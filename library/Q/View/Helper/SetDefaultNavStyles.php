<?php
 class Q_View_Helper_SetDefaultNavStyles extends Zend_View_Helper_Abstract {

	 public function setDefaultNavStyles($viewObj) {


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

$viewObj->headScript()->appendScript($dropdownScript); //inlineScript() DOES NOT WORK. The code is never expressed.

$viewObj->headStyle()->appendStyle($dropdownStyles);
$viewObj->headStyle()->appendStyle($ieStyle, array('conditional'=>"lte IE 7"));

	 }

 }