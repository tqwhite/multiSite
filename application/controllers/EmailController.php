<?php

class EmailController extends Q_Controller_Base
{

    public function init()
    {
        /* Initialize action controller here */
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
		
\Q\Utils::dumpCli($inData, 'inData');		
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH.'/views/scripts/email');
		$view->mailArray=$formParams;
		$emailMessage=$view->render('simple-array.phtml');
		
		$this->sendMail(array(
			'emailMessage'=>$emailMessage,
			'formParams'=>$inData['formParams'],
			'mailParams'=>$inData['mailParams'],

		));
        
        $errorList=array();
        $status=99;

			
		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$errorList,
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


		$mail = new Zend_Mail();
		$mail->setSubject($emailSubject);
		$mail->setBodyHtml($emailMessage);


		$mail->addTo($destAdr);

//		$mail->send($tr);


		Zend_Mail::clearDefaultFrom();
		Zend_Mail::clearDefaultReplyTo();
    
    }


}



