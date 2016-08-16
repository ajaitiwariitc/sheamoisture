<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use Magento\Framework\App\Bootstrap;
require dirname(__FILE__) . '/../app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$customer = $obj->get('Sundial\CustomerImport\Cron\ImportCustomer');
echo $customer->execute();


