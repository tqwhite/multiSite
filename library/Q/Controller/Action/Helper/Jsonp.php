<?php

class Q_Controller_Action_Helper_Jsonp extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Suppress exit when sendJson() called
     * @var boolean
     */
    public $suppressExit = false;


    public function encodeJson($data, $keepLayouts = false)
    {
        /**
         * @see Zend_View_Helper_Json
         */
        require_once 'Zend/View/Helper/Json.php';
        $jsonHelper = new Zend_View_Helper_Json();
        $data = $jsonHelper->json($data, $keepLayouts);

        if (!$keepLayouts) {
            /**
             * @see Zend_Controller_Action_HelperBroker
             */
            require_once 'Zend/Controller/Action/HelperBroker.php';
            Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
        }

        return $data;
    }


    public function sendJson($data, $callBack)
    {

        $data = $this->encodeJson($data, $keepLayouts=false); //I don't know what keepLayouts means
        $response = $this->getResponse();

        $responseData="$callBack($data)";

        $response->setBody($responseData);

        if (!$this->suppressExit) {
            $response->sendResponse();
            exit;
        }

        return $data;
    }

    public function direct($data, $callBack)
    {
	    return $this->sendJson($data, $callBack);
    }
}
