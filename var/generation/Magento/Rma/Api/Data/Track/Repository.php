<?php
namespace Magento\Rma\Api\Data\Track;

/**
 * Repository class for @see \Magento\Rma\Api\Data\TrackInterface
 */
class Repository implements \Magento\Rma\Api\TrackRepositoryInterface
{
    /**
     * trackInterfacePersistor
     *
     * @var \Magento\Rma\Api\Data\TrackInterfacePersistor
     */
    protected $trackInterfacePersistor = null;

    /**
     * Collection Factory
     *
     * @var \Magento\Rma\Api\Data\TrackSearchResultInterfaceFactory
     */
    protected $trackInterfaceSearchResultFactory = null;

    /**
     * \Magento\Rma\Api\Data\TrackInterface[]
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
     * @param \Magento\Rma\Api\Data\TrackInterface $trackInterfacePersistor
     * @param \Magento\Rma\Api\Data\TrackSearchResultInterfaceFactory
     * $trackInterfaceSearchResultFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     * $extensionAttributesJoinProcessor
     */
    public function __construct(\Magento\Rma\Api\Data\TrackInterfacePersistor $trackInterfacePersistor, \Magento\Rma\Api\Data\TrackSearchResultInterfaceFactory $trackInterfaceSearchResultFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        $this->trackInterfacePersistor = $trackInterfacePersistor;
        $this->trackInterfaceSearchResultFactory = $trackInterfaceSearchResultFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * load entity
     *
     * @param int $id
     * @return \Magento\Rma\Api\Data\TrackInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        if (!$id) {
            throw new \Magento\Framework\Exception\InputException('ID required');
        }
        if (!isset($this->registry[$id])) {
            $entity = $this->trackInterfacePersistor->loadEntity($id);
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
     * @return \Magento\Rma\Api\Data\TrackInterface
     */
    public function create(\Magento\Rma\Api\Data\TrackInterface $entity)
    {
        return $this->trackInterfacePersistor->registerNew($entity);
    }

    /**
     * Register entity to create
     *
     * @param array $data
     * @return \Magento\Rma\Api\Data\Track\Repository
     */
    public function createFromArray(array $data)
    {
        return $this->trackInterfacePersistor->registerFromArray($data);
    }

    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Magento\Rma\Api\Data\TrackInterface[]
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria)
    {
        $collection = $this->trackInterfaceSearchResultFactory->create();
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
     * @param \Magento\Rma\Api\Data\TrackInterface $entity
     */
    public function remove(\Magento\Rma\Api\Data\TrackInterface $entity)
    {
        $this->trackInterfacePersistor->registerDeleted($entity);
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\Rma\Api\Data\TrackInterface $entity
     * @return bool
     */
    public function delete(\Magento\Rma\Api\Data\TrackInterface $entity)
    {
        $this->trackInterfacePersistor->registerDeleted($entity);
        return $this->trackInterfacePersistor->doPersistEntity($entity);
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
        $this->trackInterfacePersistor->registerDeleted($entity);
        return $this->trackInterfacePersistor->doPersistEntity($entity);
    }

    /**
     * Perform persist operations
     */
    public function flush()
    {
        $ids = $this->trackInterfacePersistor->doPersist();
        foreach ($ids as $id) {
        unset($this->registry[$id]);
        }
    }

    /**
     * Perform persist operations for one entity
     *
     * @param \Magento\Rma\Api\Data\TrackInterface $entity
     * @return \Magento\Rma\Api\Data\TrackInterface
     */
    public function save(\Magento\Rma\Api\Data\TrackInterface $entity)
    {
        $this->trackInterfacePersistor->doPersistEntity($entity);
        return $entity;
    }
}
