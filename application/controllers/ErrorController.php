<?php

class ErrorController extends Q_Controller_Base
{

    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'zend'));
    }

    public function errorAction()
    {
    
// $frontController = Zend_Controller_Front::getInstance();
// $frontController->setParam('disableOutputBuffering', true);
  //  $this->_helper->layout->setLayout('/home/websites/local.tqorg/multiSite/website/application/layouts/scripts/default/nonCmsData');
    
    	$multiSite=Zend_Registry::get('multiSite');
    	
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $errors->request;

		$message=isset($message)?$message:'';

		$serverComm=array();
		$serverComm[]=array("fieldName"=>"user_confirm_message", "value"=>$message);
		$serverComm[]=array("fieldName"=>"assert_initial_controller", "value"=>'none');

		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv
		$this->view->multiSite=$multiSite;
		
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

