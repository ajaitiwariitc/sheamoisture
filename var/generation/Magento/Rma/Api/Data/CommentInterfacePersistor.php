<?php
namespace Magento\Rma\Api\Data;

/**
 * Persistor class for @see \Magento\Rma\Api\Data\CommentInterface
 */
class CommentInterfacePersistor
{
    /**
     * Entity factory
     *
     * @var \Magento\Rma\Api\Data\CommentInterfaceFactory
     */
    protected $commentInterfaceFactory = null;

    /**
     * Resource model
     *
     * @var \Magento\Rma\Model\Spi\CommentResourceInterface
     */
    protected $commentInterfaceResource = null;

    /**
     * Application Resource
     *
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource = null;

    /**
     * Database Adapter
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection = null;

    /**
     * @var array
     */
    protected $entitiesPool = array(
        
    );

    /**
     * @var array
     */
    protected $stack = array(
        
    );

    /**
     * Persistor constructor
     *
     * @param \Magento\Rma\Model\Spi\CommentResourceInterface $commentInterfaceResource
     * @param \Magento\Rma\Api\Data\CommentInterfaceFactory $commentInterfaceFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(\Magento\Rma\Model\Spi\CommentResourceInterface $commentInterfaceResource, \Magento\Rma\Api\Data\CommentInterfaceFactory $commentInterfaceFactory, \Magento\Framework\App\ResourceConnection $resource)
    {
        $this->commentInterfaceResource = $commentInterfaceResource;
        $this->commentInterfaceFactory = $commentInterfaceFactory;
        $this->resource = $resource;
    }

    /**
     * Returns Adapter interface
     *
     * @return array \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        }
        return $this->connection;
    }

    /**
     * Load entity by key
     *
     * @param int $key
     * @return \Magento\Rma\Api\Data\CommentInterfacePersistor $entity
     */
    public function loadEntity($key)
    {
        $entity = $this->commentInterfaceFactory->create()->load($key);
        return $entity;
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     */
    public function registerDeleted(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        $hash = spl_object_hash($entity);array_push($this->stack, $hash);$this->entitiesPool[$hash] = [    'entity' => $entity,    'action' => 'removed'];
    }

    /**
     * Register entity to create
     *
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     */
    public function registerNew(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        $hash = spl_object_hash($entity);
        $data = [
        'entity' => $entity,
        'action' => 'created'
        ];
        array_push($this->stack, $hash);
        $this->entitiesPool[$hash] = $data;
    }

    /**
     * Register entity to create
     *
     * @param array $data
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     */
    public function registerFromArray(array $data)
    {
        $entity = $this->commentInterfaceFactory->create(['data' => $data]);
        $this->registerNew($entity);
        return $entity;
    }

    /**
     * Perform persist operation
     *
     * @param int $items
     * @return array
     */
    public function doPersist($items = 0)
    {
        $ids = [];
        $this->getConnection()->beginTransaction();
        try {
            do {
                $hash = array_pop($this->stack);
                if (isset($this->entitiesPool[$hash])) {
                    $data = $this->entitiesPool[$hash];
                    if ($data['action'] == 'created') {
                        $this->commentInterfaceResource->save($data['entity']);
                        $ids[] = $data['entity']->getId();
                    } else {
                        $ids[] = $data['entity']->getId();
                        $this->commentInterfaceResource->delete($data['removed']);
                    }
                }
                unset($this->entitiesPool[$hash]);
                $items--;
            } while (!empty($this->entitiesPool) || $items === 0);
            $this->getConnection()->commit();
            return $ids;
        } catch (\Exception $e) {
            $this->getConnection()->rollback();
            throw $e;
        }
    }

    /**
     * Persist entity
     *
     * @param \Magento\Rma\Api\Data\CommentInterface $entity
     */
    public function doPersistEntity(\Magento\Rma\Api\Data\CommentInterface $entity)
    {
        $hash = spl_object_hash($entity);
        if (isset($this->entitiesPool[$hash])) {
        $tempStack = $this->stack;
        array_flip($tempStack);
        unset($tempStack[$hash]);
        $this->stack = array_flip($tempStack);
        unset($this->entitiesPool[$hash]);
        }
        $this->registerNew($entity);
        return $this->doPersist(1);
    }
}
