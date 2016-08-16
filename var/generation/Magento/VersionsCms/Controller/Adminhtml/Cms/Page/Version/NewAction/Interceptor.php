<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\NewAction;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\NewAction
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\NewAction implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, \Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor $filter, \Magento\Backend\Model\Auth\Session $authSession, \Magento\VersionsCms\Model\Config $cmsConfig, \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\VersionProvider $versionProvider)
    {
        $this->___init();
        parent::__construct($context, $resultForwardFactory, $filter, $authSession, $cmsConfig, $versionProvider);
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
