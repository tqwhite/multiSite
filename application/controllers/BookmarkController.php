<?php

class BookmarkController extends Q_Controller_Base
{

    public function init()
    {
        parent::init(array('controllerType'=>'zend'));
    }

    public function indexAction()
    {
        // action body
    }

    public function enterAction()
    {
		
		$this->view->parms=$this->_request->getParams();
		
    }

    public function createAction()
    {
    	
    	$params=$this->_request->getParams();
    	
			
		$catObj=new \Application_Model_Category();
		$this->view->categories=$catObj->getList();

		$newObj=new \Application_Model_Bookmark();
		$errorList=$newObj->validate($params);
    	if (count($errorList)>0){
    	
    		$errorCodeInx=2; $dbDupeFound='dbDupeFound'; $errorDataInx=3;
    		if (count($errorList)>0==1 && (isset($errorList[0][$errorCodeInx])==$dbDupeFound)){
    		
    			$newObj=$errorList[0][$errorDataInx];
    			$tmp=$newObj->categoryNodes;

				$bookmark=\Application_Model_Bookmark::formatOutput($newObj, 'web');
				$bookmark['status']='already in database';

			$this->view->bookmark=$bookmark;
    		}
    		else{
    			throw new Exception(\Q\Utils::errorListToString($errorList));
    		}
    	}
    	else{
			$source=array(
					'uri'=>isset($params['uri'])?$params['uri']:'', 
					'anchor'=>isset($params['anchor'])?$params['anchor']:'',
					'categoryList'=>array(
						array('name'=>'nameOne'),
						array('name'=>'nameTwo')
					
					)
			);
			$newObj->makeNew($source, false);

			$bookmark=array(
				'shortId'=>$newObj->entity->shortId,
				'anchor'=>$newObj->entity->anchor,
				'uri'=>$newObj->entity->uri,
				'status'=>'bookmark created'
			);
			
			
			$this->view->bookmark=$bookmark;
		}
		//bookmark/create.phtml
    }

    public function redirectAction()
    {
    	$params=$this->_request->getParams();
        
			$newObj=new \Application_Model_Bookmark();
			$redirectObj=$newObj->getByFieldName('shortId', $params['shortId']);
			if (!$redirectObj){
    			throw new Exception('No such shortId');
			}
//	echo "$redirectObj->accessCount={$redirectObj->accessCount}<br/>";		
			$redirectObj->accessCount=$redirectObj->accessCount+1;
			$newObj->persist(true);
		
//			echo $redirectObj->uri;
			$redirectString="Location: ".$redirectObj->uri;
			header($redirectString);

		foreach ($headerList as $label=>$data){
			header($data);
		}

			exit;
    }

    public function setupAction()
    {
        // action body
    }


}









