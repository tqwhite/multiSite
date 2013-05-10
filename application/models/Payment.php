<?php

class Application_Model_Payment extends Application_Model_Base
{
/*

	merchant number: 295 173 981 883

	tech support phone: 866-696-0488
	eselectplus@moneris.COM

	mastercard 			5454545454545454
	visa 				4242424242424242 or 4005554444444403
	amex		 		373599005095005
	pinless debit		4496270000164824


	ACH transactions you may use the following test bank account details.
							routing #	account number					check number
	FEDERAL RESERVE BANK	011000015	Any number between 5-22 digits	Any number

*/

static function process($inData, $processorAuth, $args="set array('debug'=>true) as second parameter in instantiation"){

$inData['creditCardPanel']['phoneNumber']=$inData['identityPanel']['phoneNumber'];

	return self::moneris($inData['orderId'], $inData['creditCardPanel'], $inData['shoppingCart'], $inData['priceSummary'], $processorAuth, $args);
}

static function moneris($orderId, $cardData, $purchaseData, $priceSummary, $parms, $args="set array('debug'=>true) as second parameter in instantiation"){

	$holdErrorState=error_reporting(); //error_reporting(E_ERROR | E_PARSE);

	error_reporting(E_ERROR | E_PARSE); //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	if (gettype($args)=='array' && $args['debug']===true){
		$production=false; //uses the moneris qa server
	}
	else{
		$production=true; //uses the moneris production server
	}


// 	$cardData=$inData['cardData'];
// 	$purchaseData=$inData['purchaseData'];

	new Q_Model_Payment_Moneris(); //initialize the class library


	/************************ Request Variables **********************************/
	if ($production){
		$store_id=$parms['storeId'];
		$api_token=$parms['apiToken'];
	}
	else{
		$store_id='monusqa002';
		$api_token='qatoken';
	}

	/************************ Transaction Variables ******************************/


	if ($production){
		$amount=number_format($priceSummary['grandTotal'], 2);
		$pan=$cardData['number'];
		$expiry_date=$cardData['expYear'].$cardData['expMonth'];

	  	$amount='1.00';

	}
	else{
		if (gettype($args)=='array' && isset($args['forceDecline']) && $args['forceDecline']){
			$amount='1.36'; //this value forces decline
		}
		else{
			$amount='1.00'; //this is only valid success value on qa server
		}
			$pan='4242424242424242';
			$expiry_date=1506;
	}



	/************************ CustInfo Object **********************************/

	$mpgCustInfo = new mpgCustInfo();


	/********************* Set E-mail and Instructions **************/

	$email =$cardData['emailAdr'];
	$mpgCustInfo->setEmail($email);

	$instructions ="PO#/Memo: ".$cardData['memo'];
	$mpgCustInfo->setInstructions($instructions);

	/********************* Create Billing Array and set it **********/

	$nameBits=explode(' ', $cardData['name']);

	$billing = array( first_name => $nameBits[0],
					  last_name => $nameBits[1],
	//                   company_name => 'Widget Company Inc.',
					  address => $cardData['street'],
					  city => $cardData['city'],
					  province => $cardData['state'],
					  postal_code => $cardData['zip'],
	//                   country => 'Canada',
					  phone_number => $cardData['phoneNumber']);
	//                   fax => '416-555-5555',
	//                   tax1 => '123.45',
	//                   tax2 => '12.34',
	//                   tax3 => '15.45',
	//                   shipping_cost => '456.23');


	$mpgCustInfo->setBilling($billing);

	/********************* Create Shipping Array and set it **********/

	// $shipping = array( first_name => 'Joe',
	//                   last_name => 'Thompson',
	//                   company_name => 'Widget Company Inc.',
	//                   address => '111 Bolts Ave.',
	//                   city => 'Toronto',
	//                   province => 'Ontario',
	//                   postal_code => 'M8T 1T8',
	//                   country => 'Canada',
	//                   phone_number => '416-555-5555',
	//                   fax => '416-555-5555',
	//                   tax1 => '123.45',
	//                   tax2 => '12.34',
	//                   tax3 => '15.45',
	//                   shipping_cost => '456.23');
	//
	// $mpgCustInfo->setShipping($shipping);


	/********************* Create Item Arraya and set them **********/

	// $item1 = array (name=>'item 1 name',
	//                 quantity=>'53',
	//                 product_code=>'item 1 product code',
	//                 extended_amount=>'1.00');
	//
	// $mpgCustInfo->setItems($item1);
	//
	//
	// $item2 = array(name=>'item 2 name',
	//                 quantity=>'53',
	//                 product_code=>'item 2 product code',
	//                 extended_amount=>'1.00');
	//
	// $mpgCustInfo->setItems($item2);


	/************************ Transaction Array **********************************/

	$txnArray=array(type=>'us_purchase',
			 order_id=>$orderId,
			 cust_id=>'cust',
			 amount=>$amount,
			 pan=>$pan,
			 expdate=>$expiry_date,
			 crypt_type=>'7',
	//        commcard_invoice=>'kjhgkjgk',
	//          commcard_tax_amount=>'0.15'
			   );


	/************************ Transaction Object *******************************/

	$mpgTxn = new mpgTransaction($txnArray);

	/************************ Set CustInfo Object *****************************/

	$mpgTxn->setCustInfo($mpgCustInfo);

	/************************ Request Object **********************************/

	$mpgRequest = new mpgRequest($mpgTxn);

	/************************ mpgHttpsPost Object ******************************/



	if ($production){
		$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);
	}
	else{
		$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest, array('host'=>'esplusqa.moneris.com'));

	}

	/************************ Response Object **********************************/

	$mpgResponse=$mpgHttpPost->getMpgResponse();


	if (false && !$production){ //when you want to see this, change false to true
		\Q\Utils::dumpCli($mpgHttpPost->getDebug(), 'Moneris debug info');
	}

	$outArray=array(
	'responseData'=>$mpgResponse->getMpgResponseData(),
	'usingProdServer'=>$production

	);

	error_reporting($holdErrorState);
	return $outArray;
}

}

