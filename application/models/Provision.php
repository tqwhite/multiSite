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

$outBoundPayload=array('transactionInfo'=>$transactionJson, 'productList'=>$productJson, 'token'=>$inData['token']);

	$ch =curl_init($url);
	
//	curl_setopt($ch, CURLOPT_PORT, 8080);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $outBoundPayload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	

// \Q\Utils::dumpWeb($url, "url");
// \Q\Utils::dumpWeb($outBoundPayload, "outBoundPayload");
// \Q\Utils::dumpWeb($inData['shoppingCart'], "inData['shoppingCart']");
// \Q\Utils::dumpWeb($transactionInfo, "transactionInfo");
// \Q\Utils::dumpWeb(json_decode($result, true), "json_decode(result, true)");

	return json_decode($result, true);
}

}

