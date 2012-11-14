<?php

class Application_Model_Provision extends Application_Model_Base
{


static function process($inData){

$outArray=array();
$outArray['productList']=$inData['purchaseData']['productList'];
$outArray['transactionInfo']=array(
		'purchaserName'=>$inData['purchaseData']['cardData']['name'],
		'purchaserPhone'=>$inData['purchaseData']['cardData']['phoneNumber'],
		'purchaserEmail'=>$inData['purchaseData']['cardData']['emailAdr'],
		'totalPaid'=>$inData['purchaseData']['grandTotal'],
		'taxPaid'=>$inData['purchaseData']['tax']
);

$productJson=json_encode($outArray['productList']);
$transactionJson=json_encode($outArray['transactionInfo']);

//	$ch =curl_init("http://store.demo.tensigma.org/test/index.php");
	$simpleStore=Zend_Registry::get('simpleStore');
	$url=$simpleStore['provision']['url'];
	$ch =curl_init($url);

//	$ch =curl_init("http://store.demo.qschooltech.com/test/index.php");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('transactionInfo'=>$transactionJson, 'productList'=>$productJson, 'token'=>$inData['orderId']));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	return json_decode($result, true);
}

}

