<?php
namespace Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Preview;

/**
 * Interceptor class for @see
 * \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Preview
 */
class Interceptor extends \Magento\VersionsCms\Controller\Adminhtml\Cms\Page\Revision\Preview implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\VersionsCms\Model\PageLoader $pageLoader, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Backend\App\ConfigInterface $config, \Magento\Framework\Session\Config\ConfigInterface $sessionConfig, \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager, \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory)
    {
        $this->___init();
        parent::__construct($context, $pageLoader, $storeManager, $config, $sessionConfig, $cookieManager, $cookieMetadataFactory);
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
