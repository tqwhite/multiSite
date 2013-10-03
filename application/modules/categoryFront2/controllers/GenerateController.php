<?php

class CategoryFront2_GenerateController extends Q_Controller_Base
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
		"domSelector"=>"#contentList",
		"controllerName"=>'widgets_display_tab_panel',
		"parameters"=>json_encode(array(
						'tabDisplayContainerIdName'=>'tabDisplayContainer',
						'tabListIdName'=>'tabList',
						'switchableContentListId'=>'contentList'
					))
	);
     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);

		$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv
		$this->view->contentArray=$this->contentObj->contentArray;
		$this->view->codeNav=$this->getCodeNav(__method__);
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
				array('name'=>'panelItems', 'assertNotEmptyFlag'=>true),

				array('name'=>'feature.html'),
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

$demoSlideShowA=<<<demoSlideShowA
<div>
TABBED DISPLAY ELEMENT: edit panelItems/a_Demo_Slide_1.html to customize<br/>
<br/>
remember:<br/>
<br/>
1) You can put images in the images directory. Show them in the normal way:<br/>&lt;img src='images/imageFileName.png'&gt;<p/>
2) You can add additional .html files into the slideShow directory if you need more slides.<p/>
3) Slides are shown in the same order they appear in the file system, ie, alphabetically.<br/>Hence, the prefix 'a_'.<p/><p/>
<b>4) Unlike the slideshow in multiPanel, the file names for this component are structured to also support naming the tabs.

Using underscore as a delimiter, the first element is the file system sequence. After that, the rest is used for the tab label.<br/>
After stripping the first part ("a_") the system will convert underscore to space.

Eg, b_Tab_Label appears on the tab as 'Tab Label' (without apostrophes, of course).
</div>
demoSlideShowA;

$demoSlideShowB=<<<demoSlideShowA
<div style='height:500px;'>
TABBED DISPLAY ELEMENT: edit panelItems/b_DemoSlide_2.html to customize<br/>
<br/>
remember:<br/>
<br/>
1) You can put images in the images directory. Show them in the normal way:<br/>&lt;img src='images/imageFileName.png'&gt;<p/>
2) You can add additional .html files into the slideShow directory if you need more slides.<p/>
3) Slides are shown in the same order they appear in the file system, ie, alphabetically.<br/>Hence, the prefix 'b_'.<p/><p/>
<b>4) Unlike the slideshow in multiPanel, the file names for this component are structured to also support naming the tabs.

Using underscore as a delimiter, the first element is the file system sequence. After that, the rest is used for the tab label.<br/>
After stripping the first part ("a_") the system will convert underscore to space.

Eg, b_Tab_Label appears on the tab as 'Tab Label' (without apostrophes, of course).
</div>
demoSlideShowA;

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'contactFooter.html'=>"FOOTER: edit contactFooter.html to customize",
				'headBanner.html'=>"HEAD BANNER: edit headBanner.html to customize",
				'headNav.ini'=>$headNav,
				'siteDirectoryUrlList.ini'=>$siteDirectoryUrlList,

				'feature.html'=>'Edit feature.html to customize',

			),
			'images'=>array(
				'README'=>'this is a placeholder so git will initialize this directory. put images in here'
			),
			'panelItems'=>array( //a directory can only contain files, not subdirectories
				'a_Demo_Slide_1.html'=>$demoSlideShowA,
				'b_DemoSlide_2.html'=>$demoSlideShowB
			)

		);

		$this->_helper->InitPageTypeDirectory($directories);
	}

} //end of class



