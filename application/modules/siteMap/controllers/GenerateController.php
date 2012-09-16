<?php

class SiteMap_GenerateController extends Q_Controller_Base
{
    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'cms'));
    }

    public function indexAction()
    {
        // action body
    }

    public function containerAction()
    {

		$this->setVariationLayout('minimal');

    //	$contentArray=$this->contentObj->contentArray;

		$this->view->routes=$this->getRoutes();
		$this->view->codeNav=$this->getCodeNav(__method__);

    }

    private function getRoutes(){
        return Zend_Registry::get('routes');
    }

	public function validateContentStructure($contentArray){
		//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()

		return; //relies only upon routes.ini

		if (!$contentArray){$contentArray=$this->contentArray;}
		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'targetUrl.txt')
			)));

	}


}



