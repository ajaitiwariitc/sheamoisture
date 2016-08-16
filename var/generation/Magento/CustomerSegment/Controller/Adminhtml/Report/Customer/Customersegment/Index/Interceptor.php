<?php
namespace Magento\CustomerSegment\Controller\Adminhtml\Report\Customer\Customersegment\Index;

/**
 * Interceptor class for @see
 * \Magento\CustomerSegment\Controller\Adminhtml\Report\Customer\Customersegment\Index
 */
class Interceptor extends \Magento\CustomerSegment\Controller\Adminhtml\Report\Customer\Customersegment\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Backend\App\Action\Context $context, \Magento\CustomerSegment\Model\ResourceModel\Segment\CollectionFactory $collectionFactory, \Magento\Framework\Registry $coreRegistry, \Magento\Framework\App\Response\Http\FileFactory $fileFactory)
    {
        $this->___init();
        parent::__construct($context, $collectionFactory, $coreRegistry, $fileFactory);
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
