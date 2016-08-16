<?php
namespace Magento\VersionsCms\Controller\Page\Revision\Drop;

/**
 * Interceptor class for @see \Magento\VersionsCms\Controller\Page\Revision\Drop
 */
class Interceptor extends \Magento\VersionsCms\Controller\Page\Revision\Drop implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Cms\Model\Page $page, \Magento\VersionsCms\Model\Page\RevisionProvider $revisionProvider, \Magento\Framework\App\DesignInterface $design, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Locale\ResolverInterface $localeResolver)
    {
        $this->___init();
        parent::__construct($context, $page, $revisionProvider, $design, $storeManager, $localeResolver);
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
