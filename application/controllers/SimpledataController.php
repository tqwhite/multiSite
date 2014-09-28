<?php

class SimpledataController extends Zend_Controller_Action
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
\Q\Utils::dumpCli($inData, "inData");exit;
		
		if (isset($inData['fileList'])){
			$fileList=$inData['fileList'];
		}
		else{
			$fileList='';
		}
			
		$view = new Zend_View();
		$view->setScriptPath(APPLICATION_PATH.'/views/scripts/email');
		$view->mailArray=$formParams;

		if (isset($inData['mailParams']['mailBodyTemplate'])){
			$emailMessage=\Q\Utils::templateReplace(array(
    			'replaceObject'=>$inData['formParams'],
    			'template'=>$inData['mailParams']['mailBodyTemplate']
    		));
    		
		}
		elseif (isset($inData['mailParams']['internalBodyTemplate'])){
				$emailMessage=\Q\Utils::templateReplace(array(
					'replaceObject'=>$inData['formParams'],
					'template'=>$inData['mailParams']['internalBodyTemplate']
				));
			
			}
		else {
			$emailMessage=$view->render('simple-array.phtml');
		}
		
		$status=$this->sendMail(array(
			'emailMessage'=>$emailMessage,
			'formParams'=>$inData['formParams'],
			'mailParams'=>$inData['mailParams'],
			'fileList'=>$fileList

		));
		
		if (isset($mailParams['ccAddress'])){
		
		
			if (isset($inData['mailParams']['internalBodyTemplate'])){
				$emailMessage=\Q\Utils::templateReplace(array(
					'replaceObject'=>$inData['formParams'],
					'template'=>$inData['mailParams']['internalBodyTemplate']
				));
			
			}
			else{
				$emailMessage=$view->render('simple-array.phtml');
			}
		
			$ccStatus=$this->sendMarketingData(array(
				'emailMessage'=>$emailMessage,
				'formParams'=>$inData['formParams'],
				'mailParams'=>$inData['mailParams'],
				'fileList'=>$fileList
			));
		}

	
		if (is_array($fileList) && count($fileList)>0){
				$this->removeUploads($fileList);
		}			
		
		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$this->errorList,
			'data'=>array(
				$emailMessage
				)
		));
		
		
    
    }


}

