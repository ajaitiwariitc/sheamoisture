<?php
namespace Magento\Rma\Block\Adminhtml\Rma\Edit\Tab\General\Shipping\Information;

/**
 * Interceptor class for @see
 * \Magento\Rma\Block\Adminhtml\Rma\Edit\Tab\General\Shipping\Information
 */
class Interceptor extends \Magento\Rma\Block\Adminhtml\Rma\Edit\Tab\General\Shipping\Information implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Rma\Helper\Data $rmaData, \Magento\Framework\Registry $registry, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Shipping\Model\Carrier\Source\GenericInterface $sourceSizeModel, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $rmaData, $registry, $orderFactory, $sourceSizeModel, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function isGirthAllowed()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isGirthAllowed');
        if (!$pluginInfo) {
            return parent::isGirthAllowed();
        } else {
            return $this->___callPlugins('isGirthAllowed', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function checkSizeAndGirthParameter()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'checkSizeAndGirthParameter');
        if (!$pluginInfo) {
            return parent::checkSizeAndGirthParameter();
        } else {
            return $this->___callPlugins('checkSizeAndGirthParameter', func_get_args(), $pluginInfo);
        }
    }
}
