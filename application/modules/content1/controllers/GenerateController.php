<?php

class Content1_GenerateController extends Q_Controller_Base
{
    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'cms'));
    }

    public function indexAction()
    {
        $this->containerAction();
    }

    public function containerAction()
    {
		$jsControllerList[]=array(
				"domSelector"=>"#aaa",
				"controllerName"=>'widgets_session_login',
				"parameters"=>''
			);
		$jsControllerList[]=array(
				"domSelector"=>"#bbb",
				"controllerName"=>'widgets_session_login',
				"parameters"=>''
			);
		$jsControllerList[]=array(
				"domSelector"=>"#ccc",
				"controllerName"=>'css',
				"parameters"=>json_encode(array('background'=>'gray', 'color'=>'red'))
			);

     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);

		$this->view->contentObj=$this->contentObj;
		$this->view->codeNav=$this->getCodeNav(__method__);
		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

		$this->view->message='hello!';
    }


	public function validateContentStructure($contentArray){
	//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}

		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'firstFile.txt'),
				array('name'=>'elements')
			)));

		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray['elements'],
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'first.txt'),
				array('name'=>'second.txt')
			)));
	}

}



