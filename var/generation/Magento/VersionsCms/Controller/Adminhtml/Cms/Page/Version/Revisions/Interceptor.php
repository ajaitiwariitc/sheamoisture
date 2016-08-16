<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\Revisions;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\Revisions
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\Revisions implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\VersionsCms\Model\PageLoader $pageLoader, \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Version\VersionProvider $versionProvider)
    {
        $this->___init();
        parent::__construct($context, $pageLoader, $versionProvider);
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
