<?php

class IndexController extends Q_Controller_Base
{
    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'json'));
    }

    public function test1Action()
    {

    	$serverComm=array();
		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');


	$fileObj=new Q\Helpers\FileContent(array('contentDirPath'=>'/Users/tqwhite/Documents/webdev/cmerdc/cmerdc.local/website//content/'.SITE_VARIATION.'/'));

		$this->view->contentArray=$fileObj->contentArray;

		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

		$this->view->message='hello from '.__method__;
		echo "bottom<br";
    }

    public function test2Action()
    {



    	$serverComm=array();
		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

		$this->view->message='hello from '.__method__;

    }

    public function test3Action()
    {
    	$serverComm=array();
		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

	//	$this->setVariationLayout('layout3');

		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

		$this->view->message='hello from '.__method__;
    }

    public function test5Action()
    {
        // action body
    }


}

















