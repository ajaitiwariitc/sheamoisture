<?php
namespace Magento\Rma\Api\Data;

/**
 * Persistor class for @see \Magento\Rma\Api\Data\RmaInterface
 */
class RmaInterfacePersistor
{
    /**
     * Entity factory
     *
     * @var \Magento\Rma\Api\Data\RmaInterfaceFactory
     */
    protected $rmaInterfaceFactory = null;

    /**
     * Resource model
     *
     * @var \Magento\Rma\Model\Spi\RmaResourceInterface
     */
    protected $rmaInterfaceResource = null;

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
     * @param \Magento\Rma\Model\Spi\RmaResourceInterface $rmaInterfaceResource
     * @param \Magento\Rma\Api\Data\RmaInterfaceFactory $rmaInterfaceFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(\Magento\Rma\Model\Spi\RmaResourceInterface $rmaInterfaceResource, \Magento\Rma\Api\Data\RmaInterfaceFactory $rmaInterfaceFactory, \Magento\Framework\App\ResourceConnection $resource)
    {
        $this->rmaInterfaceResource = $rmaInterfaceResource;
        $this->rmaInterfaceFactory = $rmaInterfaceFactory;
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
     * @return \Magento\Rma\Api\Data\RmaInterfacePersistor $entity
     */
    public function loadEntity($key)
    {
        $entity = $this->rmaInterfaceFactory->create()->load($key);
        return $entity;
    }

    /**
     * Register entity to delete
     *
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     */
    public function registerDeleted(\Magento\Rma\Api\Data\RmaInterface $entity)
    {
        $hash = spl_object_hash($entity);array_push($this->stack, $hash);$this->entitiesPool[$hash] = [    'entity' => $entity,    'action' => 'removed'];
    }

    /**
     * Register entity to create
     *
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     */
    public function registerNew(\Magento\Rma\Api\Data\RmaInterface $entity)
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
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     */
    public function registerFromArray(array $data)
    {
        $entity = $this->rmaInterfaceFactory->create(['data' => $data]);
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
                        $this->rmaInterfaceResource->save($data['entity']);
                        $ids[] = $data['entity']->getId();
                    } else {
                        $ids[] = $data['entity']->getId();
                        $this->rmaInterfaceResource->delete($data['removed']);
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
     * @param \Magento\Rma\Api\Data\RmaInterface $entity
     */
    public function doPersistEntity(\Magento\Rma\Api\Data\RmaInterface $entity)
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
