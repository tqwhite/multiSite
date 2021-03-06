<?php
 class Q_View_Helper_FilenameToLabel extends Zend_View_Helper_Abstract {

	 public function filenameToLabel($filename) {

		$extensionArray=array();
		preg_match('/\\.(.*)$/', $filename, $extensionArray);
		$extension=isset($extensionArray[0])?$extensionArray[0]:'';

	 	$elementArray=explode('_', $filename);
	 	
	 	if (count($elementArray)==1){
	 	$elementArray=explode('_', 'a_'.$filename);
	 	}
	 	
	 	$elementArray=array_splice($elementArray, 1); //starts with 1 to omit sequencing prefix
		$labelString=implode(' ', $elementArray);
		$labelString=str_replace($extension, '', $labelString);
		return $labelString;
	 }

 }