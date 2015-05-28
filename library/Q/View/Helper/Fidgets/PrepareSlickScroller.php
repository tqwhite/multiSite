<?php
 class Q_View_Helper_Fidgets_PrepareSlickScroller extends Zend_View_Helper_Abstract {

	 public function prepareSlickScroller($contentElement, $containerId) {
		$outString='';
		foreach ($contentElement as $label=>$data){
			$outString.=$data;
		}
		
		$outString="
			<div id='$containerId'>
				$outString
			</div>
		";
		
		return $outString;

	 }

 }