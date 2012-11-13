<?php

class Detail1_GenerateController extends Q_Controller_Base
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

		$this->view->contentArray=$this->contentObj->contentArray;
		$this->view->codeNav=$this->getCodeNav(__method__);
    }

	public function validateContentStructure($contentArray){
		//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}
		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'mainContent.html')
			)));

	}

	public function initializeContentDirectory(){
$mainContent=<<<mainContent
Hello from a new page type
mainContent;


		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'mainContent.html'=>$mainContent

			)
		);

		$this->_helper->InitPageTypeDirectory($directories);
	}

} //end of class


