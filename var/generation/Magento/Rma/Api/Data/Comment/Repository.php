<?php
namespace Magento\Rma\Api\Data\Comment;

/**
 * Repository class for @see \Magento\Rma\Api\Data\CommentInterface
 */
class Repository implements \Magento\Rma\Api\CommentRepositoryInterface
{
    /**
     * commentInterfacePersistor
     *
     * @var \Magento\Rma\Api\Data\CommentInterfacePersistor
     */
    protected $commentInterfacePersistor = null;

    /**
     * Collection Factory
     *
     * @var \Magento\Rma\Api\Data\CommentSearchResultInterfaceFactory
     */
    protected $commentInterfaceSearchResultFactory = null;

    /**
     * \Magento\Rma\Api\Data\CommentInterface[]
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
     * @param \Magento\Rma\Api\Data\CommentInterface $commentInterfacePersistor
     * @param \Magento\Rma\Api\Data\CommentSearchResultInterfaceFactory
     * $commentInterfaceSearchResultFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     * $extensionAttributesJoinProcessor
     */
    public function __construct(\Magento\Rma\Api\Data\CommentInterfacePersistor $commentInterfacePersistor, \Magento\Rma\Api\Data\CommentSearchResultInterfaceFactory $commentInterfaceSearchResultFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        $this->commentInterfacePersistor = $commentInterfacePersistor;
        $this->commentInterfaceSearchResultFactory = $commentInterfaceSearchResultFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * load entity
     *
     * @param int $id
     * @return \Magento\Rma\Api\Data\CommentInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        if (!$id) {
            throw new \Magento\Framework\Exception\InputException('ID required');
        }
        if (!isset($this->registry[$id])) {
            $entity = $this->commentInterfacePersistor->loadEntity($id);
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
     * @return \Magento\Rma\Api\Data\CommentInterface
     */
    public function create(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        return $this->commentInterfacePersistor->registerNew($entity);
    }

    /**
     * Register entity to create
     *
     * @param array $data
     * @return \Magento\Rma\Api\Data\Comment\Repository
     */
    public function createFromArray(array $data)
    {
        return $this->commentInterfacePersistor->registerFromArray($data);
    }

    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Magento\Rma\Api\Data\CommentInterface[]
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria)
    {
        $collection = $this->commentInterfaceSearchResultFactory->create();
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
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     */
    public function remove(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        $this->commentInterfacePersistor->registerDeleted($entity);
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     * @return bool
     */
    public function delete(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        $this->commentInterfacePersistor->registerDeleted($entity);
        return $this->commentInterfacePersistor->doPersistEntity($entity);
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
        $this->commentInterfacePersistor->registerDeleted($entity);
        return $this->commentInterfacePersistor->doPersistEntity($entity);
    }

    /**
     * Perform persist operations
     */
    public function flush()
    {
        $ids = $this->commentInterfacePersistor->doPersist();
        foreach ($ids as $id) {
        unset($this->registry[$id]);
        }
    }

    /**
     * Perform persist operations for one entity
     *
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     * @return \Magento\Rma\Api\Data\CommentInterface
     */
    public function save(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        $this->commentInterfacePersistor->doPersistEntity($entity);
        return $entity;
    }
}
