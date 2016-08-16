<?php
namespace Magento\VersionsCms\Block\Adminhtml\Cms\Page\Revision\Edit\Info;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Block\Adminhtml\Cms\Page\Revision\Edit\Info
 */
class Interceptor extends \Magento\VersionsCms\Block\Adminhtml\Cms\Page\Revision\Edit\Info implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Widget\Context $context, \Magento\Framework\Registry $registry, \Magento\Backend\Model\Auth\Session $authSession, \Magento\User\Model\UserFactory $userFactory, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $registry, $authSession, $userFactory, $data);
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
