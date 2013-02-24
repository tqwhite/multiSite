<?php
 class Q_View_Helper_BuildJqueryReadyCall extends Zend_View_Helper_Abstract {

	 public function buildJqueryReadyCall($contentArray) {
	 
	 
	 	$globalScript='';
		$localScript='';
		
	 if (isset($contentArray) &&
	 	isset($contentArray['globalItems']) &&
	 	isset($contentArray['globalItems']['JS']) &&
	 	is_array($contentArray['globalItems']['JS'])){
	 	
	 	$globalScript='';
	 	
	 	foreach ($contentArray['globalItems']['JS'] as $label=>$data){
			$globalScript.="
			
				//$label
				$data
			
			";
		}
	 	
	 }


	if (isset($contentArray['jqueryReadyScript.js']) && $contentArray['jqueryReadyScript.js']){

		$localScript=$contentArray['jqueryReadyScript.js'];

	}
	
	
	if (gettype($contentArray)=='string'){
		$localScript=$contentArray;
	}

if ($globalScript || $localScript){
	$outString="
		<script type='text/javascript'>
			/* <![CDATA[ */
			var initFunction=function(){
	
				$globalScript
	
				$localScript
	
	
			};
			
			if (typeof(steal)=='function'){
				steal.then(initFunction);
			}
			else{
				$(document).ready(initFunction);
			}
			
			/* ]]> */
		</script>
		";
	}
	else{
		$outString='';
	}

	return $outString;

	 }


 }