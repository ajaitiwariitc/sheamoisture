<?php
namespace Sundial\CustomerImport\Controller\Index\Index;

/**
 * Interceptor class for @see \Sundial\CustomerImport\Controller\Index\Index
 */
class Interceptor extends \Sundial\CustomerImport\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Customer\Model\Customer $customer, \Magento\Eav\Model\Config $eavConfig, \Magento\Customer\Model\AddressFactory $addressFactory, \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $customerFactory, $customer, $eavConfig, $addressFactory, $regionCollectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'dispatch');
        if (!$pluginInfo) {
            return parent::dispatch($request);
        } else {
            return $this->___callPlugins('dispatch', func_get_args(), $pluginInfo);
        }
    }
}
