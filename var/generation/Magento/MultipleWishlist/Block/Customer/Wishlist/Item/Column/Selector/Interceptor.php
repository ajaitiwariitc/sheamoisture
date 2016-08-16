<?php
namespace Magento\MultipleWishlist\Block\Customer\Wishlist\Item\Column\Selector;

/**
 * Interceptor class for @see
 * \Magento\MultipleWishlist\Block\Customer\Wishlist\Item\Column\Selector
 */
class Interceptor extends \Magento\MultipleWishlist\Block\Customer\Wishlist\Item\Column\Selector implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\App\Http\Context $httpContext, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $httpContext, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage($product, $imageId, $attributes = array())
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getImage');
        if (!$pluginInfo) {
            return parent::getImage($product, $imageId, $attributes);
        } else {
            return $this->___callPlugins('getImage', func_get_args(), $pluginInfo);
        }
    }
}
