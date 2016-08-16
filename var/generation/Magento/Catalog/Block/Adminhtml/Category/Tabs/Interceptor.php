<?php
namespace Magento\Catalog\Block\Adminhtml\Category\Tabs;

/**
 * Interceptor class for @see \Magento\Catalog\Block\Adminhtml\Category\Tabs
 */
class Interceptor extends \Magento\Catalog\Block\Adminhtml\Category\Tabs implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Json\EncoderInterface $jsonEncoder, \Magento\Backend\Model\Auth\Session $authSession, \Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $collectionFactory, \Magento\Catalog\Helper\Catalog $helperCatalog, \Magento\Framework\Registry $registry, array $data = array())
    {
        $this->___init();
        parent::__construct($context, $jsonEncoder, $authSession, $collectionFactory, $helperCatalog, $registry, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function toHtml()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'toHtml');
        if (!$pluginInfo) {
            return parent::toHtml();
        } else {
            return $this->___callPlugins('toHtml', func_get_args(), $pluginInfo);
        }
    }
}
