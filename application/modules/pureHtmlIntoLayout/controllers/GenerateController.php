<?php

class PureHtmlIntoLayout_GenerateController extends Q_Controller_Base
{
	public function init() //this is called by the Zend __construct() method
	{
		parent::init(array('controllerType' => 'cms'));
	}

	public function indexAction() {
		// action body

	}

	public function containerAction() {

		$contentArray = $this->contentObj->contentArray;
		$this->setLayoutName(); //accesses $this->contentObj->contentArray
		

		$this->view->contentArray = $this->contentObj->contentArray;
		$this->view->codeNav = $this->getCodeNav(__method__);
		$this->view->accessOtherPageSupport=$this->getFileContentAccessParameters();
	}

} //end of class




