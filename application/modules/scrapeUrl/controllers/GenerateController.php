<?php

class ScrapeUrl_GenerateController extends Q_Controller_Base
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

    	$contentArray=$this->contentObj->contentArray;
    	$contentArray['scrapeData']=$this->getContentZone($this->contentObj->contentArray['targetUrl.txt']);

		$this->view->contentArray=$contentArray;
	//	$this->view->contentObj=$this->contentObj; //not needed by template
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


