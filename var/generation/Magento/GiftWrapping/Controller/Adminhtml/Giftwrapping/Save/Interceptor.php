<?php
namespace Magento\GiftWrapping\Controller\Adminhtml\Giftwrapping\Save;

/**
 * Interceptor class for @see
 * \Magento\GiftWrapping\Controller\Adminhtml\Giftwrapping\Save
 */
class Interceptor extends \Magento\GiftWrapping\Controller\Adminhtml\Giftwrapping\Save implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\Framework\Registry $coreRegistry)
    {
        $this->___init();
        parent::__construct($context, $coreRegistry);
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
