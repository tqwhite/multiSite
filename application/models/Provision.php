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

$shoppingCart=self::cleanCart($inData['shoppingCart']);
$productJson=json_encode($shoppingCart);
$transactionJson=json_encode($transactionInfo);

$outBoundPayload=array('transactionInfo'=>$transactionJson, 'productList'=>$productJson, 'token'=>$inData['token']);

	$ch =curl_init($url);
	
//	curl_setopt($ch, CURLOPT_PORT, 8080);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $outBoundPayload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	

// \Q\Utils::dumpCli($url, "url");
// \Q\Utils::dumpCli($outBoundPayload, "outBoundPayload");
// \Q\Utils::dumpCli($shoppingCart, "shoppingCart");
// \Q\Utils::dumpCli($transactionInfo, "transactionInfo");
// \Q\Utils::dumpCli(json_decode($result, true), "json_decode(result, true)");
// \Q\Utils::dumpCli($result, "result");

	return json_decode($result, true);
}

private function cleanCart($shoppingCart){
$outArray=array();
		foreach ($shoppingCart as $label=>$data){
			$outItem=array();
			$name='prodCode'; $outItem[$name]=$data[$name];
			$name='quantity'; $outItem[$name]=$data[$name];
			$outArray[]=$outItem;
		}
	return $outArray;

}

}

