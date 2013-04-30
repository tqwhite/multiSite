<?php

class PictureShow_GenerateController extends Q_Controller_Base
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
    	//operates with default layout 'layout'

		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

				$jsControllerList[]=array(
				"domSelector"=>"#anythingslider",
				"controllerName"=>'widgets_display_anythingslider',
				"parameters"=>json_encode(array(
					'sliderParms'=>array('autoPlay'=>true, 'color'=>'red')
					)
				)
			);

     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);

		$this->view->contentObj=$this->contentObj;
		$this->view->contentArray=$this->contentObj->contentArray;
		$this->view->codeNav=$this->getCodeNav(__method__);
		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

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
				array('name'=>'pictureFrame.html'),
				array('name'=>'images', 'assertNotEmptyFlag'=>true)
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
a.0.links.0.url='http://admin.<!rootDomainSegment!>/sitemap'
a.0.links.0.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

a.0.links.1.title='Document Imaging'
a.0.links.1.url='http://imaging.<!rootDomainSegment!>/sitemap'
a.0.links.1.selector='.contentzone'	;this is a jQuery selector for the content to extract from target page

SITEDIR;

$demoPictureFrame=<<<demoPictureFrame
<img src='<!imagePath!>'>
demoPictureFrame;


		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'contactFooter.html'=>"FOOTER: edit contactFooter.html to customize",
				'headBanner.html'=>"HEAD BANNER: edit headBanner.html to customize",
				'headNav.ini'=>$headNav,
				'siteDirectoryUrlList.ini'=>$siteDirectoryUrlList,
				'pictureFrame.html'=>$demoPictureFrame

			),
			'images'=>array(
				'README'=>'this is a placeholder so git will initialize this directory. put images in here'
			)

		);

		$this->_helper->InitPageTypeDirectory($directories);
	}

} //end of class



