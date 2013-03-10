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
\Q\Utils::dumpCli($mailParams);		
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH.'/views/scripts/email');
		$view->mailArray=$formParams;
		$emailMessage=$view->render('simple-array.phtml');
		
// 		$this->sendMail(array(
// 			emailMessage=>$emailMessage,
// 			mailParams=>$inData['formParams']
// 		));
        
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
    	$mailParams=$argss['mailParams'];
    	
    	
		$tr=new Zend_Mail_Transport_Sendmail();
		Zend_Mail::setDefaultTransport($tr);
		Zend_Mail::setDefaultFrom('school@genatural.com', "Good Earth Lunch Program");
		Zend_Mail::setDefaultReplyTo('school@genatural.com', "Good Earth Lunch Program");


		$mail = new Zend_Mail();
		$mail->setSubject($emailSubject);
		$mail->setBodyHtml($emailMessage);


		$mail->addTo($element['address'], $element['name']);

		$mail->send($tr);


		Zend_Mail::clearDefaultFrom();
		Zend_Mail::clearDefaultReplyTo();
    
    }


}



