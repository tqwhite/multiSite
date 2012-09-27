<?php

class ExternalUrlList_GenerateController extends Q_Controller_Base
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

		$contentArray=$this->contentObj->contentArray;

    	$contentArray['scrapeDataList']=$this->scrapeList($contentArray['urlList.ini']);

		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

		$jsControllerList[]=array(
				"domSelector"=>"#contentList",
				"controllerName"=>'widgets_display_link_switch',
				"parameters"=>json_encode(
					array(
						'controlButtonIdClassName'=>'contentListActivator',
						'switchablePanelClassName'=>'switchablePanel'
					)
				)
			);
     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);

		$this->setVariationLayout('layout');

		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv
		$this->view->contentArray=$contentArray;
	//	$this->view->contentObj=$this->contentObj; //not needed by template
		$this->view->codeNav=$this->getCodeNav(__method__);

    }

    private function scrapeList($inData){
		$outList=array();
    	foreach ($inData as $label=>$group){
			foreach ($group as $label2=>$section){
				foreach($section['links'] as $label3=>$linkItem){
						$tmp=$linkItem;
					$tmp['content']=$this->getContentZone($linkItem);

					$outList[]=$tmp;
					}
			}
		}
		return $outList;
    }

	private function getContentZone($inData){
		$targetUrl=$inData['url'];
		$handler=curl_init($targetUrl);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$rawPage=curl_exec($handler);
		curl_close($handler);

		$dom = new Zend_Dom_Query($rawPage);

		if (isset($inData['selector'])){
		$results = $dom->query($inData['selector']);
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


	public function validateContentStructure($contentArray){
	//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}

		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'urlList.ini')
			)));
	}

}



