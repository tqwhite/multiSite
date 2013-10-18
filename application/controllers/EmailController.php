<?php

class EmailController extends Q_Controller_Base
{

    public function init()
    { //this is called by the Zend __construct() method
		$this->inData    = $this->getRequest()->getPost('data');
		$routeName = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
		
		if ($routeName == 'default' && $this->inData['serverManagement']['processContentSourceRouteName']) {
			$overrideRouteName = $this->inData['serverManagement']['processContentSourceRouteName'];
		} else {
			$overrideRouteName = '';
		}
		
		parent::init(array(
			'controllerType' => 'cms',
			'overrideRouteName' => $overrideRouteName
		));
      
        $this->errorList=array();
    }

    public function indexAction()
    {
        // action body
    }

    public function formAction()
    {
        // action body
	
	
		$inData=$this->inData;
		$formParams=$inData['formParams'];
		$mailParams=$inData['mailParams'];
			
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH.'/views/scripts/email');
		$view->mailArray=$formParams;


		if (isset($inData['mailParams']['mailBodyTemplate'])){
			$emailMessage=\Q\Utils::templateReplace(array(
    			'replaceObject'=>$inData['formParams'],
    			'template'=>$inData['mailParams']['mailBodyTemplate']
    		));
    		
		}
		else{
			$emailMessage=$view->render('simple-array.phtml');
		}
		
		$status=$this->sendMail(array(
			'emailMessage'=>$emailMessage,
			'formParams'=>$inData['formParams'],
			'mailParams'=>$inData['mailParams']

		));
		
		if (isset($mailParams['ccAddress'])){
			$emailMessage=$view->render('simple-array.phtml');
			$ccStatus=$this->sendMarketingData(array(
				'emailMessage'=>$emailMessage,
				'formParams'=>$inData['formParams'],
				'mailParams'=>$inData['mailParams']
			));
		}
			
		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$this->errorList,
			'data'=>array(
				$emailMessage
				)
		));
		
		
    }
    
    private function sortDestName($args){
    	$mailParams=$args['mailParams'];
    	$formParams=$args['formParams'];
   	
    	if (isset($mailParams['destAddress']['template'])){
    		$address=\Q\Utils::templateReplace(array(
    			'replaceObject'=>$formParams,
    			'template'=>$mailParams['destAddress']['template']['emailAdr'],
    			'transformations'=>array(
    				'test'=>function($args){ return strtoupper($args['visitorEmail']);}
    			)
    		));
    		$name=\Q\Utils::templateReplace(array(
    			'replaceObject'=>$formParams,
    			'template'=>$mailParams['destAddress']['template']['name']
    		));
    		
    	}
    	else{    	
			$address=$mailParams['destAddress']['firstPart'].'@'.$mailParams['destAddress']['secondPart'];
			$name=$mailParams['destAddress']['name'];
    	}
    	return array(
    		'address'=>$address,
    		'name'=>$name
    	);
    }
    
    private function addToList($mail, $list){
    	for ($i=0, $len=count($list); $i<$len; $i++){
			$element=$list[$i];
			$mail->addTo($element['firstPart'].'@'.$element['secondPart']);
		}
    }
    
    private function addAttachments($mail, $list){
		foreach ($list as $label=>$data){

			$at = new Zend_Mime_Part($data);
			$at->filename = basename($label);
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding = Zend_Mime::ENCODING_BASE64;
			$at->type = 'application/pdf';
        
			$mail->addAttachment($at);


		}
    }
    
    private function sendMail($args){
    

    	$emailMessage=$args['emailMessage'];
    	$mailParams=$args['mailParams'];
    	$emailSubject=$mailParams['mailSubject'];
    	
    	$destInfo=$this->sortDestName($args);
    	$destAdr=$destInfo['address'];
    	$destName=$destInfo['name'];
    	
    	$fromAdr=$mailParams['fromAddress']['firstPart'].'@'.$mailParams['fromAddress']['secondPart'];
    	$fromName=$mailParams['fromAddress']['name'];
    	
    	if (isset($mailParams['replyAddress'])){
    		$replyAdr=$mailParams['replyAddress']['firstPart'].'@'.$mailParams['replyAddress']['secondPart'];
    		$replyName=$mailParams['replyAddress']['name'];
    	}
    	else{
    		$replyAdr=$fromAdr;
    		$replyName=$fromName;
    	}
    	
		$tr=new Zend_Mail_Transport_Sendmail();
		
// 		$tr=new Zend_Mail_Transport_Smtp('mail.justkidding.com', array(
// 			'username'=>'tq@justkidding.com',
// 			'password'=>'xx',
// 			'auth'=>'login'
// 		));
// 		
	
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom($fromAdr, $fromName);
		Zend_Mail::setDefaultReplyTo($replyAdr, $replyName);


		$mail = new Zend_Mail();
		$mail->setSubject($emailSubject);
		$mail->setBodyHtml($emailMessage);


		$mail->addTo($destAdr);
		$mail->addTo($destAdr);
		if (isset($this->contentObj->contentArray['ATTACHMENTS'])){$this->addAttachments($mail, $this->contentObj->contentArray['attachments']);}
		$mail->addBcc('tqwhite@erdc.k12.mn.us');



		if (true||getenv('APPLICATION_ENV')!='development'){
			$mail->send($tr); 
			return 1;
		}
		else{
			$this->errorList[]=array('mail', 'development: mail not sent');
			return -1;
		}
    
    }

    
    private function sendMarketingData($args){
    

    	$emailMessage=$args['emailMessage'];
    	$mailParams=$args['mailParams'];
    	$emailSubject=$mailParams['mailSubject'];
    	
    	$destInfo=$this->sortDestName($args);
    	$destAdr=$destInfo['address'];
    	$destName=$destInfo['name'];
    	
    	$fromAdr=$mailParams['fromAddress']['firstPart'].'@'.$mailParams['fromAddress']['secondPart'];
    	$fromName=$mailParams['fromAddress']['name'];
    	
    	if (isset($mailParams['replyAddress'])){
    		$replyAdr=$mailParams['replyAddress']['firstPart'].'@'.$mailParams['replyAddress']['secondPart'];
    		$replyName=$mailParams['replyAddress']['name'];
    	}
    	else{
    		$replyAdr=$fromAdr;
    		$replyName=$fromName;
    	}
    	
		$tr=new Zend_Mail_Transport_Sendmail();
		
// 		$tr=new Zend_Mail_Transport_Smtp('mail.justkidding.com', array(
// 			'username'=>'tq@justkidding.com',
// 			'password'=>'xx',
// 			'auth'=>'login'
// 		));
		
	
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom($fromAdr, $fromName);
		Zend_Mail::setDefaultReplyTo($replyAdr, $replyName);


		$mail = new Zend_Mail();
		$mail->setSubject($emailSubject);
		$mail->setBodyHtml($emailMessage);


		$this->addToList($mail, $mailParams['ccAddress']);
		$mail->addTo($destAdr);
		$mail->addBcc('tqwhite@erdc.k12.mn.us');
		

		if (true||getenv('APPLICATION_ENV')!='development'){
			$mail->send($tr);
			return 1;
		}
		else{
			$this->errorList[]=array('mail', 'development: mail not sent');
			return -1;
		}


    
    }
}



