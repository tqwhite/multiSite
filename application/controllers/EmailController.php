<?php

class EmailController extends Q_Controller_Base
{

    public function init()
    {
        /* Initialize action controller here */
        
        $this->errorList=array();
    }

    public function indexAction()
    {
        // action body
    }

    public function formAction()
    {
        // action body
        
		$inData=$this->getRequest()->getPost('data');
		$formParams=$inData['formParams'];
		$mailParams=$inData['mailParams'];
			
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH.'/views/scripts/email');
		$view->mailArray=$formParams;
		$emailMessage=$view->render('simple-array.phtml');
		
		$status=$this->sendMail(array(
			'emailMessage'=>$emailMessage,
			'formParams'=>$inData['formParams'],
			'mailParams'=>$inData['mailParams'],

		));

			
		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$this->errorList,
			'data'=>array(
				$emailMessage
				)
		));
		
		
    }
    
    private function sendMail($args){
    	$emailMessage=$args['emailMessage'];
    	$mailParams=$args['mailParams'];
    	$emailSubject=$mailParams['mailSubject'];
    	
    	$destAdr=$mailParams['destAddress']['firstPart'].'@'.$mailParams['destAddress']['secondPart'];
    	$destName=$mailParams['destAddress']['name'];
    	
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
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom($fromAdr, $fromName);
		Zend_Mail::setDefaultReplyTo($replyAdr, $replyName);

		
		$headerListing=\Q\Utils::dumpWebString($_SERVER, true);
		$emailMessage="
			$emailMessage
			
			$destAdr<p/>
			
			$headerListing
		";
		

		$mail = new Zend_Mail();
		$mail->setSubject($emailSubject);
		$mail->setBodyHtml($emailMessage);


		$mail->addTo($destAdr);
		

		
		$mail->addBcc('tq@justkidding.com');

		if (getenv('APPLICATION_ENV')!='development'){
			$mail->send($tr);
			return 1;
		}
		else{
			$this->errorList[]=array('mail', 'development: mail not sent');
			return -1;
		}


		Zend_Mail::clearDefaultFrom();
		Zend_Mail::clearDefaultReplyTo();
    
    }


}



