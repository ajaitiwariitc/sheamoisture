<?php
namespace Magento\Rma\Block\Adminhtml\Rma\Edit\Tab\General\Shippingmethod;

/**
 * Interceptor class for @see
 * \Magento\Rma\Block\Adminhtml\Rma\Edit\Tab\General\Shippingmethod
 */
class Interceptor extends \Magento\Rma\Block\Adminhtml\Rma\Edit\Tab\General\Shippingmethod implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Tax\Helper\Data $taxData, \Magento\Rma\Helper\Data $rmaData, \Magento\Rma\Model\ShippingFactory $shippingFactory, \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $taxData, $rmaData, $shippingFactory, $priceCurrency, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function canDisplayCustomValue()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canDisplayCustomValue');
        if (!$pluginInfo) {
            return parent::canDisplayCustomValue();
        } else {
            return $this->___callPlugins('canDisplayCustomValue', func_get_args(), $pluginInfo);
        }
    }
}
