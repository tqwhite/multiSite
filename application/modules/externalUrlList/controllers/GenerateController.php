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

		$this->setVariationLayout('layout');

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
				array('name'=>'headNav.ini'),
				array('name'=>'headBanner.html'),
				array('name'=>'urlList.ini')
			)));
	}

	public function initializeContentDirectory(){
	$headNav=<<<HEADNAV

menu.0.title="About Us"

menu.0.links.0.title='Our Story'
menu.0.links.0.url='#ourStory'

menu.0.links.1.title='Contact Info'
menu.0.links.1.url='#contactInfo'


menu.1.title="Our Partners"

menu.1.links.0.title='The Cambridge Group'
menu.1.links.0.url='http://www.cambridgestrategics.com/'

menu.1.links.1.title='Anoka-Henepin School District'
menu.1.links.1.url='http://www.---.com/'

HEADNAV;

$siteDirectoryUrlList=<<<SITEDIR

;if you do not want a site directory, leave the rest of this file blank
a.0.title="Company List"

a.0.links.0.title='Administrative Solutions'
a.0.links.0.url='http://admin.cmerdc.local/sitemap'
a.0.links.0.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

a.0.links.1.title='Document Imaging'
a.0.links.1.url='http://imaging.cmerdc.local/sitemap'
a.0.links.1.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

SITEDIR;

$urlList=<<<URLLIST

a.0.title="a 1"

a.0.links.1.title='Overview'
a.0.links.1.url='http://www.erdc.k12.mn.us/products/prdataw/index.php'
a.0.links.1.selector='.contentzone'

a.0.links.2.title='Contact'
a.0.links.2.url='http://www.erdc.k12.mn.us/products/prdataw/dwcontac/index.php'
a.0.links.2.selector='.contentzone'

URLLIST;

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'contactFooter.html'=>"FOOTER: edit contactFooter.html to customize",
				'headBanner.html'=>"HEAD BANNER: edit headBanner.html to customize",
				'headNav.ini'=>$headNav,
				'pageTitle.txt'=>"TITLE: edit pageTitle.txt to customize",
				'siteDirectoryUrlList.ini'=>$siteDirectoryUrlList,
				'urlList.ini'=>$urlList

			)

		);

		$this->_helper->InitPageTypeDirectory($directories);
	}

}



