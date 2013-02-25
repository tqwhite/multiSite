<?php

class Application_Model_Category extends Application_Model_Base
{

	const entityName="Category";

	public function __construct(){
		parent::__construct();
	}

	static function validate($inData){

		$errorList=array();

// 		$name='uri';
// 		$datum=isset($inData[$name])?$inData[$name]:'';
// 
// 		if (strlen($datum)<4){
// 			$errorList[]=array($name, "URI is too short");
// 		}
// 		else{
// 			$testObj=new \Application_Model_Bookmark();
// 			$testObj=$testObj->getByFieldName('uri', $datum);
// 			if (isset($testObj)){
// 				$errorList[]=array($name, "URI already in database", 'dbDupeFound', $testObj);
// 			}
// 		}

		return $errorList;
	}

	static function formatDetail($inData, $outputType){

		if ($inData->category){ 
			$outArray=array(
				'name'=>$inData->category->name,
				'refId'=>$inData->category->refId
				);
		}
		else{
			$outArray=array(
				'name'=>$inData->name,
				'refId'=>$inData->refId
				);
		}

			return $outArray;
	}
	
	

}

