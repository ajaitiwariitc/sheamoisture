<?php
namespace Sundial\OrderImport\Controller\Index\Index;

/**
 * Interceptor class for @see \Sundial\OrderImport\Controller\Index\Index
 */
class Interceptor extends \Sundial\OrderImport\Controller\Index\Index implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Catalog\Model\ProductFactory $product, \Magento\Framework\Data\Form\FormKey $formkey, \Magento\Quote\Model\QuoteFactory $quote, \Magento\Quote\Model\QuoteManagement $quoteManagement, \Magento\Customer\Model\CustomerFactory $customerFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository, \Magento\Sales\Model\Service\OrderService $orderService, \Magento\Sales\Model\Service\InvoiceService $invoiceService, \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender, \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory, \Magento\Sales\Model\Order\Shipment\Track $shipmentTrack, \Magento\Framework\DB\Transaction $dbTransaction, \Magento\Sales\Model\Convert\Order $orderConvert, \Magento\Sales\Model\Order $order, \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customersFactory)
    {
        $this->___init();
        parent::__construct($context, $storeManager, $product, $formkey, $quote, $quoteManagement, $customerFactory, $customerRepository, $orderService, $invoiceService, $invoiceSender, $shipmentFactory, $shipmentTrack, $dbTransaction, $orderConvert, $order, $customersFactory);
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
