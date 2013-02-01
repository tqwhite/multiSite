<?php
 class Q_View_Helper_MapServableToHash extends Zend_View_Helper_Abstract {

	 public function mapServableToHash($contentArray, $inString) {
	 
	 	if (!is_array($contentArray)){ return $inString;}
	 
	 	$outString=$inString;
	 	
		 if (is_array($contentArray['images'])){
			foreach ($contentArray['images'] as $label=>$data){
				$outString=str_replace("../images/$label", $data, $outString);
			}	
		}
	
		 if (is_array($contentArray['elements'])){
			foreach ($contentArray['elements'] as $label=>$data){
				$outString=str_replace("../elements/$label", $data, $outString);
			}	
		}

		return $outString;
	}

}