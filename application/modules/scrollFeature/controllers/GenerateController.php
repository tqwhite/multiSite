<?php

class ScrollFeature_GenerateController extends Q_Controller_Base {
	public function init() //this is called by the Zend __construct() method
	{
		parent::init(array('controllerType' => 'cms'));
	}

	public function indexAction() {
		// action body

	}

	public function containerAction() {

		$contentArray = $this->contentObj->contentArray;
		$this->setLayoutName();
		$this->setJsActivationList();
		

		$this->view->contentArray = $this->contentObj->contentArray;
		$this->view->codeNav = $this->getCodeNav(__method__);
		$this->view->accessOtherPageSupport=$this->getFileContentAccessParameters();
	}

	private function setJsActivationList() {
// 		$jsControllerList[] = array(
// 			"domSelector" => "#contentList", 
// 			"controllerName" => 'widgets_display_tab_panel', 
// 			"parameters" => json_encode(array(
// 				'tabDisplayContainerIdName' => 'tabDisplayContainer', 
// 				'tabListIdName' => 'tabList', 
// 				'switchableContentListId' => 'contentList'
// 			))
// 		);
		$jsControllerList[] = array();
				
		$serverComm = $this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);
		$this->view->serverComm = $this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv
		
	}
}
