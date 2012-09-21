<?php

class ErdcLegacy_GenerateController extends Q_Controller_Base
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

    //eg, http://cmerdc.local/erdcLegacy?url=http://www.erdc.k12.mn.us/distweb/index.php

    	$targetUrl=$this->getRequest()->getQuery('url');

    	if (!$targetUrl){ die('gotta have a url, mon: eg, http://blah.com?url=http://otherBlah.com');}

    	$contentArray=array();
    	$contentArray['scrapeData']=$this->getContentZone($targetUrl);

		$this->setVariationLayout('layout');

		$this->view->contentArray=$contentArray;
	//	$this->view->contentObj=$this->contentObj; //not needed by template
		$this->view->codeNav=$this->getCodeNav(__method__);

    }

	public function validateContentStructure($contentArray){
	//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}

		return; //currently, all parameters come from URL

	}

	private function getContentZone($targetUrl){
		$handler=curl_init($targetUrl);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$rawPage=curl_exec($handler);
		curl_close($handler);

		$dom = new Zend_Dom_Query($rawPage);

		if (preg_match('/erdc/', $targetUrl)){
		$results = $dom->query('.contentzone');
		foreach ($results as $node) {
			$nodeHtml=$results->getDocument()->saveXML($node);
			if ($nodeHtml){break;}
		}
		}
		else{
			$nodeHtml=$rawPage;
		}
		return $nodeHtml;
	}

}


