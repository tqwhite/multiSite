<?php
 class Q_View_Helper_ApplyStyles extends Zend_View_Helper_Abstract {

	 public function applyStyles($viewObj, $contentArray) {

		global $thiss;
		$thiss=$viewObj;
		if(!function_exists('applyStyle')){
			function applyStyle($cssString){
				global $thiss;
				$thiss->headStyle()->appendStyle($cssString);
			}
		}
		//$viewObj->headStyle()->appendStyle($contentArray['globalItems']['CSS']['main.css']);

		$cssString=$viewObj->mapServableToHash($contentArray, $contentArray['superGlobalItems']['CSS']);
		array_map('applyStyle', $cssString);
		
		$cssString=$viewObj->mapServableToHash($contentArray, $contentArray['globalItems']['CSS']);
		array_map('applyStyle', $cssString);


	 }

 }