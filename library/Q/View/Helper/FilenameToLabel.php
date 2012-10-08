<?php
 class Q_View_Helper_FilenameToLabel extends Zend_View_Helper_Abstract {

	 public function filenameToLabel($filename) {

	 	$elementArray=explode('_', $filename);
	 	$elementArray=array_splice($elementArray, 1);
		$labelString=implode(' ', $elementArray);
		$labelString=str_replace('.html', '', $labelString);
		return $labelString;
	 }




 }