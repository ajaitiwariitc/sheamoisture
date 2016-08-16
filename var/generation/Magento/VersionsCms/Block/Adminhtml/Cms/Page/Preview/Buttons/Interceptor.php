<?php
namespace Magento\VersionsCms\Block\Adminhtml\Cms\Page\Preview\Buttons;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Block\Adminhtml\Cms\Page\Preview\Buttons
 */
class Interceptor extends \Magento\VersionsCms\Block\Adminhtml\Cms\Page\Preview\Buttons implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Widget\Context $context, \Magento\VersionsCms\Model\Config $cmsConfig, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $cmsConfig, $data);
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
