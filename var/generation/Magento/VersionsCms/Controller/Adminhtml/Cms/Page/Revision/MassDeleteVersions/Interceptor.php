<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\MassDeleteVersions;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\MassDeleteVersions
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\MassDeleteVersions implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Backend\Model\Auth\Session $backendSession, \Magento\VersionsCms\Model\Config $cmsConfig, \Magento\VersionsCms\Model\Page\Version $pageVersion)
    {
        $this->___init();
        parent::__construct($context, $backendSession, $cmsConfig, $pageVersion);
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
