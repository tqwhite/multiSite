<?php

class FancyStore1_GenerateController extends Q_Controller_Base
{
    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'cms'));
    }

    public function indexAction()
    {
        // action body 
    }


    private function updateGlobals($config){
    	$this->cardProcessorAuth=$config['simpleStore.ini']['moneris'];
		$this->simpleStore=$config['simpleStore.ini']['simpleStore'];

		$this->simpleStore['provision']['url']=$this->simpleStore['provision']['productionUrl'];
    }
    
    public function containerAction(){
    
		$contentArray=$this->contentObj->contentArray;
		$this->updateGlobals($contentArray['globalItems']['CONFIG']);

    	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){ $scheme='https://';}
    	else{ $scheme='http://';}
    
    	$productSectionArray=$this->productSectionArray($this->contentObj->contentArray['productSpecs']);

		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

				$jsControllerList[]=array(
				"domSelector"=>".mainContentContainer",
				"controllerName"=>'widgets_simple_store_main',
				"parameters"=>json_encode(
					array(
						'paymentServerUrl'=>$scheme.$_SERVER['HTTP_HOST'].'/simpleStore/generate/process',
						'deferAppearance'=>true,
						'catalogData'=>htmlentities($this->contentObj->contentArray['productSpecs'])
					)
				)
			);

     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);
     	$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv


		$this->setVariationLayout('layout');
		$this->view->productSectionArray=$productSectionArray;
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
				array('name'=>'catalogTemplate.html'),
				array('name'=>'productListingTemplate.html'),
				array('name'=>'productPageTemplate.html'),
				array('name'=>'productPopupTemplate.html'),
				array('name'=>'productSpecs', 'assertNotEmptyFlag'=>true)
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

private function productSectionArray($productArray){
$productSectionArray=array();
$hasCategoryList=array();
$remainderList=array();

		foreach ($productArray as $label=>$data){
			if (isset($data['keywords']) && is_array($data['keywords'])){
				foreach ($data['keywords'] as $label2=>$data2){
					$productSectionArray[$data2][]=$data;
					$hasCategoryList[$label]=true;
				}
			}
		}

		foreach ($productArray as $label=>$data){

			if (!isset($hasCategoryList[$label])){
				$remainderList[$label]=$data;
			}
		}
		
		if (count($remainderList)>0){
			$productSectionArray['uncategorized']=$remainderList;
		}

return $productSectionArray;
}
} //end of class