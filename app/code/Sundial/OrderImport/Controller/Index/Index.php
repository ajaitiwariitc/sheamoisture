<?php
namespace Sundial\OrderImport\Controller\Index;
 
class Index extends \Magento\Framework\App\Action\Action
{    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $product,
        \Magento\Framework\Data\Form\FormKey $formkey,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService,
		\Magento\Sales\Model\Service\InvoiceService $invoiceService, 
		\Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
		\Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory,
		\Magento\Sales\Model\Order\Shipment\Track $shipmentTrack,
		\Magento\Framework\DB\Transaction $dbTransaction,
		\Magento\Sales\Model\Convert\Order $orderConvert,
		\Magento\Sales\Model\Order $order,
		\Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customersFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->_product = $product;
        $this->_formkey = $formkey;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
		$this->invoiceService = $invoiceService;
		$this->invoiceSender = $invoiceSender;
		$this->shipmentFactory = $shipmentFactory;
		$this->shipmentTrack = $shipmentTrack;
		$this->dbTransaction = $dbTransaction;
		$this->orderConvert = $orderConvert;
		$this->order = $order;
		$this->_customersFactory = $customersFactory;
        parent::__construct($context);
    }

    public function execute()
    {		
	
	
		$tempOrder = array( 'currency_id'  => 'USD',
						//'email'        => 'ram10raj0@gmail.com', //buyer email id
						'customer_id' => 12,
						'shipping_address' => array('firstname'    => 'Rajamanickam', //address Details
													'lastname'     => 'China',
													'street' => 'Kerakodhalli',
													'city' => 'Dharmapuri',
													'country_id' => 'IN',
													'region' => 'TamilNadu',
													'postcode' => '635305',
													'telephone' => '9865642811',
													'fax' => '32423',
													'save_in_address_book' => 0
												),
						'items'=> array( array('product_id'=>'1','qty'=> 1),
										 array('product_id'=>'2','qty'=> 2)),//array of product which order you want to create

				);		
		$customerCollection = $this->_customersFactory->create()->addAttributeToFilter('api_customer_id',$tempOrder['customer_id'])->load()->getFirstItem();
		$store = $this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        /*$customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        /*$customer->loadByEmail($tempOrder['email']);// load customet by email address
        if(!$customer->getEntityId()){
            //If not avilable then create this customer 
            $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($tempOrder['shipping_address']['firstname'])
                    ->setLastname($tempOrder['shipping_address']['lastname'])
                    ->setEmail($tempOrder['email']) 
                    ->setPassword($tempOrder['email']);
            $customer->save();
        }*/
        $quote = $this->quote->create(); //Create object of quote
        $quote->setStore($store); //set store for which you create quote
        // if you have allready buyer id then you can load customer directly 
        $customer =  $this->customerRepository->getById($customerCollection->getEntityId());
        $quote->setCurrency();
        $quote->assignCustomer($customer); //Assign quote to customer 
        //add items in quote
        foreach($tempOrder['items'] as $item){
			$product = $this->_product->create()->load($item['product_id']);	
            $product->setPrice($product->getPrice());
            $quote->addProduct(
                $product,
                intval($item['qty'])
            );			
        }
        //Set Address to quote
        $quote->getBillingAddress()->addData($tempOrder['shipping_address']);
        $quote->getShippingAddress()->addData($tempOrder['shipping_address']); 
        // Collect Rates and Set Shipping & Payment Method 
        $shippingAddress=$quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates()
                        ->setShippingMethod('flatrate_flatrate'); //shipping method
        $quote->setPaymentMethod('checkmo'); //payment method
        $quote->setInventoryProcessed(false); //not effetc inventory
		$quote->setCreatedAt('2009-06-22 12:44:37');
		$quote->setUpdatedAt('2015-06-22 13:42:08');
        $quote->save(); //Now Save quote and your quote is ready 
        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'checkmo']); 
        // Collect Totals & Save Quote
        $quote->collectTotals()->save(); 
        // Create Order From Quote
        $order = $this->quoteManagement->submit($quote);        
        $order->setEmailSent(0);
        $increment_id = $order->getRealOrderId();
        if($order->getEntityId()){
			echo "Created = ".$increment_id;
			$this->createShipment($order);
        }else{
			echo "Not-Created";
        }
    }
	/*
	* Create the invoice for that order
	* @param $order - order object
	*/
	public function createInvoice($order){
		if($order->canInvoice()) {
			$invoice = $this->invoiceService->prepareInvoice($order);
			$invoice->register();
			//$invoice->setIncrementId('AB-10001');
			$invoice->save();
			$transactionSave = $this->dbTransaction->addObject($invoice)->addObject($invoice->getOrder());
			$transactionSave->save();
			$this->invoiceSender->send($invoice);
			//send notification code
			$order->addStatusHistoryComment(
				__('Notified customer about invoice #%1.', $invoice->getIncrementId())
			)
			->setIsCustomerNotified(true)
			->save();
			
		}
	}
	/*
	* Create the shipment for that order
	* @param $order - order object
	*/
	public function createShipment($order){
		if($order->canShip()) {
			$trackingDetails[] = array(
				'carrier_code' => 'usps',
				'title' => 'United States Postal Service',
				'number' => 'TEST',
			);		
			$shipment = $this->shipmentFactory->create($order,$item = [],$trackingDetails);
			$shipment->register();	
			$shipment->save();
			
			$this->dbTransaction
				->addObject($shipment)
				->addObject($shipment->getOrder())
				->save();
			$order->addStatusHistoryComment(
				__('Notified customer about shipment #%1.', $shipment->getIncrementId())
			)
			->setIsCustomerNotified(true)
			->save();
			$this->createInvoice($order);
				
		}				
	}
}