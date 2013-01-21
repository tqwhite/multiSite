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


}



