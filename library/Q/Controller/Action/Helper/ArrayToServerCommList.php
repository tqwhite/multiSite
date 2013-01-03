<?php
 class Q_Controller_Action_Helper_ArrayToServerCommList extends Zend_Controller_Action_Helper_Abstract {

	 public function direct($jsArrayName, $inData) {
		$outArray=array();
		foreach ($inData as $label=>$data){
			foreach ($data as $label2=>$data2){
				$outArray[]=array(
					'fieldName'=>"{$jsArrayName}[$label][$label2]",
					'value'=>$data2
				);
			}
		}
		return $outArray;
    }

 }