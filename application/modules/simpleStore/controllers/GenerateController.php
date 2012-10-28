<?php

class SimpleStore_GenerateController extends Q_Controller_Base
{
    public function init() //this is called by the Zend __construct() method
    {
        parent::init(array('controllerType'=>'cms'));
    }

    public function indexAction()
    {
        // action body
    }

    public function containerAction()
    {
       $this->setVariationLayout('layout');

		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

				$jsControllerList[]=array(
				"domSelector"=>".mainContentContainer",
				"controllerName"=>'widgets_simple_store_main',
				"parameters"=>json_encode(array('paymentServerUrl'=>'http://store.tensigma.local/simpleStore/generate/process'))
			);

     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);
     	$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

		$contentArray=$this->contentObj->contentArray;

		$this->view->contentArray=$contentArray;
		$this->view->codeNav=$this->getCodeNav(__method__);
    }

	public function validateContentStructure($contentArray){
		//this is passed to QHelpersFileContent ($this->FileContainer) by Q_Controller_Base::init()
		if (!$contentArray){$contentArray=$this->contentArray;}
		\Q\Utils::validateProperties(array(
			'validatedEntity'=>$contentArray,
			'source'=>__file__,
			'propertyList'=>array(
				array('name'=>'headBanner.html'),
				array('name'=>'products.ini')
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

$productList=<<<PRODUCTS

prodA.prodcCode="prodA";
prodA.name="Product A";
prodA.price=11.33;
prodA.description="This is an excellent <b>Product A</b> thing."

prodB.prodcCode="prodB";
prodB.name="Product B";
prodB.price=22.33;
prodB.description="This is an excellent <b>Product B</b> thing."


PRODUCTS;

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'contactFooter.html'=>"FOOTER: edit contactFooter.html to customize",
				'headBanner.html'=>"HEAD BANNER: edit headBanner.html to customize",
				'headNav.ini'=>$headNav,
				'pageTitle.txt'=>"TITLE: edit pageTitle.txt to customize",
				'siteDirectoryUrlList.ini'=>$siteDirectoryUrlList,
				'products.ini'=>$productList

			),
			'images'=>array(
				'README'=>'this is a placeholder so git will initialize this directory. put images in here'
			)

		);

		$this->_helper->InitPageTypeDirectory($directories);
	}



    public function processAction()
    {
        //HACKERY: This action is *not* mapped by the multiSite routing system. It uses the
        //Zend default controller. Consequently, contentArray comes from
        //a directory called 'default'.
        //Also, I don't know why it's not being rejected by validateContentStructure() but
        //I need to get this done. Maybe I'll come back to it later. tqii

		$inData=$this->getRequest()->getPost('data');
		$status=-1; //change it to good (1) when something good happens
		$messages=array(array('test', "hello from server"));


		$errorList=\Application_Model_Purchase::validate($inData);
		if (count($errorList)==0){$status=1;}

		$this->_helper->json(array(
			status=>$status,
			messages=>$errorList,
			data=>array(tmp=>'test')
		));
    }

} //end of class