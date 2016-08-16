<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\MassDeleteRevisions;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\MassDeleteRevisions
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\MassDeleteRevisions implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\Auth\Session $backendSession, \Magento\VersionsCms\Model\Config $cmsConfig, \Magento\VersionsCms\Model\Page\Revision $pageRevision)
    {
        $this->___init();
        parent::__construct($context, $backendSession, $cmsConfig, $pageRevision);
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
