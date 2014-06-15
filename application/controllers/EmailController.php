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

		if (isset($inData['fileList'])){
			$fileList=$inData['fileList'];
		}
		else{
			$fileList='';
		}
			
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH.'/views/scripts/email');
		$view->mailArray=$formParams;

		if (isset($inData['mailParams']['mailBodyTemplate'])){
			$emailMessage=\Q\Utils::templateReplace(array(
    			'replaceObject'=>$inData['formParams'],
    			'template'=>$inData['mailParams']['mailBodyTemplate']
    		));
    		
		}
		elseif (isset($inData['mailParams']['internalBodyTemplate'])){
				$emailMessage=\Q\Utils::templateReplace(array(
					'replaceObject'=>$inData['formParams'],
					'template'=>$inData['mailParams']['internalBodyTemplate']
				));
			
			}
		else {
			$emailMessage=$view->render('simple-array.phtml');
		}
		
		$status=$this->sendMail(array(
			'emailMessage'=>$emailMessage,
			'formParams'=>$inData['formParams'],
			'mailParams'=>$inData['mailParams'],
			'fileList'=>$fileList

		));
		
		if (isset($mailParams['ccAddress'])){
		
		
			if (isset($inData['mailParams']['internalBodyTemplate'])){
				$emailMessage=\Q\Utils::templateReplace(array(
					'replaceObject'=>$inData['formParams'],
					'template'=>$inData['mailParams']['internalBodyTemplate']
				));
			
			}
			else{
				$emailMessage=$view->render('simple-array.phtml');
			}
		
			$ccStatus=$this->sendMarketingData(array(
				'emailMessage'=>$emailMessage,
				'formParams'=>$inData['formParams'],
				'mailParams'=>$inData['mailParams'],
				'fileList'=>$fileList
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
    
    private function addAttachments($mail, $list, $contentList){
		foreach ($list as $label=>$data){

			if (!isset($contentList[$data])){die(__FILE__.", line ".__LINE__." says, There is no items called '$data' in the ATTACHMENTS folder");}

			$at = new Zend_Mime_Part($contentList[$data]);
			$at->filename = basename($data);
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding = Zend_Mime::ENCODING_BASE64;
			$at->type = 'application/pdf';
        
			$mail->addAttachment($at);


		}
    }
    
    private function addUploads($mail, $fileList, $fileNamePrefix=''){
		foreach ($fileList as $fileId=>$filePath){
		
			$fileContent=file_get_contents($filePath);
			
			
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mimeType = $finfo->file($filePath);
		
$extension=array_search(
			$mimeType,
			array(
				'jpg' => 'image/jpeg',
				'png' => 'image/png',
				'gif' => 'image/gif',
			),
			true
		);

			$at = new Zend_Mime_Part($fileContent);
			$at->filename = basename($fileNamePrefix.$fileId.'\.'.$extension);
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding = Zend_Mime::ENCODING_BASE64;
			$at->type = $mimeType;
        
			$mail->addAttachment($at);
		
		exec("rm $filePath");
			
		}
    }
    
    private function sendMail($args){
    

    	$emailMessage=$args['emailMessage'];
    	$mailParams=$args['mailParams'];
    	$emailSubject=$mailParams['mailSubject'];
    	$fileList=$args['fileList'];
    	$formParams=$args['formParams'];
    	
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
		
// 		$tr=new Zend_Mail_Transport_Smtp('smtp.mandrillapp.com', array(
// 			'username'=>'tq@justkidding.com',
// 			'password'=>'**',
// 			'auth'=>'login'
// 		));
		
	
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom($fromAdr, $fromName);
		Zend_Mail::setDefaultReplyTo($replyAdr, $replyName);


		$mail = new Zend_Mail();
		$mail->setSubject($emailSubject);
		$mail->setBodyHtml($emailMessage);


		$mail->addTo($destAdr);
		$mail->addTo($destAdr);
	
		if (is_array($fileList) && count($fileList)>0){
		
				$uploadFilePrefix=$mailParams['uploadFilePrefix'];
				foreach ($formParams as $label=>$data){
					$uploadFilePrefix=str_replace("<!$label!>", $data, $uploadFilePrefix);
				}

				$this->addUploads($mail, $fileList, $uploadFilePrefix);
				
		}
		
		if (isset($mailParams['attachments']) && count($mailParams['attachments'])>0){
			if (isset($this->contentObj->contentArray['ATTACHMENTS'])){$this->addAttachments($mail, $mailParams['attachments'], $this->contentObj->contentArray['ATTACHMENTS']);}
			else{ die(__FILE__.", line ".__LINE__." says, There is no ATTACHMENTS folder");}
		
		}
		
		if(!isset($mailParams['noBccTq']) || $mailParams['noBccTq']!='true'){
			$mail->addBcc('tq@justkidding.com');
		}



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
    	$fileList=$args['fileList'];
    	
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
	
		if (is_array($fileList) && count($fileList)>0){
				$this->addUploads($mail, $fileList, $mailParams['uploadFilePrefix']);
		}
		

		if(!isset($mailParams['noBccTq']) || $mailParams['noBccTq']!=true){
			$mail->addBcc('tq@justkidding.com');
		}
		

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



