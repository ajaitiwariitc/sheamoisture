<?php
namespace Magento\GiftCard\Model\Attribute\Backend\Giftcard\Amount;

/**
 * Interceptor class for @see
 * \Magento\GiftCard\Model\Attribute\Backend\Giftcard\Amount
 */
class Interceptor extends \Magento\GiftCard\Model\Attribute\Backend\Giftcard\Amount implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Directory\Model\CurrencyFactory $currencyFactory, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Helper\Data $catalogData, \Magento\Framework\App\Config\ScopeConfigInterface $config, \Magento\Framework\Locale\FormatInterface $localeFormat, \Magento\Directory\Helper\Data $directoryHelper, \Magento\GiftCard\Model\ResourceModel\Attribute\Backend\Giftcard\Amount $amountResource)
    {
        $this->___init();
        parent::__construct($currencyFactory, $storeManager, $catalogData, $config, $localeFormat, $directoryHelper, $amountResource);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
