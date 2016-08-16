<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Publish;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Publish
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Publish implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\VersionsCms\Model\Page\RevisionProvider $revisionProvider)
    {
        $this->___init();
        parent::__construct($context, $revisionProvider);
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
