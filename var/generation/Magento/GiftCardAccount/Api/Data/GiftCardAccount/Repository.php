<?php
namespace Magento\GiftCardAccount\Api\Data\GiftCardAccount;

/**
 * Repository class for @see
 * \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface
 */
class Repository implements \Magento\GiftCardAccount\Api\GiftCardAccountRepositoryInterface
{
    /**
     * giftCardAccountInterfacePersistor
     *
     * @var \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterfacePersistor
     */
    protected $giftCardAccountInterfacePersistor = null;

    /**
     * Collection Factory
     *
     * @var
     * \Magento\GiftCardAccount\Api\Data\GiftCardAccountSearchResultInterfaceFactory
     */
    protected $giftCardAccountInterfaceSearchResultFactory = null;

    /**
     * \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface[]
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
     * @param \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface
     * $giftCardAccountInterfacePersistor
     * @param
     * \Magento\GiftCardAccount\Api\Data\GiftCardAccountSearchResultInterfaceFactory
     * $giftCardAccountInterfaceSearchResultFactory
     * @param \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     * $extensionAttributesJoinProcessor
     */
    public function __construct(\Magento\GiftCardAccount\Api\Data\GiftCardAccountInterfacePersistor $giftCardAccountInterfacePersistor, \Magento\GiftCardAccount\Api\Data\GiftCardAccountSearchResultInterfaceFactory $giftCardAccountInterfaceSearchResultFactory, \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor)
    {
        $this->giftCardAccountInterfacePersistor = $giftCardAccountInterfacePersistor;
        $this->giftCardAccountInterfaceSearchResultFactory = $giftCardAccountInterfaceSearchResultFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * load entity
     *
     * @param int $id
     * @return \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($id)
    {
        if (!$id) {
            throw new \Magento\Framework\Exception\InputException('ID required');
        }
        if (!isset($this->registry[$id])) {
            $entity = $this->giftCardAccountInterfacePersistor->loadEntity($id);
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
     * @return \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface
     */
    public function create(\Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity)
    {
        return $this->giftCardAccountInterfacePersistor->registerNew($entity);
    }

    /**
     * Register entity to create
     *
     * @param array $data
     * @return \Magento\GiftCardAccount\Api\Data\GiftCardAccount\Repository
     */
    public function createFromArray(array $data)
    {
        return $this->giftCardAccountInterfacePersistor->registerFromArray($data);
    }

    /**
     * Find entities by criteria
     *
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface[]
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria)
    {
        $collection = $this->giftCardAccountInterfaceSearchResultFactory->create();
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
     * @param \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity
     */
    public function remove(\Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity)
    {
        $this->giftCardAccountInterfacePersistor->registerDeleted($entity);
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity
     * @return bool
     */
    public function delete(\Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity)
    {
        $this->giftCardAccountInterfacePersistor->registerDeleted($entity);
        return $this->giftCardAccountInterfacePersistor->doPersistEntity($entity);
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
        $this->giftCardAccountInterfacePersistor->registerDeleted($entity);
        return $this->giftCardAccountInterfacePersistor->doPersistEntity($entity);
    }

    /**
     * Perform persist operations
     */
    public function flush()
    {
        $ids = $this->giftCardAccountInterfacePersistor->doPersist();
        foreach ($ids as $id) {
        unset($this->registry[$id]);
        }
    }

    /**
     * Perform persist operations for one entity
     *
     * @param \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity
     * @return \Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface
     */
    public function save(\Magento\GiftCardAccount\Api\Data\GiftCardAccountInterface $entity)
    {
        $this->giftCardAccountInterfacePersistor->doPersistEntity($entity);
        return $entity;
    }
}
