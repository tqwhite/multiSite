<?php

class Application_Model_Provision extends Application_Model_Base
{


static function process($inData, $url){

$transactionInfo=array(
			'purchaserName'=>$inData['purchaseData']['cardData']['name'],
			'purchaserPhone'=>$inData['purchaseData']['cardData']['phoneNumber'],
			'purchaserEmail'=>$inData['purchaseData']['cardData']['emailAdr'],
			'totalPaid'=>$inData['purchaseData']['grandTotal'],
			'taxPaid'=>$inData['purchaseData']['tax']
	);

$productJson=json_encode($inData['purchaseData']['shoppingCart']);
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

