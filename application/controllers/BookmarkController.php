<?php

class BookmarkController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
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
		$source=array(
			array(
				'uri'=>isset($params['uri'])?$params['uri']:'', 
				'anchor'=>isset($params['anchor'])?$params['anchor']:''
			)
		);

		$newObj=new \Application_Model_Bookmark();
		$errorList=$newObj->validate($params);
    	if (count($errorList)>0){
    		throw new Exception(\Q\Utils::errorListToString($errorList));
    	}
    	else{
			$newObj->newFromArrayList($source, false);

			$bookmark=array(
				'shortId'=>$newObj->entity->shortId,
				'anchor'=>$newObj->entity->anchor,
				'uri'=>$newObj->entity->uri
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
			
//			echo $redirectObj->uri;
			$redirectString="Location: ".$redirectObj->uri;
			header($redirectString);

		foreach ($headerList as $label=>$data){
			header($data);
		}

			exit;
    }


}







