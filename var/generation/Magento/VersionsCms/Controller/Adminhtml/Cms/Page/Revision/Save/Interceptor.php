<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Save;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Save
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor $dataProcessor, \Magento\Backend\Model\Auth\Session $authentication, \Magento\VersionsCms\Model\Page\RevisionProvider $revisionProvider)
    {
        $this->___init();
        parent::__construct($context, $dataProcessor, $authentication, $revisionProvider);
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
