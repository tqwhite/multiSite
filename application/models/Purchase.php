<?php

class Application_Model_Purchase extends Application_Model_Base
{

	const entityName="Purchase";

	public function __construct(){
		parent::__construct();
	}

	static function validate($inData){


		$errorList=array();

		$datum=$inData['purchaseData']['grandTotal'];
		if (!$datum){
			$errorList[]=array($name, "The grand total must be greater than zero");
		}

		$name='cardName';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Card name number required");
		}

		$name='street';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Street address required");
		}

		$name='city';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "City is required");
		}

		$name='state';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "State code is required");
		}
		else if (strlen($datum)!=2){
			$errorList[]=array($name, "State code is exactly two letters");
		}

		$name='zip';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Zip code is required");
		}

		$name='emailAdr';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Email address is required");
		}

		$name='phoneNumber';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Phone number is required");
		}



		$name='cardNumber';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Credit card number required");
		}
		else if (strlen(preg_replace('/[^\S]/', '', $datum))<15){
			$errorList[]=array($name, "Credit card number is incorrect");
		}

		$name='expMonth';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Month is required");
		}
		else if ($datum<1 || $datum>12){
			$errorList[]=array($name, "Month is wrong");
		}

		$name='expYear';
		$datum=$inData['cardData'][$name];
		if (!$datum){
			$errorList[]=array($name, "Year is required");
		}
		else if ($datum<12){
			$errorList[]=array($name, "Year is wrong");
		}

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

