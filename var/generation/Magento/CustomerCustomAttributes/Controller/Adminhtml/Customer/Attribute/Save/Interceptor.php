<?php
namespace Magento\CustomerCustomAttributes\Controller\Adminhtml\Customer\Attribute\Save;

/**
 * Interceptor class for @see
 * \Magento\CustomerCustomAttributes\Controller\Adminhtml\Customer\Attribute\Save
 */
class Interceptor extends \Magento\CustomerCustomAttributes\Controller\Adminhtml\Customer\Attribute\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry, \Magento\Eav\Model\Config $eavConfig, \Magento\Customer\Model\AttributeFactory $attrFactory, \Magento\Eav\Model\Entity\Attribute\SetFactory $attrSetFactory, \Magento\Store\Model\WebsiteFactory $websiteFactory, \Magento\CustomerCustomAttributes\Helper\Data $helperData, \Magento\CustomerCustomAttributes\Helper\Customer $helperCustomer, \Magento\Framework\Filter\FilterManager $filterManager)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry, $eavConfig, $attrFactory, $attrSetFactory, $websiteFactory, $helperData, $helperCustomer, $filterManager);
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
