<?php
ini_set("memory_limit","2048M");
//(20151029_114245 is equivalent to date and time => Ymd_His )
require_once ("../../inc/magmi_defs.php");
require_once ("../inc/magmi_datapump.php");
  
/** Define a logger class that will receive all magmi logs **/
class TestLogger
{
 	/**
 	 * logging methos
 	 * @param string $data : log content
 	 * @param string $type : log type
 	 */
 	public function log($data,$type)
 	{
 		echo "$type:$data\n";
 	}
}

$dp=Magmi_DataPumpFactory::getDataPumpInstance("productimport");
$dp->beginImportSession("default","create",new TestLogger()); 
 
/**
 *
 *
 */
 
function getProductDataFromAPI(){
	/*
	$fileName = '1011_product.json';
	$fileName = '1363_product.json';
	$fileName = '1569_product.json';
	$fileName = '1773_product.json';
	*/
	$fileName = '1011_product.json';
	//$ServiceURL = 'http://127.0.0.1/'.$fileName;
        $ServiceURL = 'http://dev.i3lsundial.com/'.$fileName;
	
	if(isset($ServiceURL) && $ServiceURL != ''){
		$headers = array(
			'Accept: application/json',
			'Content-Type:  application/json',
		);

		$curl = curl_init($ServiceURL);
		curl_setopt($curl, CURLOPT_URL, $ServiceURL);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_COOKIEJAR, $cookiefile);
		//curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiefile);
		
		$JSONResponse = curl_exec($curl);
		$StatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		if( $JSONResponse == false ) {
			echo 'Curl error: ' . curl_error($curl);exit;
		} else {
			if($StatusCode == 200){
				$data = json_decode($JSONResponse,true);
			}	
		}
	} else {
		echo 'Error: URL is empty.' ;
	}
	
	return $data;
	
} 

/**
 * @purpose: makeMagentoFriendlyURL method is used to convert MagentoFriendly URL.
 * @param string $urlKey : url_key
 * @return string : $magentoFriendlyURL   
 * @author Ajai Tiwari
 **/
function makeMagentoFriendlyURL ($urlKey){
	$magentoFriendlyURL = '';
	if(isset($urlKey) && $urlKey != '') {
		$aReplace = array('(', ')','|','®','!','%','.',':',';','+','"','&',',','™','*',"'",'?','#','<','>','/','\\');
		$value = str_replace($aReplace, '', $urlKey);
		
		$value = trim($value);
		//replace more than 1 space with single space
		$value = preg_replace("/ {2,}/", " ", $value);
		
		$value = str_replace(' ', '-', $value);
		//$magentoFriendlyURL = strtolower($value);
		$value = str_replace('I','ı',$value);
		$magentoFriendlyURL = mb_strtolower($value, 'UTF-8');
	}
	return $magentoFriendlyURL;
}

 
 
 
function getCategoryMappedData($categoryList){
	// 3dCart customerGroupId => Magento customerGroupId
	//$categoryList
	//$mappedCustomerGroupId = 2;
	//print_r($categoryList);exit;
	$mappedCategories = '';
	$categoryMappArray = array(
		'1568' =>'[Default Category]||Bath & Body||Bar Soap',
		'1695' =>'[Default Category]||Bath & Body||Body Polish',
		'1550' =>'[Default Category]||Bath & Body||Body Wash',
		'1624' =>'[Default Category]||Bath & Body||Bubble Bath',
		'1498' =>'[Default Category]||Bath & Body||Scrub',
		'1530' =>'[Default Category]||Bath & Body||Soak',
		'1606' =>'[Default Category]||Bath & Body||Body Butter',
		'1621' =>'[Default Category]||Bath & Body||Hand Cream',
		'1853' =>'[Default Category]||Bath & Body||Hand Soap',
		'1577' =>'[Default Category]||Bath & Body||Infused Butter',
		'1499' =>'[Default Category]||Bath & Body||Lotion',
		'1492' =>'[Default Category]||Bath & Body||Oil',
		'1856' =>'[Default Category]||Bath & Body||Oil',
		'1595' =>'[Default Category]||Bath & Body||Bar Soap',
		'1565' =>'[Default Category]||Bath & Body||Body Wash',
		'1617' =>'[Default Category]||Bath & Body||Lotion',
		'1661' =>'[Default Category]||Bath & Body||Lotion',
		'1641' =>'[Default Category]||Bath & Body||Bar Soap',
		'1665' =>'[Default Category]||Bath & Body||Body Butter',
		'1789' =>'[Default Category]||Bath & Body||Lotion',
		'1646' =>'[Default Category]||Bath & Body||Oil',
		'1630' =>'[Default Category]||Bath & Body||Lotion',
		'1788' =>'[Default Category]||Bath & Body||Ointment',
		'1664' =>'[Default Category]||Bath & Body||Scrub',
		'1592' =>'[Default Category]||Shave||Aftershave',
		'1600' =>'[Default Category]||Shave||Body Wax',
		'1786' =>'[Default Category]||Shave||Shaving Créme',
		'1616' =>'[Default Category]||Shave||Shave',
		'1643' =>'[Default Category]||Shave||Brush',
		'1615' =>'[Default Category]||Shave||Pre-Shave Oil',
		'1610' =>'[Default Category]||Shave||Shaving Créme',
		'1713' =>'[Default Category]||Cosmetics||Cheek',
		'1685' =>'[Default Category]||Cosmetics||Complexion',
		'1681' =>'[Default Category]||Cosmetics||Eye',
		'1683' =>'[Default Category]||Cosmetics||Lip',
		'1718' =>'[Default Category]||Cosmetics||Accessories',
		'1800' =>'[Default Category]||Cosmetics||Better for You Beauty',
		'1553' =>'[Default Category]||Face||Bar Soap',	
		'1557' =>'[Default Category]||Face||Cream',
		'1533' =>'[Default Category]||Face||Mask',
		'1485' =>'[Default Category]||Face||Serum',
		'1540' =>'[Default Category]||Face||Toner',
		'1552' =>'[Default Category]||Face||Wash & Scrub',
		'1625' =>'[Default Category]||Face||Cream',
		'1508' =>'[Default Category]||Hair||Color',
		'1506' =>'[Default Category]||Hair||Conditioner',
		'1542' =>'[Default Category]||Hair||Co-Wash',
		'1527' =>'[Default Category]||Hair||Detangler',
		'1613' =>'[Default Category]||Hair||Kit',
		'1705' =>'[Default Category]||Hair||Masque',
		'1504' =>'[Default Category]||Hair||Styler',
		'1512' =>'[Default Category]||Hair||Treatment',
		'1603' =>'[Default Category]||Hair||Shampoo',
		'1571' =>'[Default Category]||Hair||Conditioner',
		'1573' =>'[Default Category]||Hair||Shampoo',
		'1522' =>'[Default Category]||Hair||Styler',
		'1652' =>'[Default Category]||Hair||Conditioner',
		'1649' =>'[Default Category]||Hair||Detangler',
		'1654' =>'[Default Category]||Hair||Treatment',
		'1633' =>'[Default Category]||Hair||Shampoo',
		'1173' =>'[Default Category]||Gifts',
		'1656' =>'[Default Category]||Specials'
	);	
	foreach($categoryList as $itemC) {
		$cId = $itemC['CategoryID'];
		if (array_key_exists($itemC['CategoryID'],$categoryMappArray)) {
			$mappedCategories[]= $categoryMappArray["$cId"];
		} else {
			//$mappedCustomerGroupId = 1;
		}
	}	
	//$mappedCategories = implode(";;", $mappedCategories);
	if(count($mappedCategories) > 1){
		
		$mappedCategoriesData = implode(";;", $mappedCategories);
	} else {
		
		if(count($mappedCategories) > 0){
			//echo "<pre>-----------".count($mappedCategories);
			//
			$mappedCategoriesData = $mappedCategories[0];
		} else {
			
		}
		
	}
	
	//$mappedCategories = '[Default Category]||Gear||Bags;;[Default Category]||Men||Tops||Jackets';
	return $mappedCategoriesData;;
}	 
 
//echo '<pre>';
$productArray2 = array();
function getProductsMappedData(){
	$productsData = getProductDataFromAPI();
	$i = 0;
	foreach($productsData as $item) {
		
		//MainImageFile
		//AdditionalImageFile2
		//AdditionalImageFile3
		// Store configuration
		$productArray[$i]["store"] = 'admin';
		$productArray[$i]["type"] = 'simple';
		
		// Status & Visibility
		$productArray[$i]["status"] = '1';
		if($item["Hide"] === TRUE || $item["NotForSale"] === TRUE ) {
			$productArray[$i]["visibility"] = 'Not Visible Individually';
		} else if($item["NonSearchable"] === TRUE ){
			$productArray[$i]["visibility"] = 'Catalog';
		} else if($item["Hide"] === False && $item["NotForSale"] === False && $item["NonSearchable"] === False  ) {
			$productArray[$i]["visibility"] = 'Catalog, Search'; 
		} else {
			$productArray[$i]["visibility"] = 'Catalog, Search'; 
		}
		
		// Other Attributes
		$productArray[$i]["catalog_id"] = $item["SKUInfo"]["CatalogID"];
		$productArray[$i]["sku"] = $item["SKUInfo"]["SKU"];
		$productArray[$i]["name"] = $item["SKUInfo"]["Name"];
		$productArray[$i]["cost"] = $item["SKUInfo"]["Cost"];
		$productArray[$i]["price"] = $item["SKUInfo"]["Price"];
		$productArray[$i]["special_price"] = $item["SKUInfo"]["SalePrice"];
		$productArray[$i]["msrp"] = $item["SKUInfo"]["RetailPrice"];
		$productArray[$i]["sale"] = $item["SKUInfo"]["OnSale"];
				
		$productArray[$i]["qty"] = $item["SKUInfo"]["Stock"];
		$productArray[$i]["is_in_stock"] = '1';
		
		$productArray[$i]["short_description"] = $item["ShortDescription"];
		$productArray[$i]["description"] = $item["Description"];
		$productArray[$i]["meta_keyword"] = $item["Keywords"];
		//$productArray[$i]["meta_description"] = $item["Keywords"];
		//$productArray[$i]["meta_title"] = $item["Keywords"];
		$productArray[$i]["ea_upc"] = $item["GTIN"];
		
		
		// Image Section	
		$productArray2[$i]["media_gallery"] = 'wget https://shea-moisture.scdn4.secure.raxcdn.com/'.$item["MainImageFile"];
		$productArray2[$i]["AdditionalImageFile2"] = 'wget https://shea-moisture.scdn4.secure.raxcdn.com/'.$item["AdditionalImageFile2"];
		$productArray2[$i]["AdditionalImageFile3"] = 'wget https://shea-moisture.scdn4.secure.raxcdn.com/'.$item["AdditionalImageFile3"];
		
		
		if(isset($item["MainImageFile"]) && $item["MainImageFile"] != ''){
			$tmpImg1 = '';$tmpImg2 ='';$tmpImg3 ='';
			$tmpImg1 = explode('assets/images/products/', $item["MainImageFile"]);
			if(count($tmpImg1) > 1){
				$image = '+'.$tmpImg1["1"];
				$image1 = $tmpImg1["1"];
			} else {
				//$image1 = 'xxxxx.jpg';
			}
			
			if(isset($item["AdditionalImageFile2"]) && $item["AdditionalImageFile2"] != ''){
				$tmpImg2 = explode('assets/images/products/', $item["AdditionalImageFile2"]);
				if(count($tmpImg2) > 1){
					$image .= ';'.'+'.$tmpImg2["1"];
				}	
				
			}
	
			if(isset($item["AdditionalImageFile3"]) && $item["AdditionalImageFile3"] != ''){
				$tmpImg3 = explode('assets/images/products/', $item["AdditionalImageFile3"]);
				if(count($tmpImg3) > 1){
					$image .= ';'.'+'.$tmpImg3["1"];
				}
			}
		}
		$productArray[$i]["media_gallery"] = $image;
		$productArray[$i]["image"] = $image1;
		$productArray[$i]["thumbnail"] = $image1;
		$productArray[$i]["small_image"] = $image1;
		
		if(count($item["DistributorList"]) > 0){
			$productArray[$i]["distributor"] = $item["DistributorList"][0]["DistributorName"];
		}
		
		
		
		// Category Section
		$productArray[$i]["categories"] = getCategoryMappedData($item["CategoryList"]);
		
		if(!$item["NonTaxable"]) {
			$productArray[$i]["tax_class_id"] = 'Taxable Goods';
		} else {
			$productArray[$i]["tax_class_id"] = 'None';
			
		}
		$urlKeyByProductName = $item["SKUInfo"]["Name"];
		$urlKey = makeMagentoFriendlyURL ($urlKeyByProductName);
		
		$productArray[$i]["url_key"] = $urlKey;
		$productArray[$i]["height"] = $item["Height"];
		//$productArray[$i]["date_created"] = $item["DateCreated"];
		$productArray[$i]["height"] = $item["Height"];
		$productArray[$i]["width"] = $item["Width"];
		$productArray[$i]["depth"] = $item["Depth"];
		$productArray[$i]["weight"] = $item["Weight"];
		
		$productArray[$i]["attribute_set"] = 'cosmetics';
		$productArray[$i]["product_volume"] = $item["ExtraField1"];
		$productArray[$i]["product_type"] = $item["ExtraField3"];
		$productArray[$i]["collection_sheamoisture"] = $item["ExtraField6"];
		$productArray[$i]["key_ingredients"] = $item["ExtraField7"];
		$productArray[$i]["product_usage_instructions"] = $item["ExtraField8"];
		$productArray[$i]["ea_stockcode"] = $item["ExtraField9"];
		$productArray[$i]["product_ingredients"] = $item["ExtraField10"];
		
		//print_r($productArray);exit;
		$i++;
	}
	
	/*
	foreach($productArray2 as $item)
	{
		echo $item["media_gallery"]; echo '<br>';
		echo $item["AdditionalImageFile2"]; echo '------XX<br>';
		echo $item["AdditionalImageFile3"]; echo '------YY<br>';
		
		
	}	*/
	
	//Print_r($productArray2);exit;
	return $productArray;
}	


$productsMappedData = getProductsMappedData();

//echo "<pre>";
// import stock data
$d = 0;
foreach($productsMappedData as $item)
{			
	unset($item['id']);
	//Import product
	//print_r($item);
	$dp->ingest($item);
		
}

/* end import session, will run post import plugins */
$dp->endImportSession();
