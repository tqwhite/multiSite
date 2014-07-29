<?php

class SimpleStore_GenerateController extends Q_Controller_Base {
	
	private $useCardProdServer;
	private $simpleStore;
	private $cardProcessorAuth;
	private $confirmationHtml;
	
	public function init() { //this is called by the Zend __construct() method
		$inData    = $this->getRequest()->getPost('data');
		$routeName = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
		
		if ($routeName == 'default' && $inData['serverManagement']['processContentSourceRouteName']) {
			$overrideRouteName = $inData['serverManagement']['processContentSourceRouteName'];
		} else {
			$overrideRouteName = '';
		}
		
		parent::init(array(
			'controllerType' => 'cms',
			'overrideRouteName' => $overrideRouteName
		));
	}
	
	public function indexAction() {
		// action body
	}
	
	private function updateGlobals($config) {
		$this->cardProcessorAuth = $config['simpleStore.ini']['moneris'];
		$this->simpleStore       = $config['simpleStore.ini']['simpleStore'];
		unset($this->simpleStore['moneris']);
		
		if (isset($this->simpleStore['useCardProdServer'])) {
			$this->useCardProdServer = $this->simpleStore['useCardProdServer'];
		} else {
			$this->useCardProdServer = true;
		}
	}
	
	public function containerAction() {
		$contentArray = $this->contentObj->contentArray;
		$this->updateGlobals($contentArray['globalItems']['CONFIG']);
		
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$scheme = 'https://';
		} else {
			$scheme = 'http://';
		}
		
		$this->setVariationLayout('layout');
		$serverComm[] = array(
			"fieldName" => "message",
			"value" => 'hello from the server via javascript'
		);
		
		$storeManagement=array(
			'test'=>'tmp'
		);
		
		$jsControllerList[] = array(
			"domSelector" => ".mainContentContainer",
			"controllerName" => 'widgets_simple_store_main',
			"parameters" => json_encode(array(
				'paymentServerUrl' => $scheme . $_SERVER['HTTP_HOST'] . '/simpleStore/generate/process',
				'processContentSourceRouteName' => Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName()
			))
		);
		
		$serverComm             = $this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);
		$this->view->serverComm = $this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv
		
		$this->view->simpleStore=$this->simpleStore;
		$this->view->contentArray = $contentArray;
		$this->view->codeNav      = $this->getCodeNav(__method__);
	}
	
	public function validateContentStructure($contentArray) {
		//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray) {
			$contentArray = $this->contentArray;
		}
		\Q\Utils::validateProperties(array(
			'validatedEntity' => $contentArray,
			'source' => __file__,
			'propertyList' => array(
				array(
					'name' => 'headBanner.html'
				),
				array(
					'name' => 'productSpecs',
					'assertNotEmptyFlag' => true
				)
			)
		));
		
	}
	
	public function initializeContentDirectory() {
		$headNav = <<<HEADNAV

menu.0.title="About Us"

menu.0.links.0.title='Our Story'
menu.0.links.0.url='#ourStory'

menu.0.links.1.title='Contact Info'
menu.0.links.1.url='#contactInfo'


menu.1.title="Our Partners"

menu.1.links.0.title='The Cambridge Group'
menu.1.links.0.url='http://www.cambridgestrategics.com/'

menu.1.links.1.title='Anoka-Henepin School District'
menu.1.links.1.url='http://www.---.com/'

HEADNAV;
		
		$siteDirectoryUrlList = <<<SITEDIR

;if you do not want a site directory, leave the rest of this file blank
a.0.title="Company List"

a.0.links.0.title='Administrative Solutions'
a.0.links.0.url='http://admin.<!rootDomainSegment!>/sitemap'
a.0.links.0.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

a.0.links.1.title='Document Imaging'
a.0.links.1.url='http://imaging.<!rootDomainSegment!>/sitemap'
a.0.links.1.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

SITEDIR;
		
		$productList = <<<PRODUCTS

prodA.prodcCode="prodA";
prodA.name="Product A";
prodA.price=11.33;
prodA.description="This is an excellent <b>Product A</b> thing."

prodB.prodcCode="prodB";
prodB.name="Product B";
prodB.price=22.33;
prodB.description="This is an excellent <b>Product B</b> thing."


PRODUCTS;
		
		$directories = array(
			'ROOTFILES' => array( //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'contactFooter.html' => "FOOTER: edit contactFooter.html to customize",
				'headBanner.html' => "HEAD BANNER: edit headBanner.html to customize",
				'headNav.ini' => $headNav,
				'siteDirectoryUrlList.ini' => $siteDirectoryUrlList,
				'products.ini' => $productList
				
			),
			'images' => array(
				'README' => 'this is a placeholder so git will initialize this directory. put images in here'
			)
			
		);
		
		$this->_helper->InitPageTypeDirectory($directories);
	}
	
	public function processAction() {
		
		$inData            = $this->getRequest()->getPost('data');	
		$inData['orderId'] = $inData['token'] = md5(json_encode($inData) . time());
		$inData['freeCard']=$this->freeCardNo($inData);
		
		$inData['provisionServer']=$this->chooseProvisionServer($inData);
		
		$contentArray = $this->contentObj->contentArray;
		$this->updateGlobals($contentArray['globalItems']['CONFIG']);
		
		$errorList            = array();
		$provisionResultArray = array();
		$paymentResultArray   = array();
		$messages             = array();
		
		$messages = array();
		
		$errorList = \Application_Model_Purchase::validate($inData);
		
		
		if (count($errorList) == 0) { //incoming data is good, now work on processing
			
			$paymentResultArray               = $this->getPaymentResult($inData);
			$inData['creditCardPanel']['number'] = substr($inData['creditCardPanel']['number'], strlen($inData['creditCardPanel']['number']) - 4, 4);
			
			$paymentResult = $paymentResultArray['paymentProcessResult'];
			$messages      = array_merge($messages, $paymentResultArray['messages']);
			
			$paymentStatus = $paymentResultArray['paymentProcessResult']['responseData']['ResponseCode'];
			if ($paymentStatus == 1) {
				$provisionResultArray = $this->getProvisionResult($inData);
				
				$inData['token']=$provisionResultArray['provisionProcessResult']['data']['tokenValue']; //should always be the same as $inData['orderId']
			
				$messages[]           = array(
					'provisioning',
					$provisionResultArray['provisionProcessResult']['message']
				);
			} else { //payment didn't work, tell user
				$provisionResultArray = array(
					'provisionProcessResult' => array(
						'status' => -1,
						'messages' => 'Not attempted. Payment error.',
						"url" => "",
						'data' => array(
							'tokenValue' => ''
						)
					)
				);
				$messages[]           = array(
					'failure',
					"Payment Failure: " . $paymentResultArray['paymentProcessResult']['responseData']['Message']
				);
			}
			
			$mailSentStatus = $this->processEmail($provisionResultArray, $contentArray, $inData);
			
			
			$this->_helper->json(array(
				'status' => $provisionResultArray['provisionProcessResult']['status'],
				'messages' => array_merge($errorList, $messages),
				'data' => array(
					'token' => $inData['orderId'],
					'provisionResult' => $provisionResultArray['provisionProcessResult'],
					'paymentResult' => $paymentResultArray['paymentProcessResult'],
					'mailSentStatus' => $mailSentStatus
				)
			));
		} else { //payment failed
			$this->_helper->json(array(
				'status' => -1,
				'messages' => $errorList,
				'data' => array()
			));
			
		}
	}
	
	private function getPaymentResult($inData) {
		$outArray  = array();
		$messages  = array();
		$firstFour = substr($inData['creditCardPanel']['number'], 0, 4);
		
		if(isset($inData['usePurchaseOrder'])){
		$usePo=($inData['usePurchaseOrder']);
		$poNumber=$inData['usePurchaseOrder'];
		}
		else{
		$usePo=false;
		$poNumber='';
		}
		
		if (!$usePo && !$inData['freeCard']) {
			$paymentResult                    = Application_Model_Payment::process($inData, $this->cardProcessorAuth, array(
				'debug' => !$this->useCardProdServer,
				'forceDecline' => false
			));
			
			$paymentResult['isFreeCard']      = false;
			$paymentResult['usingProdServer'] = $this->useCardProdServer;
		} 
		else {
			$paymentResult = array(
				'responseData' => array(
					'explanation'=>'Special No-Pay Card Number',
					'FakeCardNumber' => $inData['creditCardPanel']['number'],
					'ReceiptId' => $inData['orderId'],
					'ReferenceNum' => '640000030014779980',
					'ResponseCode' => '001',
					'ISO' => '00',
					'AuthCode' => '749314',
					'TransTime' => '09:54:11',
					'TransDate' => '2012-11-02',
					'TransType' => '00',
					'Complete' => 'true',
					'Message' => 'APPROVED*',
					'TransAmount' => '1.00',
					'CardType' => 'V',
					'TransID' => '619800-0_10',
					'TimedOut' => 'false',
					'Ticket' => 'null'
				),
				'usingProdServer' => $this->useCardProdServer,
				'isFreeCard' => $firstFour||$poNumber
			);
			$messages[]    = array(
				'cardProcess',
				"No charge has been made to a credit card"
			);
		}
		
			if ($usePo){
				$paymentResult['responseData']['purchaseOrderNumber']=$inData['usePurchaseOrder'];
				$paymentResult['responseData']['explanation']='Purchase Order';
			}
		$outArray['paymentProcessResult'] = $paymentResult;
		$outArray['messages']             = $messages;
		
		return $outArray;
	}
	
	private function getProvisionResult($inData) {
		
		$outArray    = array();
		$errorList[] = array();
		$firstFour   = (isset($inData['creditCardPanel']))?substr($inData['creditCardPanel']['number'], 0, 4):'';

		
		
		switch ($inData['provisionServer']) {
			default:
			case 'prod': //production server
				$provisionProcessResult = Application_Model_Provision::process($inData, $this->simpleStore['provision']['productionUrl']);

				
				if ($provisionProcessResult['status'] != 1) {
					$errorList[] = array(
						'provision',
						$provisionProcessResult['message']
					);
		
				}
		$provisionProcessResult['data']=array(); //provision server is currently returning bad JSON.
		$provisionProcessResult['data']['tokenValue']=$inData['token']; //this is always the same anyway
				break;
			case 'demo': //demo server
				$provisionProcessResult = Application_Model_Provision::process($inData, $this->simpleStore['provision']['demoUrl']);

				if ($provisionProcessResult['status'] != 1) {
					$errorList[] = array(
						'provision',
						$provisionProcessResult['message']
					);
				}

		$provisionProcessResult['data']=array(); //provision server is currently returning bad JSON.
		$provisionProcessResult['data']['tokenValue']=$inData['token']; //this is always the same anyway

				break;
			case 'noProvision':
				$provisionProcessResult = array(
					'status' => 1,
					'message' => 'Testing. Generated locally.',
					"url" => "https://trax.tensigma.org/DemoAssessment/",
					'data' => array(
						'tokenValue' => 'Testing. No Token Created'
					)
				);
				break;
		}
		
		//result = '{"status":"1","message":"Successful creation of token entry.","url":"https://trax.tensigma.org/DemoAssessment/","data":"{tokenValue:\'49d8a61c40f8ae8ca9db90f67422f5f7\'}"}';
		$provisionProcessResult['serverUsed']=$inData['provisionServer'];
		$outArray['provisionProcessResult'] = $provisionProcessResult;
		$outArray['errorList']              = $errorList;
		
		return $outArray;
	}
	
	private function processEmail($provisionResultArray, $contentArray, $inData) {
		if ($provisionResultArray['provisionProcessResult']['status'] == 1) {
			$mailSentStatus             = $this->sendMail($contentArray, $inData);
			$mailSentStatus['messages'] = array();
		} else {
			$mailSentStatus = array(
				'status' => null,
				'messages' => array(
					'email',
					'Provisioning failed. No email attempted'
				)
			);
		}
		
		return $mailSentStatus;
	}
	
	private function sendMail($contentArray, $inData) {
		$customerReceiptSetup = $contentArray['email.ini']['customerReceipt'];
		$subForms             = $customerReceiptSetup['subForms'];
		
		
		foreach ($subForms as $label => $data) {
			$inData[$label] = '';
			
			$shoppingCart = \Q\Utils::getDottedPath($inData, $data['pathToData']);
			
			foreach ($shoppingCart as $label2 => $data2) {
				$inData[$label] .= $this->processTemplate($data['template'], $data2);
			}
		}
		// \Q\Utils::dumpCli($contentArray['productSpecs'], 'contentArray productSpecs');
		// \Q\Utils::dumpCli($customerReceiptSetup, 'customerReceiptSetup');
		// \Q\Utils::dumpCli($inData['shoppingCart'], 'inData shoppingCart');
		// \Q\Utils::dumpCli($inData, 'inData');exit;
		
		
		$body = $this->assembleEmailBody($contentArray, $inData, $customerReceiptSetup);
		
		$body = $this->processTemplate($body, $inData);
		
		$this->confirmationHtml = $body;


		$stringHelper = new Q_View_Helper_ProcessTemplateArray();
		$resultObj    = $stringHelper->processTemplateArray(array(
			'sourceData' => array(
				array(
					'placeholder' => 'placeholder'
				)
			),
			'itemTemplate' => $customerReceiptSetup['subject'],
			'referenceData' => $inData
		));
		
		$subject = $resultObj['outString'];// \Q\Utils::processTemplateArray($customerReceiptSetup['subject'], array('placeholder'=>'placeholder'), $inData);

		$toAddress = \Q\Utils::getDottedPath($inData, $customerReceiptSetup['toAddressPath']);

		$toName = \Q\Utils::getDottedPath($inData, $customerReceiptSetup['toNamePath']);
		
		$tr = new Zend_Mail_Transport_Sendmail();
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom($customerReceiptSetup['fromAddress'], $customerReceiptSetup['fromName']);
		Zend_Mail::setDefaultReplyTo($customerReceiptSetup['fromAddress'], $customerReceiptSetup['fromName']);
		
		$mail = new Zend_Mail();
		$mail->setSubject($subject);
		
		$mail->setBodyHtml($body);
		
		$mail->addTo($toAddress, $toName);
		
		if (isset($customerReceiptSetup['ccEmailAddresses']) && gettype($customerReceiptSetup['ccEmailAddresses']) == 'array') {
			foreach ($customerReceiptSetup['ccEmailAddresses'] as $label => $data) {
				$mail->addCc($data);
			}
		}
		
		if (isset($customerReceiptSetup['bccEmailAddresses']) && gettype($customerReceiptSetup['bccEmailAddresses']) == 'array') {
			foreach ($customerReceiptSetup['bccEmailAddresses'] as $label => $data) {
				$mail->addBcc($data);
			}
		}
		
		
		$mail->send($tr);
		
		Zend_Mail::clearDefaultFrom();
		Zend_Mail::clearDefaultReplyTo();
		return $mailStatus = array(
			'status' => 1,
			'toAddress' => $toAddress,
			'toName' => $toName,
			'subject' => $subject,
			'message' => $body
		);
	}
	
	private function processTemplate($template, $inData, $debug = false) {
		
		if ($this->useCardProdServer) {
			$inData['prodServerMessage'] = '';
		} else {
			$inData['prodServerMessage'] = "<div style='font-weight:10pt;color:red;margin-bottom:10px;'>Not a real transaction. Using Test Processing Server</div>";
		}
		
		
		foreach ($inData as $label => $data) {
			if (gettype($data) == 'array')
				foreach ($data as $label2 => $data2) {
					if (gettype($data2) == 'string') {
						if ($debug) {
							echo "$label $label2=$data\n\n";
						}
						$template = preg_replace('/<!+' . $label . '.' . $label2 . '!>/', $data2, $template);
					} else {
						$template = preg_replace('/<!+' . $label . '.' . $label2 . '!>/', 'arrayResult', $template);
					}
				} elseif (gettype($data) == 'string') {
				if ($debug) {
					echo "$label $label2=$data\n\n";
				}
				$template = preg_replace('/<!+' . $label . '!>/', $data, $template);
			}
		}
		if ($debug) {
			exit;
		}
		return $template;
	}
	
	private function freeCardNo($inData) {
		$cardNo=(isset($inData['creditCardPanel']))?$inData['creditCardPanel']['number']:'';
		$prefix = substr($cardNo, 0, 4);
		switch ($prefix) {
			case '8881': //no provision
			case '8882': //provision with test server
			case '8888': //give real product for free
				$status = true;
				break;
			default:
				$status = false;
				break;
		}
		return $status;
	}
	
	private function chooseProvisionServer($inData){
		$cardNo=(isset($inData['creditCardPanel']))?$inData['creditCardPanel']['number']:'';
		$poNo=(isset($inData['purchaseOrderPanel']))?$inData['purchaseOrderPanel']['number']:'';
			$demo=0;
			$noProvision=0;
		
		if (isset($inData['usePurchaseOrder']) && $inData['usePurchaseOrder']){
			$testNumber=$poNo;
		}
		else {
			$testNumber=$cardNo;
		}
		
		str_replace('8881', '', $testNumber, $noProvision);
		str_replace('8882', '', $testNumber, $demo);
		
		$outString='prod';
		
		if ($noProvision){
			$outString='noProvision';
		}
		else if ($demo){
			$outString='demo';
		}
		return $outString;
	}
	
	private function assembleEmailBody($contentArray, $inData, $customerReceiptSetup) {

		$shoppingCart = $inData['shoppingCart'];
		$productSpecs=$contentArray['productSpecs'];
	
		$productConfirmationList = $this->generateConfirmationMessages($productSpecs, $inData);
		
		if (isset($contentArray['catalogDisplayTemplates']['transformations.php'])) {
			$transformations = $contentArray['catalogDisplayTemplates']['transformations.php'];
		} else {
			$transformations = array();
		}
		
		$stringHelper = new Q_View_Helper_ProcessTemplateArray();
		
		if ($this->needShipping($productSpecs, $shoppingCart)){
		
			if (isset($inData['wantShippingAddr']) && $inData['wantShippingAddr']){
				$shippingInfo=$inData['shippingPanel'];
			}
			else{
				$shippingInfo=$inData['creditCardPanel'];
			}
		
			$resultObj    = $stringHelper->processTemplateArray(array(
				'sourceData' => array($shippingInfo),
				'itemTemplate' => $customerReceiptSetup['shippingInfo'],
				'referenceData' => array_merge($inData, $this->simpleStore),
				'transformations' => $transformations,
				'debug'=>false
			));
			$shippingString=$resultObj['outString'];
		}
		else{
			$shippingString='';
		}
		
		
		if (isset($inData['usePurchaseOrder']) && $inData['usePurchaseOrder']){
			$paymentTemplate=$customerReceiptSetup['purchaseOrderInfo'];
			$paymentInfo=$inData['purchaseOrderPanel'];
		}
		else{
			$paymentTemplate=$customerReceiptSetup['creditCardInfo'];
			$paymentInfo=$inData['creditCardPanel'];
		}
		
			$resultObj    = $stringHelper->processTemplateArray(array(
				'sourceData' => array($paymentInfo),
				'itemTemplate' => $paymentTemplate,
				'referenceData' => array_merge($inData, $this->simpleStore),
				'transformations' => $transformations,
				'debug'=>false
			));
			$paymentString=$resultObj['outString'];
		
		
		$resultObj    = $stringHelper->processTemplateArray(array(
			'sourceData' => array(
				array(
					'shippingInfo' => $shippingString,
					'paymentInfo' => $paymentString,
					'productSpecificConfirmationInfo'=>$productConfirmationList
				)
			),
			'itemTemplate' => $customerReceiptSetup['body'],
			'referenceData' => array_merge($inData, $this->simpleStore),
			'transformations' => $transformations,
			'debug'=>false
		));
		
		
		return $resultObj['outString'];
	}
	
	private function generateConfirmationMessages($productSpecs, $inData) {
		
		$stringHelper = new Q_View_Helper_ProcessTemplateArray();
		$sourceData   = array();
		$outString    = '';
		
		$contentArray = $this->contentObj->contentArray;
		$shoppingCart = $inData['shoppingCart'];
		
		if (isset($contentArray['catalogDisplayTemplates']['transformations.php'])) {
			$transformations = $contentArray['catalogDisplayTemplates']['transformations.php'];
		} else {
			$transformations = array();
		}
		
		$confirmationList = $this->consolidateConfirmationMessages($productSpecs, $shoppingCart);
		
		foreach ($confirmationList as $label => $confirmationItem) {
			
			$resultObj = $stringHelper->processTemplateArray(array(
				'sourceData' => array(
					array(
						'temp' => 'test'
					)
				),
				'itemTemplate' => $confirmationItem['template'],
				'referenceData' => array_merge($inData, $this->simpleStore),
				'transformations' => $transformations,
				'debug'=>false
			));
			$outString .= $resultObj['outString'];
			
		}
		return $outString;
	}
	
	private function getConfirmationTemplate($productCatalogInfo) {
		
		$contentArray = $this->contentObj->contentArray;

		if (isset($productCatalogInfo['confirmationMessageTemplateName']) && $productCatalogInfo['confirmationMessageTemplateName']!='') {
			$outString = $contentArray['productConfirmationMessageTemplates'][$productCatalogInfo['confirmationMessageTemplateName']];
		} else {
			$outString = '';
		}
		
		return $outString;
		
	}
	
	private function consolidateConfirmationMessages($productSpecs, $shoppingCart) {
		$outArray = array();
		for ($i = 0, $len = count($shoppingCart); $i < $len; $i++) {
			$element            = $shoppingCart[$i];
			$productCatalogInfo = \Q\Utils::lookupDottedPath($productSpecs, 'prodCode', $element['prodCode']);
			
			
			if (isset($productCatalogInfo['confirmationMessageTemplateName'])  && $productCatalogInfo['confirmationMessageTemplateName']!='') {
				
				$name = $productCatalogInfo['confirmationMessageTemplateName'];
				
				$productConfirmationTemplate = $this->getConfirmationTemplate($productCatalogInfo);
				
				if (!isset($outArray[$name]['cartItems'])) {
					$outArray[$name]['cartItems'] = array();
				}
				if (!isset($outArray[$name]['productCatalogItems'])) {
					$outArray[$name]['cartItems'] = array();
				}
				if (!isset($outArray[$name]['template'])) {
					$outArray[$name]['cartItems'] = array();
				}
				$outArray[$name]['cartItems'][]           = $element;
				$outArray[$name]['productCatalogItems'][] = $productCatalogInfo;
				$outArray[$name]['template']              = $productConfirmationTemplate;
			}
			
			
		}
		
		return $outArray;
	}
	
	private function needShipping($productSpecs, $shoppingCart) {
		foreach ($shoppingCart as $i=>$element) {
			$productCatalogInfo = \Q\Utils::lookupDottedPath($productSpecs, 'prodCode', $element['prodCode']);
			
			if (isset($productCatalogInfo['requiresShipping']) && $productCatalogInfo['requiresShipping']) {

				return true;
			}
		}

		
		return false;
	}
	
} //end of class