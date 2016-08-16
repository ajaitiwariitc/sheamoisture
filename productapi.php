<?php
//namespace RestDoc\HttpClient;
class RestClient 
{
	
	private $url;
	private $secureUrl;
	private $privateKey;
	private $token;
	private $httpHeaders;
	private $contentType;
	
	function __construct($version, $secureUrl, $privateKey, $token, $contentType = 'json') 
	{
		$this->url         = 'https://apirest.3dcart.com/3dCartWebAPI/v' . $version . '/';
		$this->secureUrl   = $secureUrl;
		$this->privateKey  = $privateKey;
		$this->token       = $token;
		$this->contentType = $contentType;
		$this->httpHeaders = array(
				'SecureUrl: ' . $this->secureUrl,
				'PrivateKey: ' . $this->privateKey,
				'Token: ' . $this->token,
		);
		if ($this->contentType === 'xml') {
			array_push($this->httpHeaders, 'Content-Type: application/xml; charset=utf-8');
			array_push($this->httpHeaders, 'Accept: application/xml');
		} else {
			array_push($this->httpHeaders, 'Content-Type: application/json; charset=utf-8');
			array_push($this->httpHeaders, 'Accept: application/json');
		}
	}
	
	public function getProducts($productId = null, $params = null)
	{
		$this->url .= 'Products';
		if ($productId !== null) {
			$this->url .= '/' . $productId;
		}
		if (count(array_filter($params)) > 0) {
			$this->url .= '?' . http_build_query($params);
		}
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpHeaders);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// [ ... addtional cURL options as needed ... ]
		$response = curl_exec($ch);
		//Debug::dump($response);
		if ($response === false) {
			$response = curl_error($ch);
		}
		curl_close($ch);
		return $response;
	}
}
// elsewhere...
$version = 1;                                 // API version
$secureUrl = 'https://sheamoisture.com';       // Secure URL is set in Settings->General->StoreSettings
$privateKey = '4e354d8f18a089494380aba64dcef452'; // Private key is obtained when registering your app at http://devportal.3dcart.com
$token = '19f0720278ba9b591702148c1bdd3706';      // The token is generated when a customer authorizes your app
$contentType = 'json';                            // can be set to json or xml (default = json)
$id = null; // not necessary if filtering by parameters or within a customer group
$count = 0;
$offset = 601;
//more:

$productId = null; // set to null or exclude to retrieve a list of all customers
$params = array(
        'limit'     => 200,  // limit the number of records to return (default = 10, max = 100)
        'offset'    => $offset, // the starting position within the result set
        'countonly' => $count,	// boolean to return only the number of records in the result set
);
$client  = new RestClient($version, $secureUrl, $privateKey, $token, $contentType);
$response = $client->getProducts($productId, $params);
echo "<pre>";
//print_r(json_decode($response,true));
$products = json_decode($response,true);

	$productCatalogId = $products["0"]["SKUInfo"]["CatalogID"];
	$fp = fopen($productCatalogId.'_product.json', 'w');
	fwrite($fp, $response);
	fclose($fp);

?>
