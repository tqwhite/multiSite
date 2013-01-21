<?php

class PureHtml_GenerateController extends Q_Controller_Base
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

        $this->view->requestParms=$this->_request->getParams();
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
				array('name'=>'index.html'),
				array('name'=>'elements')
			)));

	}

	public function initializeContentDirectory(){
$serverCommSample=<<<serverComSample
<div id='serverData' style='display:none;'>
<input type=hidden name='controller_startup_list[0][domSelector]' value='body'>
<input type=hidden name='controller_startup_list[0][controllerName]' value='widgets_SOMETHINGINTERESTING'>
<input type=hidden name='controller_startup_list[0][parameters]' value='{"background":"gray","color":"red"}'>
</div>
serverComSample;
$serverCommSample=htmlentities($serverCommSample);

$index=<<<index


Edit index.html to customize this page. Everything you enter will be put into the &lt;body&gt; tag. No filtering is done.

You can put images into the elements folder as well as any other linkable things (stylesheets, .js, video, etc). Address them
as usual (&lt;img src='elements/imageName.png'&gt;).

You can communicate with the Widgets library using the serverData div. EG,

<!serverCommSample!>

Someday, there will be a library of widgets you can use.

jQuery is available, too, but it has to be addressed through the serverData mechanism. Replace the controllerName value 'widgets_SOMETHINGINTERESTING' with
a jQuery routine, eg, 'fade' or something.


index;

$index=str_replace('<!serverCommSample!>', $serverCommSample, $index);
$index=nl2br($index);
$index="<div style='margin:20px;'>$index</div>";

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'index.html'=>$index

			),
			'elements'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.
				'README'=>'fill this directory with image files used by your page'

			)

		);

		$this->_helper->InitPageTypeDirectory($directories);
	}

}



