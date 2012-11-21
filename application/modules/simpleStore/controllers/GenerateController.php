<?php

class SimpleStore_GenerateController extends Q_Controller_Base
{

	private $useCardProdServer;
	private $simpleStore;
	private $cardProcessorAuth;

    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'cms'));
    }

    public function indexAction()
    {
        // action body
    }

    private function updateGlobals($config){
    	$this->cardProcessorAuth=$config['simpleStore.ini']['moneris'];
		$this->simpleStore=$config['simpleStore.ini']['simpleStore'];

		$this->simpleStore=['url']=$this->simpleStore['productionUrl'];
    }

    public function containerAction()
    {
		$contentArray=$this->contentObj->contentArray;
		$this->updateGlobals($contentArray['globalItems']['CONFIG']);

    	$redemptionUrl=$this->simpleStore['redemption']['url'];

    	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){ $scheme='https://';}
    	else{ $scheme='http://';}

       $this->setVariationLayout('layout');
		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

				$jsControllerList[]=array(
				"domSelector"=>".mainContentContainer",
				"controllerName"=>'widgets_simple_store_main',
				"parameters"=>json_encode(
					array(
						'paymentServerUrl'=>$scheme.$_SERVER['HTTP_HOST'].'/simpleStore/generate/process',
						'redemptionUrl'=>$redemptionUrl
					)
				)
			);

     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);
     	$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv


		$this->view->contentArray=$contentArray;
		$this->view->codeNav=$this->getCodeNav(__method__);
    }

	public function validateContentStructure($contentArray){
		//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}
		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'headBanner.html'),
				array('name'=>'products.ini')
			)));

	}

	public function initializeContentDirectory(){
	$headNav=<<<HEADNAV

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

$siteDirectoryUrlList=<<<SITEDIR

;if you do not want a site directory, leave the rest of this file blank
a.0.title="Company List"

a.0.links.0.title='Administrative Solutions'
a.0.links.0.url='http://admin.cmerdc.local/sitemap'
a.0.links.0.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

a.0.links.1.title='Document Imaging'
a.0.links.1.url='http://imaging.cmerdc.local/sitemap'
a.0.links.1.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

SITEDIR;

$productList=<<<PRODUCTS

prodA.prodcCode="prodA";
prodA.name="Product A";
prodA.price=11.33;
prodA.description="This is an excellent <b>Product A</b> thing."

prodB.prodcCode="prodB";
prodB.name="Product B";
prodB.price=22.33;
prodB.description="This is an excellent <b>Product B</b> thing."


PRODUCTS;

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'contactFooter.html'=>"FOOTER: edit contactFooter.html to customize",
				'headBanner.html'=>"HEAD BANNER: edit headBanner.html to customize",
				'headNav.ini'=>$headNav,
				'pageTitle.txt'=>"TITLE: edit pageTitle.txt to customize",
				'siteDirectoryUrlList.ini'=>$siteDirectoryUrlList,
				'products.ini'=>$productList

			),
			'images'=>array(
				'README'=>'this is a placeholder so git will initialize this directory. put images in here'
			)

		);

		$this->_helper->InitPageTypeDirectory($directories);
	}



    public function processAction()
    {
        //HACKERY: This action is *not* mapped by the multiSite routing system. It uses the
        //Zend default controller. Consequently, contentArray comes from
        //a directory called 'default'.
        //That means that many of the parameters contained in _default need to be REPEATED in default
        //Also, I don't know why it's not being rejected by validateContentStructure() but
        //I need to get this done. Maybe I'll come back to it later. tqii
        //arrgggh!!

		$contentArray=$this->contentObj->contentArray;
		$this->updateGlobals($contentArray['globalItems']['CONFIG']);

    	if (isset($this->simpleStore['useCardProdServer'])){
	    	$this->useCardProdServer=$this->simpleStore['useCardProdServer'];
		}
		else{
			$this->useCardProdServer=true;
		}

		$errorList=array();

		$inData=$this->getRequest()->getPost('data');

		$provisionResult=array();
		$paymentResult=array();

		$status=-1; //change it to good (1) if something good happens
		$messages=array(array('default', "Nothing has gone wrong"));

		$firstFour=substr($inData['cardData']['cardNumber'], 0, 4);

		$errorList=\Application_Model_Purchase::validate($inData);
		if (count($errorList)==0){

			//incoming data is good, now work on processing
			$inData['orderId']=$inData['token']=md5(json_encode($inData).time());


		if (!$this->freeCardNo($inData['cardData']['cardNumber'])){
					$paymentResult=Application_Model_Payment::process($inData, $this->cardProcessorAuth, array('debug'=>!$this->useCardProdServer, 'forceDecline'=>false));
					$paymentResult['isFreeCard']=false;
					$paymentResult['usingProdServer']=$this->useCardProdServer;
		}
		else{
			$paymentResult=array(
				'responseData' => array(
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
				'isFreeCard'=> $firstFour
			);
			$messages=array(array('cardProcess', "No charge has been made to a credit card"));
}

			$inData['cardData']['cardNumber']=substr($inData['cardData']['cardNumber'], strlen($inData['cardData']['cardNumber'])-4, 4);;

			if ($paymentResult['responseData']['ResponseCode']==1){
				if ($firstFour!='8881'){
					$provisionResult=Application_Model_Provision::process($inData, $this->simpleStore['provision']['url']);
						$status=1;
					if ($provisionResult['status']!=1){
						$errorList[]=array('provision', $provisionResult['message']);
					}
				}
				else{
					$status=1;
					$inData['orderId']='Testing. No Token Created';
					$provisionResult=array();
				}
			}
			else{
				//less than 1 tells user interface to display error, not success
				$status=-1*$paymentResult['responseData']['ResponseCode'];
				$messages[]=$errorList[]=array('cardProcess', $paymentResult['responseData']['Message']);
				$provisionResult=array();
			}
		}


// $inData['orderId']=$inData['token']=md5(json_encode($inData).time());
// $inData['cardData']['cardNumber']=substr($inData['cardData']['cardNumber'], strlen($inData['cardData']['cardNumber'])-4, 4);;

		if ($status===1){
			$contentArray=$this->contentObj->contentArray;
			$mailSentStatus=$this->sendMail($contentArray, $inData);
		}
		else{
			$mailSentStatus=false;
		}

		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$errorList,
			'data'=>array(
				'token'=>$inData['orderId'],
				'provisionResult'=>$provisionResult,
				'paymentResult'=>$paymentResult,
				'mailSentStatus'=>$mailSentStatus
				)
		));
    }

    private function sendMail($contentArray, $inData){
		$customerReceiptSetup=$contentArray['email.ini']['customerReceipt'];
		$subForms=$customerReceiptSetup['subForms'];


		foreach ($subForms as $label=>$data){
			$inData[$label]='';
			$subFormData=\Q\Utils::getDottedPath($inData, $data['pathToData']);

			foreach ($subFormData as $label2=>$data2){
				$inData[$label].=$this->processTemplate($data['template'], $data2);
			}

		}


		$body=$this->processTemplate($customerReceiptSetup['body'], $inData);
		$subject=$this->processTemplate($customerReceiptSetup['subject'], $inData);

		$toAddress=\Q\Utils::getDottedPath($inData, $customerReceiptSetup['toAddressPath']);
		$toName=\Q\Utils::getDottedPath($inData, $customerReceiptSetup['toNamePath']);

    	$tr=new Zend_Mail_Transport_Sendmail();
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom($customerReceiptSetup['fromAddress'], $customerReceiptSetup['fromName']);
		Zend_Mail::setDefaultReplyTo($customerReceiptSetup['fromAddress'], $customerReceiptSetup['fromName']);

		$mail = new Zend_Mail();
		$mail->setSubject($subject);

		$mail->setBodyHtml($body);

		$mail->addTo($toAddress, $toName);

		if (isset($customerReceiptSetup['ccEmailAddresses']) && gettype($customerReceiptSetup['ccEmailAddresses'])=='array'){
			foreach ($customerReceiptSetup['ccEmailAddresses'] as $label=>$data){
				$mail->addCc($data);
			}
		}

		if (isset($customerReceiptSetup['bccEmailAddresses']) && gettype($customerReceiptSetup['bccEmailAddresses'])=='array'){
			foreach ($customerReceiptSetup['bccEmailAddresses'] as $label=>$data){
				$mail->addBcc($data);
			}
		}


		$mail->send($tr);

		Zend_Mail::clearDefaultFrom();
		Zend_Mail::clearDefaultReplyTo();
		return $mailStatus=array('status'=>1, 'toAddress'=>$toAddress, 'toName'=>$toName);
    }

    private function processTemplate($template, $inData, $debug=false){

		if ( $this->useCardProdServer){
			$inData['prodServerMessage']='';
		}
		else{
			$inData['prodServerMessage']="<div style='font-weight:10pt;color:red;margin-bottom:10px;'>Not a real transaction. Using Test Processing Server</div>";
		}


		foreach ($inData as $label=>$data){
			if (gettype($data)=='array')
				foreach ($data as $label2=>$data2){
					if (gettype($data2)=='string'){
						if ($debug){echo "$label $label2=$data\n\n";}
						$template=preg_replace('/<%=+'.$label.'.'.$label2.'%>/', $data2, $template);
					}
					else{
						$template=preg_replace('/<%=+'.$label.'.'.$label2.'%>/', 'arrayResult', $template);
					}
				}
			elseif (gettype($data)=='string'){
				if ($debug){echo "$label $label2=$data\n\n";}
				$template=preg_replace('/<%=+'.$label.'%>/', $data, $template);
			}
		}
		if ($debug){exit;}
		return $template;
    }

    private function freeCardNo($cardNo){
    	$prefix=substr($cardNo, 0, 4);
    	switch ($prefix){
    		case '8881':
    		case '8888':
    			$status=true;
    		break;
    		default:
    			$status=false;
    		break;
    	}
    	return $status;
    }

} //end of class