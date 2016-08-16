<?php
namespace Magento\Solr\Controller\Adminhtml\Search\System\Config\TestConnection\Ping;

/**
 * Interceptor class for @see
 * \Magento\Solr\Controller\Adminhtml\Search\System\Config\TestConnection\Ping
 */
class Interceptor extends \Magento\Solr\Controller\Adminhtml\Search\System\Config\TestConnection\Ping implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Solr\Model\Client\FactoryInterface $clientFactory, \Magento\Solr\Helper\ClientOptionsInterface $clientHelper, \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->___init();
        parent::__construct($context, $clientFactory, $clientHelper, $resultJsonFactory);
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
