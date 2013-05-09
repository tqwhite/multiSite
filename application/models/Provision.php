<?php

class Application_Model_Provision extends Application_Model_Base
{


static function process($inData, $url){

$transactionInfo=array(
			'purchaserName'=>$inData['identityPanel']['name'],
			'purchaserPhone'=>$inData['identityPanel']['phoneNumber'],
			'purchaserEmail'=>$inData['identityPanel']['emailAdr'],
			'totalPaid'=>$inData['priceSummary']['grandTotal'],
			'taxPaid'=>$inData['priceSummary']['tax']
	);

$productJson=json_encode($inData['shoppingCart']);
$transactionJson=json_encode($transactionInfo);

//	$ch =curl_init("http://store.demo.tensigma.org/test/index.php");
	$ch =curl_init($url);

//	curl_setopt($ch, CURLOPT_PORT, 8080);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('transactionInfo'=>$transactionJson, 'productList'=>$productJson, 'token'=>$inData['orderId']));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	return json_decode($result, true);
}

}

