<?php
namespace Magento\Rma\Api\Data\Rma;

/**
 * Repository class for @see \Magento\Rma\Api\Data\RmaInterface
 */
class Repository implements \Magento\Rma\Api\RmaRepositoryInterface
{
    /**
     * rmaInterfacePersistor
     *
     * @var \Magento\Rma\Api\Data\RmaInterfacePersistor
     */
    protected $rmaInterfacePersistor = null;

    /**
     * Collection Factory
     *
     * @var \Magento\Rma\Api\Data\RmaSearchResultInterfaceFactory
     */
    protected $rmaInterfaceSearchResultFactory = null;

    /**
     * \Magento\Rma\Api\Data\RmaInterface[]
     *
     * @var array
     */
    protected $registry = array(
        
    );

    /**
     * Extension attributes join processor.
     *
     * @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor = null;

    /**
     * Repository constructor
     *
     * @param \Magento\Rma\Api\Data\RmaInterface $rmaInterfacePersistor
     * @param \Magento\Rma\Api\Data\RmaSearchResultInterfaceFactory
     * $rmaInterfaceSearchResultFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     * $extensionAttributesJoinProcessor
     */
    public function __construct(\Magento\Rma\Api\Data\RmaInterfacePersistor $rmaInterfacePersistor, \Magento\Rma\Api\Data\RmaSearchResultInterfaceFactory $rmaInterfaceSearchResultFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        $this->rmaInterfacePersistor = $rmaInterfacePersistor;
        $this->rmaInterfaceSearchResultFactory = $rmaInterfaceSearchResultFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * load entity
     *
     * @param int $id
     * @return \Magento\Rma\Api\Data\RmaInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        if (!$id) {
            throw new \Magento\Framework\Exception\InputException('ID required');
        }
        if (!isset($this->registry[$id])) {
            $entity = $this->rmaInterfacePersistor->loadEntity($id);
            if (!$entity->getId()) {
                throw new \Magento\Framework\Exception\NoSuchEntityException('Requested entity doesn\'t exist');
            }
            $this->registry[$id] = $entity;
        }
        return $this->registry[$id];
    }

    /**
     * Register entity to create
     *
     * @param array $data
     * @return \Magento\Rma\Api\Data\RmaInterface
     */
    public function create(\Magento\Rma\Api\Data\RmaInterface $entity)
    {
        return $this->rmaInterfacePersistor->registerNew($entity);
    }

    /**
     * Register entity to create
     *
     * @param array $data
     * @return \Magento\Rma\Api\Data\Rma\Repository
     */
    public function createFromArray(array $data)
    {
        return $this->rmaInterfacePersistor->registerFromArray($data);
    }

    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Magento\Rma\Api\Data\RmaInterface[]
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria)
    {
        $collection = $this->rmaInterfaceSearchResultFactory->create();
        $this->extensionAttributesJoinProcessor->process($collection);
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        return $collection;
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     */
    public function remove(\Magento\Rma\Api\Data\RmaInterface $entity)
    {
        $this->rmaInterfacePersistor->registerDeleted($entity);
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     * @return bool
     */
    public function delete(\Magento\Rma\Api\Data\RmaInterface $entity)
    {
        $this->rmaInterfacePersistor->registerDeleted($entity);
        return $this->rmaInterfacePersistor->doPersistEntity($entity);
    }

    /**
     * Delete entity by Id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById($id)
    {
        $entity = $this->get($id);
        $this->rmaInterfacePersistor->registerDeleted($entity);
        return $this->rmaInterfacePersistor->doPersistEntity($entity);
    }

    /**
     * Perform persist operations
     */
    public function flush()
    {
        $ids = $this->rmaInterfacePersistor->doPersist();
        foreach ($ids as $id) {
        unset($this->registry[$id]);
        }
    }

    /**
     * Perform persist operations for one entity
     *
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     * @return \Magento\Rma\Api\Data\RmaInterface
     */
    public function save(\Magento\Rma\Api\Data\RmaInterface $entity)
    {
        $this->rmaInterfacePersistor->doPersistEntity($entity);
        return $entity;
    }
}
