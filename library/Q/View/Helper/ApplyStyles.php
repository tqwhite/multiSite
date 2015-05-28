<?php
 class Q_View_Helper_ApplyStyles extends Zend_View_Helper_Abstract {

	 public function applyStyles($viewObj, $contentArray, $options) {

		global $thiss;
		$thiss=$viewObj;
		if(!function_exists('applyStyle')){
			function applyStyle($cssString){
				global $thiss;
				$thiss->headStyle()->appendStyle($cssString);
			}
		}
		
		if (!isset($options)){ $options=array();}
		if (!$options['skipSuperGlobal']){$options['skipSuperGlobal']=false; }
		
		//$viewObj->headStyle()->appendStyle($contentArray['globalItems']['CSS']['main.css']);

		if (isset($contentArray['superGlobalItems']['CSS']) && !$options['skipSuperGlobal']){
			$cssString=$viewObj->mapServableToHash($contentArray, $contentArray['superGlobalItems']['CSS']);
			array_map('applyStyle', $cssString);
		}
		
		if (isset($contentArray['globalItems']['CSS'])){
			$cssString=$viewObj->mapServableToHash($contentArray, $contentArray['globalItems']['CSS']);
			array_map('applyStyle', $cssString);
		}
		
		if (isset($contentArray['CSS'])){
			$cssString=$viewObj->mapServableToHash($contentArray, $contentArray['CSS']);
			array_map('applyStyle', $cssString);
		}

	 }

 }