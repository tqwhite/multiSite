<?php

class Application_Model_Purchase extends Application_Model_Base
{

	const entityName="Purchase";

	public function __construct(){
		parent::__construct();
	}

	static function validate($inData){
	$name=''; $datum=''; $sectionName='';
		$errorList=array();

	if (!isset($inData['usePurchaseOrder']) || !$inData['usePurchaseOrder']){
	$sectionName='creditCardPanel';
		$name='name';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Card Name is required.");}

		$name='street';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Card Street is required.");}

		$name='city';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Card City is required.");}

		$name='state';
		$datum=$inData[$sectionName][$name];
		if (strlen($datum)!=2)
		{$errorList[]=array($name, "Card State code must be two characters.");}

		$name='zip';
		$datum=$inData[$sectionName][$name];
		if (!$datum || $datum=='00000')
		{$errorList[]=array($name, "Card Zip Code is required.");}
			else if (strlen($datum)!=5)
			{$errorList[]=array($name, "Card Zip must be five digits (00000).");}


		$name='number';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Card Number is required.");}
			else if (strlen(preg_replace('/[^\S]/', '', $datum))<15){
				$errorList[]=array($name, "Credit card number is incorrect");
			}

		$name='expMonth';
		$datum=$inData[$sectionName][$name];
		if (!$datum || $datum=='MM')
		{$errorList[]=array($name, "Card Expiration Date is required.");}

		$name='expYear';
		$datum=$inData[$sectionName][$name];
		if (!$datum || $datum=='YY')
		{$errorList[]=array($name, "Card Expiration Year is required.");}
	}
	else{
	$sectionName='purchaseOrderPanel';

		$name='number';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Purchase Order Number is required is required.");}
		
		$name='name';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Authorized Person Name is required.");}

		$name='phoneNumber';
		$datum=$inData[$sectionName][$name];
		if (!$datum || $datum=='000-000-0000')
		{$errorList[]=array($name, "Phone Number is required.");}
			else if (strlen($datum)!=12)
			{$errorList[]=array($name, "Phone Number must be 000-000-0000.");}

		$name='street';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "PO Street is required.");}

		$name='city';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "PO City is required.");}

		$name='state';
		$datum=$inData[$sectionName][$name];
		if (strlen($datum)!=2)
		{$errorList[]=array($name, "PO State code must be two characters.");}

		$name='zip';
		$datum=$inData[$sectionName][$name];
		if (!$datum || $datum=='00000')
		{$errorList[]=array($name, "PO Zip Code is required.");}
			else if (strlen($datum)!=5)
			{$errorList[]=array($name, "Zip must be five digits (00000).");}

			
	}
//start shipping


	if (isset($inData['wantShippingAddr']) && $inData['wantShippingAddr']){
	$sectionName='shippingPanel';
		$name='name';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Shipping Name is required.");}

		$name='street';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Shipping Street is required.");}

		$name='city';
		$datum=$inData[$sectionName][$name];
		if (!$datum)
		{$errorList[]=array($name, "Shipping City is required.");}

		$name='state';
		$datum=$inData[$sectionName][$name];
		if (strlen($datum)!=2)
		{$errorList[]=array($name, "Shipping State code must be two characters.");}

		$name='zip';
		$datum=$inData[$sectionName][$name];
		if (!$datum || $datum=='00000')
		{$errorList[]=array($name, "Shipping Zip Code is required.");}
			else if (strlen($datum)!=5)
			{$errorList[]=array($name, "Zip must be five digits (00000).");}

	}
	
//start identity

	$sectionName='identityPanel';
	$name='name';
	$datum=$inData[$sectionName][$name];
	if (!$datum)
	{$errorList[]=array($name, "Name is required.");}

	$name='phoneNumber';
	$datum=$inData[$sectionName][$name];
	if (!$datum || $datum=='000-000-0000')
	{$errorList[]=array($name, "Phone Number is required.");}
		else if (strlen($datum)!=12)
		{$errorList[]=array($name, "Phone Number must be 000-000-0000.");}

	$name='emailAdr';
	$datum=$inData[$sectionName][$name];
	if (!$datum)
	{$errorList[]=array($name, "Email Address is required.");}
	
	//end of identity

	return $errorList;
}

	static function provision(){
		//does post to TRAX server/Wilbert
	}

	static function notifyCustomerService(){
		//sends email to customer service
	}

	static function notifyCustomer(){
		//sends email to customer containing receipt, etc
	}
}

