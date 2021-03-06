<?php
namespace Magento\Rma\Model\RmaRepository;

/**
 * Interceptor class for @see \Magento\Rma\Model\RmaRepository
 */
class Interceptor extends \Magento\Rma\Model\RmaRepository implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Rma\Model\RmaFactory $rmaFactory, \Magento\Rma\Model\ResourceModel\Rma\CollectionFactory $rmaCollectionFactory)
    {
        $this->___init();
        parent::__construct($rmaFactory, $rmaCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'get');
        if (!$pluginInfo) {
            return parent::get($id);
        } else {
            return $this->___callPlugins('get', func_get_args(), $pluginInfo);
        }
    }
}
