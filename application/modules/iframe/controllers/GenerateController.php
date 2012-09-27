<?php

class Iframe_GenerateController extends Q_Controller_Base
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
		$this->setVariationLayout('minimal');

		$this->view->contentObj=$this->contentObj;
		$this->view->codeNav=$this->getCodeNav(__method__);

    }

	public function validateContentStructure($contentArray){
	//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}
		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'targetUrl.txt')
			)));

	}

	public function initializeContentDirectory(){
		$contents='https://github.com/tqwhite/multiSite';

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'targetUrl.txt'=>$contents
				)
		);

		$this->_helper->InitPageTypeDirectory($directories);
	}


}



