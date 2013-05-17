<?php

class FancyStore1_GenerateController extends Q_Controller_Base
{
    public function init() //this is called by the Zend __construct() method
    {

        parent::init(array('controllerType'=>'cms'));
    }

    public function indexAction()
    {
        // action body 
    }


    private function updateGlobals($config){
    	$this->cardProcessorAuth=$config['simpleStore.ini']['moneris'];
		$this->simpleStore=$config['simpleStore.ini']['simpleStore'];
		$this->defaults=(isset($config['defaults.ini']))?$config['defaults.ini']:array();

		$this->simpleStore['provision']['url']=$this->simpleStore['provision']['productionUrl'];
    }
    
    public function containerAction(){
    
		$contentArray=$this->contentObj->contentArray;
		$this->updateGlobals($contentArray['globalItems']['CONFIG']);

    	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){ $scheme='https://';}
    	else{ $scheme='http://';}
    
    	$productSectionArray=$this->productSectionArray($this->contentObj->contentArray['productSpecs']);
    	
    	$catalogEntities=\Q\Utils::htmlentities($this->contentObj->contentArray['productSpecs'], array("@apos;"=>'@apos;'));

		$serverComm[]=array("fieldName"=>"message", "value"=>'hello from the server via javascript');

				$jsControllerList[]=array(
				"domSelector"=>".mainContentContainer",
				"controllerName"=>'widgets_simple_store_main',
				"parameters"=>json_encode(
					array(
						'paymentServerUrl'=>$scheme.$_SERVER['HTTP_HOST'].'/simpleStore/generate/process',
						'deferAppearance'=>true,
						'catalogData'=>$catalogEntities,
						'processContentSourceRouteName'=>Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName()
					)
				)
			);

     	$serverComm=$this->_helper->ArrayToServerCommList('controller_startup_list', $jsControllerList);
     	$this->view->serverComm=$this->_helper->WriteServerCommDiv($serverComm); //named: Q_Controller_Action_Helper_WriteServerCommDiv

		if (isset($contentArray['config.ini']) && isset($contentArray['config.ini']['layout'])){
	
		$this->setVariationLayout($contentArray['config.ini']['layout']);
		}
		elseif (isset($this->defaults['layout'])){
		$this->setVariationLayout($this->defaults['layout']);
		}
		else{
		$this->setVariationLayout('layout');
		}
		
		
		$this->view->simpleStore=$this->simpleStore;
		$this->view->productSectionArray=$productSectionArray;
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
// 				array('name'=>'catalogTemplate.html'),
// 				array('name'=>'productListingTemplate.html'),
// 				array('name'=>'productPageTemplate.html'),
// 				array('name'=>'productPopupTemplate.html'),
				array('name'=>'productSpecs', 'assertNotEmptyFlag'=>true)
			)));

	}

private function productSectionArray($productArray){
$productSectionArray=array();
$hasCategoryList=array();
$remainderList=array();

		foreach ($productArray as $label=>$data){
			if (isset($data['keywords']) && is_array($data['keywords'])){
				foreach ($data['keywords'] as $label2=>$data2){
					$productSectionArray[$data2][]=$data;
					$hasCategoryList[$label]=true;
				}
			}
		}

		foreach ($productArray as $label=>$data){

			if (!isset($hasCategoryList[$label])){
				$remainderList[$label]=$data;
			}
		}
		
		if (count($remainderList)>0){
			$productSectionArray['uncategorized']=$remainderList;
		}

return $productSectionArray;
}

	public function initializeContentDirectory(){
$mainContent=<<<mainContent
Hello from a new page type
mainContent;

$productOne=<<<PRODUCTONE
name="The Name of Product One"
prodCode="1052"
price="100"
keywords.0="First Category"

priceSuffix="/yr"

;confirmationMessageTemplateName="somethingIn_productConfirmationMessageTemplates.html";
;needsShipping=true;

description="This is a lengthy paragraph or two describing the product virtues. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sodales, ante at elementum tempus, ante urna eleifend ipsum, eu ultrices justo quam sit amet sapien. Mauris pulvinar vehicula nibh, vel dictum purus ornare non. Morbi nibh est, aliquam at faucibus non, semper quis leo. Ut suscipit dignissim interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam ultrices arcu vitae dui posuere porttitor. Ut ut nisi metus. Sed justo risus, blandit ut rhoncus vitae, scelerisque ut quam. Nulla faucibus nisl eget elit porttitor euismod. Cras vel ipsum ut ipsum semper facilisis eget vel lectus. In hac habitasse platea dictumst.
<br/><br/>
Aliquam laoreet aliquet facilisis. In tellus turpis, consectetur at sodales ac, blandit sed urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus eu massa et enim ornare dictum ac sit amet leo. Fusce lorem diam, fermentum non venenatis eget, sagittis ut lorem. Maecenas cursus ultrices massa vitae elementum. In eros lectus, cursus ac accumsan eu, accumsan nec ligula. Integer faucibus sodales rutrum.
";

PRODUCTONE;

$productTwo=<<<PRODUCTONE
name="The Name of Product Two"
prodCode="1052"
price="100"
keywords.0="First Category"
keywords.1="Second Category"
keywords.2="Matches Something In categoryTemplates Folder"

priceSuffix="/yr"

;confirmationMessageTemplateName="somethingIn_productConfirmationMessageTemplates.html";
;needsShipping=true;

description="This is a lengthy paragraph or two describing the product virtues. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sodales, ante at elementum tempus, ante urna eleifend ipsum, eu ultrices justo quam sit amet sapien. Mauris pulvinar vehicula nibh, vel dictum purus ornare non. Morbi nibh est, aliquam at faucibus non, semper quis leo. Ut suscipit dignissim interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam ultrices arcu vitae dui posuere porttitor. Ut ut nisi metus. Sed justo risus, blandit ut rhoncus vitae, scelerisque ut quam. Nulla faucibus nisl eget elit porttitor euismod. Cras vel ipsum ut ipsum semper facilisis eget vel lectus. In hac habitasse platea dictumst.
<br/><br/>
Aliquam laoreet aliquet facilisis. In tellus turpis, consectetur at sodales ac, blandit sed urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vivamus eu massa et enim ornare dictum ac sit amet leo. Fusce lorem diam, fermentum non venenatis eget, sagittis ut lorem. Maecenas cursus ultrices massa vitae elementum. In eros lectus, cursus ac accumsan eu, accumsan nec ligula. Integer faucibus sodales rutrum.
";

PRODUCTONE;

$catalogTemplate=<<<TEMPLATE
<div class='catalogContentContainer'>
<div class='storeHeader'>
	<div class='title'>TONLINE STORE</div> 
	<div style='position:absolute;top:6px;right:6px;' class='simpleButton viewCartButton'>
	
			View Cart
	</div>
</div>

<!catalogInfo!>
</div>
TEMPLATE;

$productListingTemplate=<<<TEMPLATE
<div class='productListingItemContainer'>
	<!--img style='float:left;' src='../images/logo.png'-->
	<div class='descriptionContainer'>
		<div class='primaryTitle'><!prodCode!> - <b><!name!></b> - <!dollarPrice!><!priceSuffix!></div>
		<div class='annotation'><!annotation!></div>
		<div class='description showFirstLine'>
			<div class='simpleButton shrinkButton15 expandoButtonIdClass shrinked' >&nbsp;</div>
			<div class='innerContainer '><!description!></div>
		</div>
	</div>
	<input type='hidden' name='quantity' value='1'>
	<input type='hidden' name='price' value='<!price!>'>
	<input type='hidden' name='prodCode' value='<!prodCode!>'>
	<input type='hidden' name='name' value='<!name!>'>
	<input type='hidden' name='requiresShipping' value='<!requiresShipping!>'>
	<div class='addToCart simpleButton shrinkButton15 addToCartButton' style='float:right;'>Add to Cart</div>
</div>
TEMPLATE;

$defaultCategoryTemplate=<<<TEMPLATE
<div class='productCategoryContainer'>
	<div class='sideBar'>

	</div>
	
	<div class='header'>
		<div class='titleText'>Uncategorized</div>
	</div>
	
	<div class='productList'>
		<!productList!>
	</div>
</div>
TEMPLATE;

		$directories=array(
			'ROOTFILES'=>array(  //ROOTFILES is the keyword for files that are peered at the base level of the route.


			),
			'productSpecs'=>array(
				'A_Product_Example_One.ini'=>$productOne,
				'B_Product_Example_Two.ini'=>$productTwo
			),
			'catalogDisplayTemplates'=>array(
				'catalogTemplate.html'=>$catalogTemplate,
				'productListingTemplate.html'=>$productListingTemplate
			),
			'categoryTemplates'=>array(
				'categoryDefaultTemplate.html'=>$defaultCategoryTemplate
				),
			'pageFormTemplates'=>array(
				'tmp.ini'=>'a=C'
				),
			'images'=>array(
				'README'=>'this is a placeholder so git will initialize this directory. put images in here',
				'NO_FOLDERS_FILES_ONLY_IN_THIS'=>'Only files can be placed in this directory. No folders/sub-directories'
			)
		);

		$this->_helper->InitPageTypeDirectory($directories);
	}
	
} //end of class