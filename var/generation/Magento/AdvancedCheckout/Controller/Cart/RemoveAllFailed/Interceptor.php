<?php
namespace Magento\AdvancedCheckout\Controller\Cart\RemoveAllFailed;

/**
 * Interceptor class for @see
 * \Magento\AdvancedCheckout\Controller\Cart\RemoveAllFailed
 */
class Interceptor extends \Magento\AdvancedCheckout\Controller\Cart\RemoveAllFailed implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context)
    {
        $this->___init();
        parent::__construct($context);
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
