<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\Edit;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\Edit
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\Edit implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory, \Magento\Framework\Registry $registry, \Magento\VersionsCms\Model\PageLoader $pageLoader, \Magento\VersionsCms\Model\Config $cmsConfig, \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\VersionProvider $versionProvider)
    {
        $this->___init();
        parent::__construct($context, $resultPageFactory, $registry, $pageLoader, $cmsConfig, $versionProvider);
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
