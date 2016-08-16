<?php
namespace Magento\GiftCardAccount\Block\Adminhtml\Giftcardaccount\Edit;

/**
 * Interceptor class for @see
 * \Magento\GiftCardAccount\Block\Adminhtml\Giftcardaccount\Edit
 */
class Interceptor extends \Magento\GiftCardAccount\Block\Adminhtml\Giftcardaccount\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Widget\Context $context, \Magento\Framework\Registry $registry, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function canRender(\Magento\Backend\Block\Widget\Button\Item $item)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'canRender');
        if (!$pluginInfo) {
            return parent::canRender($item);
        } else {
            return $this->___callPlugins('canRender', func_get_args(), $pluginInfo);
        }
    }
}