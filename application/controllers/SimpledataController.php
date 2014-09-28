<?php

class SimpledataController  extends Q_Controller_Base
{



    public function init()
    { //this is called by the Zend __construct() method
		$this->inData    = $this->getRequest()->getPost('data');
		$routeName = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
		
		if ($routeName == 'default' && $this->inData['serverManagement']['processContentSourceRouteName']) {
			$overrideRouteName = $this->inData['serverManagement']['processContentSourceRouteName'];
		} else {
			$overrideRouteName = '';
		}
		
		parent::init(array(
			'controllerType' => 'cms',
			'overrideRouteName' => $overrideRouteName
		));
      
        $this->errorList=array();
    }
    

    public function indexAction()
    {
        // action body
    }
    
    public function saveAction(){
    
        // action body
	
	
		$inData=$this->inData;
		$formParams=$inData['formParams'];
		
		$contentArray=$this->contentObj->contentArray;	
		
		$folderName=$this->inData['controlParameters']['parameterFolderName'];
		$fileName=$this->inData['controlParameters']['parameterFileName'];
		
		$parameters=$contentArray[$folderName][$fileName];

	
		$simpleData=new \Application_Model_SimpleData($parameters);
		
		$status=$simpleData->save();
		

		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$this->errorList,
			'data'=>array(
				$emailMessage
				)
		));
		
		
    
    }


}

