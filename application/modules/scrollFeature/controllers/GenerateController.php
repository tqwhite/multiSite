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


		$this->view->contentArray = $this->contentObj->contentArray;
		$this->view->codeNav = $this->getCodeNav(__method__);
	}


	private function setLayoutName() {
		$userLayoutName = \Q\Utils::getDottedPath($this->contentObj->contentArray, 'globalItems,siteSpecs.ini,layoutName', ',');


		if ($userLayoutName) {
			$this->setVariationLayout(str_replace('.phtml', '', $userLayoutName));
		} else {
			$this->setVariationLayout('foundation-default');
		}
	}
}

