<?php
/**
 * Copyright © 2016 Sundial. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Sundial\OrderImport\Cron;

class ImportOrder
{
	 /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
	 * @param \Magento\Customer\Model\AddressFactory    $addressFactory
	 * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory    $regionCollectionFactory
     */
	protected $storeManager;
    protected $customerFactory;
	protected $addressFactory;
	protected $regionCollectionFactory;
   
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface  $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Customer\Model\Customer $customer, 
		\Magento\Customer\Model\AddressFactory $addressFactory,
		\Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
    ) {
        $this->storeManager     = $storeManager;
        $this->customerFactory  = $customerFactory;
		$this->addressFactory  	= $addressFactory;
		$this->regionCollectionFactory = $regionCollectionFactory;
    }

    public function execute()
    {
		echo "Order Import";
		exit;
		echo $time_start = microtime(true); 
		echo "<br>";
		$websiteId  = $this->storeManager->getStore()->getWebsiteId();
		$customerdata = $this->getCustomerDataByApi();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		foreach($customerdata as $customervalue){
			//checking customer already exists or not.
			$customerAlreadyAvailable = $objectManager->create('Magento\Customer\Model\Customer')->setWebsiteId($websiteId)->loadByEmail($customervalue['email']);
			if($customerAlreadyAvailable->hasData()){
				$customer   = $customerAlreadyAvailable;
			}else{
				$customer   = $this->customerFactory->create();
			}
			$customerDataobj = $customer->getDataModel();
			// Preparing data for new customer
			$grourpId = $this->getCustomerGroupMappedId($customervalue['group_id']);
			$isActive = ($customervalue['is_active'] == true ) ? 1: 0;
			$customerDataobj->setWebsiteId($websiteId);			
			$customerDataobj->setEmail($customervalue['email']); 
			$customerDataobj->setCustomAttribute('api_customer_id', $customervalue['api_customer_id']);
			$customerDataobj->setGroupId($grourpId);
			$customer->setIsActive($isActive);//this will be default is 1 we can't change.			
			$customerDataobj->setFirstname($customervalue['billing_address']['firstname']);
			$customerDataobj->setLastname($customervalue['billing_address']['lastname']);
			$customer->setPassword($customervalue['password']);
			// Save data
			try{
				 $customer->updateData($customerDataobj);
                 $customer->save();
            }catch (Exception $e) {
                 Zend_Debug::dump($e->getMessage());
            }
			$billingData = $customervalue['billing_address'];
			$shippingData = $customervalue['shipping_address'];
			if($customervalue['billing_same_as_shipping']){
				$this->saveBillingSameAsShipping($billingData,$customer->getId());
			}else{
				$this->saveBillingAddress($billingData,$customer->getId());
				$this->saveShippingAddress($shippingData,$customer->getId());
			}
			
		}
		echo $time_end = microtime(true);
		echo "<br>";
		$execution_time = ($time_end - $time_start)/60;

		//execution time of the script
		echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
    }
	
	public function getCustomerGroupMappedId($apiCustomerGroupId){		
		return 1;
		/*
		* @param 3cart_group_id as key		
		* @param magento_group_id as value
		*/
		
		//array( 1 => 3 , 2 => )
	}
	/* Get the 3rd cart data as array.	
	*/
	public function getCustomerDataByApi(){
		
		$data = array(
					0 => array('email' => 'ram10raj0@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					1 => array('email' => 'ram10raj1@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					2 => array('email' => 'ram10raj2@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					3 => array('email' => 'ram10raj3@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					4 => array('email' => 'ram10raj4@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					5 => array('email' => 'ram10raj5@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					6 => array('email' => 'ram10raj6@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					7 => array('email' => 'ram10raj7@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					8 => array('email' => 'ram10raj8@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					9 => array('email' => 'ram10raj9@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					10 => array('email' => 'ram10raj10@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					11 => array('email' => 'ram10raj11@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					12 => array('email' => 'ram10raj12@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					13 => array('email' => 'ram10raj13@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					14 => array('email' => 'ram10raj14@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					15 => array('email' => 'ram10raj15@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					16 => array('email' => 'ram10raj16@mail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					17 => array('email' => 'ram10raj17@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					18 => array('email' => 'ram10raj18@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					19 => array('email' => 'ram10raj19@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					20 => array('email' => 'ram10raj20@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					21 => array('email' => 'ram10raj21@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					22 => array('email' => 'ram10raj22@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					23 => array('email' => 'ram10raj23@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					24 => array('email' => 'ram10raj24@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					25 => array('email' => 'ram10raj25@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					26 => array('email' => 'ram10raj26@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					27 => array('email' => 'ram10raj27@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					28 => array('email' => 'ram10raj28@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					29 => array('email' => 'ram10raj129@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854')),
					30 => array('email' => 'ram10raj130@gmail.com','password' => 'ram@123','is_active' => true,'group_id' => 2,'api_customer_id' => '10','billing_same_as_shipping'=> false,'billing_address' => array('firstname' => 'Ramki','lastname' => 'Ram','company' => 'Velan info service','street' => 'No:21,vadavalli','city' => 'coimbatore','region' => 'CA','country_id' => 'US','postcode' => '635305','telephone' => '8220271604'),'shipping_address' => array('firstname' => 'Aarthi','lastname' => 'Madhu','company' => 'Gandhigaram','street' => 'N0:1/175,Kerakodhalli','city' => 'Dharmapuri','region' => 'Tamil nadu','country_id' => 'IN','postcode' => '600001','telephone' => '9092477854'))
				);
				return $data;
	}
	/* To save the billing and shipping  address as default
	* @param array addressData
	* @param int customerId
	*/
	public function saveBillingSameAsShipping($addressData,$customerId){
		
			$websiteId  = $this->storeManager->getStore()->getWebsiteId();
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$customerAlreadyAvailable = $objectManager->create('Magento\Customer\Model\Customer')->setWebsiteId($websiteId)->load($customerId);
			if($customerAlreadyAvailable->hasData()){
				$address = $objectManager->create('Magento\Customer\Model\Customer')->getAddressById($customerAlreadyAvailable->getDefaultBilling());
			}else{
				 $address = $this->addressFactory->create();
			}			
            $address->setCustomerId($customerId)
            ->setFirstname($addressData['firstname'])
            ->setLastname($addressData['lastname'])
            ->setCountryId($addressData['country_id'])
            ->setPostcode($addressData['postcode'])
            ->setCity($addressData['city'])
            ->setTelephone($addressData['telephone'])
            ->setCompany($addressData['company'])
            ->setStreet($addressData['street'])
            ->setIsDefaultBilling('1')
			->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');
			$region = $this->getRegionIdByName($addressData['region'],$addressData['country_id']);
			if ($region->getSize()) {
				$address->setRegionId($region->getFirstItem()->getId())
				->setRegion($region->getFirstItem()->getName());
			}else{
				$address->setRegion($addressData['region']);
			}
            try{
                $address->save();
            }catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
            }
	}
	/* To save the billing address as default
	* @param array billingData
	* @param int customerId
	*/
	public function saveBillingAddress($billingData,$customerId){
		    $websiteId  = $this->storeManager->getStore()->getWebsiteId();
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$customerAlreadyAvailable = $objectManager->create('Magento\Customer\Model\Customer')->setWebsiteId($websiteId)->load($customerId);
			if($customerAlreadyAvailable->hasData()){
				$address = $objectManager->create('Magento\Customer\Model\Customer')->getAddressById($customerAlreadyAvailable->getDefaultBilling());
			}else{
				 $address = $this->addressFactory->create();
			}		
            $address->setCustomerId($customerId)
            ->setFirstname($billingData['firstname'])
            ->setLastname($billingData['lastname'])
            ->setCountryId($billingData['country_id'])
            ->setPostcode($billingData['postcode'])
            ->setCity($billingData['city'])
            ->setTelephone($billingData['telephone'])
            ->setCompany($billingData['company'])
            ->setStreet($billingData['street'])
            ->setIsDefaultBilling('1')
            ->setSaveInAddressBook('1');
			$region = $this->getRegionIdByName($billingData['region'],$billingData['country_id']);
			if ($region->getSize()) {
				$address->setRegionId($region->getFirstItem()->getId())
				->setRegion($region->getFirstItem()->getName());
			}else{
				$address->setRegion($billingData['region']);
			}
            try{
                $address->save();
            }catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
            }
	}
	/* To save the shipping address as default
	* @param array shippingData
	* @param int customerId
	*/
	public function saveShippingAddress($shippingData,$customerId){
		    $websiteId  = $this->storeManager->getStore()->getWebsiteId();
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$customerAlreadyAvailable = $objectManager->create('Magento\Customer\Model\Customer')->setWebsiteId($websiteId)->load($customerId);
			if($customerAlreadyAvailable->hasData()){
				$address = $objectManager->create('Magento\Customer\Model\Customer')->getAddressById($customerAlreadyAvailable->getDefaultShipping());
			}else{
				 $address = $this->addressFactory->create();
			}	
            $address->setCustomerId($customerId)
            ->setFirstname($shippingData['firstname'])
            ->setLastname($shippingData['lastname'])
            ->setCountryId($shippingData['country_id'])
            ->setPostcode($shippingData['postcode'])
            ->setCity($shippingData['city'])
            ->setTelephone($shippingData['telephone'])
            ->setCompany($shippingData['company'])
            ->setStreet($shippingData['street'])
            ->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');
			$region = $this->getRegionIdByName($shippingData['region'],$shippingData['country_id']);
			if ($region->getSize()) {
				$address->setRegionId($region->getFirstItem()->getId())
				->setRegion($region->getFirstItem()->getName());
			}else{
				$address->setRegion($shippingData['region']);
			}
            try{
                $address->save();
            }catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
            }
	}
	/*get the state id based on the country and state name.
	* @param string region 
	* @param string countryId 
	*/	
	public function getRegionIdByName($region, $countryId)
    {
        $collection = $this->regionCollectionFactory->create()
            ->addRegionCodeOrNameFilter($region)
            ->addCountryFilter($countryId);
		return $collection;
    }
}
