<?php
 class Q_View_Helper_BuildJqueryReadyCall extends Zend_View_Helper_Abstract {

	 public function buildJqueryReadyCall($contentArray) {
	 
	 
	 	$globalScript='';
		$localScript='';
		
	 if (isset($contentArray) &&
	 	isset($contentArray['superGlobalItems']) &&
	 	isset($contentArray['superGlobalItems']['JS']) &&
	 	is_array($contentArray['superGlobalItems']['JS'])){
	 	
	 	foreach ($contentArray['superGlobalItems']['JS'] as $label=>$data){

			$globalScript.="
			
				//$label
				$data
			
			";
		}
	 	
	 }
		
	 if (isset($contentArray) &&
	 	isset($contentArray['globalItems']) &&
	 	isset($contentArray['globalItems']['JS']) &&
	 	is_array($contentArray['globalItems']['JS'])){
	 	
	 	foreach ($contentArray['globalItems']['JS'] as $label=>$data){

			$globalScript.="
			
				//$label
				$data
			
			";
		}
	 	
	 }
		
	 if (isset($contentArray) &&
	 	isset($contentArray['JS']) &&
	 	is_array($contentArray['JS'])){
	 	
	 	foreach ($contentArray['JS'] as $label=>$data){

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

if (isset($contentArray['layoutJs'])){
	$layoutJs=$contentArray['layoutJs'];
}
else{
	$layoutJs='';
}

if ($globalScript || $localScript){
	$outString="
		<script type='text/javascript'>
			/* <![CDATA[ */
			var initFunction=function(){
				
				$layoutJs
				
				$globalScript
	
				$localScript
	
			}; //end of initFunction()
			
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