<?php

\Q\Utils::validateProperties(array(
	'validatedEntity'=>$this,
	'source'=>__file__,
	'propertyList'=>array(
		array('name'=>'codeNav'),
		array('name'=>'contentObj')
	)));

//INIT =========================================================================


$contentArray=$this->contentObj->contentArray; //needs to be a property so helpers can see it

if (isset($contentArray['pageTitle.txt'])){
	$this->headTitle($contentArray['pageTitle.txt']);
}
else{
	$request = Zend_Controller_Front::getInstance()->getRequest();
	$this->headTitle($request->getActionName().'/')
		 ->headTitle($request->getControllerName());
}

//DROPDOWN MENU =========================================================================

$dropdownStyles="

	/*
		LEVEL ONE
	*/
	ul.dropdown                         { position: relative; float:right; z-index:1000;}
	ul.dropdown li                      { float: left; zoom: 1; }
	ul.dropdown a:hover		            { color: #000; }
	ul.dropdown a:active                { color: #ffa500; }
	ul.dropdown li a                    { display: block; padding: 4px 8px; border-right: 1px solid #333;
										  color: #222; text-decoration:none; }
	ul.dropdown li:last-child a         { border-right: none; } /* Doesn't work in IE */
	ul.dropdown li.hover,
	ul.dropdown li:hover                { background: #F3D673; color: black; position: relative; }
	ul.dropdown li.hover a              { color: black; }


	/*
		LEVEL TWO
	*/
	ul.dropdown ul 						{ width: 220px; visibility: hidden; position: absolute; top: 100%; left: 0; }
	ul.dropdown ul li 					{ font-weight: normal; background: #f6f6f6; color: #000;
										  border-bottom: 1px solid #ccc; float: none; }

										/* IE 6 & 7 Needs Inline Block */
	ul.dropdown ul li a					{ border-right: none; width: 100%; display: inline-block; }

	/*
		LEVEL THREE
	*/
	ul.dropdown ul ul 					{ left: 100%; top: 0; }
	ul.dropdown li:hover > ul 			{ visibility: visible; }
";

$ieStyle="ul.dropdown ul li					{ display: inline; width: 100%; } ";

$dropdownScript="
$(function(){
    $('ul.dropdown li').hover(function(){

        $(this).addClass('hover');
        $('ul:first',this).css('visibility', 'visible');

    }, function(){

        $(this).removeClass('hover');
        $('ul:first',this).css('visibility', 'hidden');

    });

    $('ul.dropdown li ul li:has(ul)').find('a:first').append(' &raquo; ');

});
";

$this->headScript()->appendScript($dropdownScript); //inlineScript() DOES NOT WORK. The code is never expressed.

$this->headStyle()->appendStyle($dropdownStyles);
$this->headStyle()->appendStyle($ieStyle, array('conditional'=>"lte IE 7"));



$controlsString=$this->unorderedListForSelect(
	$this->contentObj->contentArray['headNav.ini']['menu'],
	array('mainClass'=>'dropdown')
);

$controlsString=$this->translateUrls($controlsString);

$controlsString="
<div style='width:1000px;background:#def;height:50px;margin:0px 0px 20px 0px;'>
	<div style='width:860px;padding-top:20px;margin:0px 70px 20px 70px;'>$controlsString</div>
</div>";

//GLOBAL OVERRIDES (from content dir) =========================================================================
global $thiss;
$thiss=$this;
function applyStyle($cssString){
	global $thiss;
	$thiss->headStyle()->appendStyle($cssString);
}

//$this->headStyle()->appendStyle($contentArray['globalItems']['CSS']['main.css']);
array_map('applyStyle', $contentArray['globalItems']['CSS']);

//MAIN CONTENT SECTION =========================================================================

$panelContentString='';
		$list=$this->contentObj->contentArray['slideshow'];
		foreach ($list as $label=>$element){
			$panelContentString.="<li>$element</li>";
		}
$panelContentString="<ul id='slideshow'>$panelContentString</ul>";

$headBanner=$contentArray['headBanner.html'];

$elementList=$contentArray['images'];

foreach ($elementList as $label=>$data){
	$panelContentString=str_replace("../images/$label", $data, $panelContentString);
	$headBanner=str_replace("images/$label", $data, $headBanner);
}

$panelString="
	<div id='anythingslider' style='width:800px;height:500px;margin-left:90px;margin-top:20px;overflow:hidden;'>
		$panelContentString
	</div>
";


//OUTPUT SECTION =========================================================================

echo $headBanner;
echo $controlsString;
echo $panelString;
