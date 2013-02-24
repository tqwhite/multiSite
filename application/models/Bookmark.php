<?php

class Application_Model_Bookmark extends Application_Model_Base
{

	const entityName="Bookmark";

	public function __construct(){
		parent::__construct();
	}

	static function validate($inData){

		$errorList=array();

		$name='uri';
		$datum=isset($inData[$name])?$inData[$name]:'';

		if (strlen($datum)<4){
			$errorList[]=array($name, "URI is too short");
		}
		else{
			$testObj=new \Application_Model_Bookmark();
			$testObj=$testObj->getByFieldName('uri', $datum);
			if (isset($testObj)){
				$errorList[]=array($name, "URI already in database", 'dbDupeFound', $testObj);
			}
		}

		return $errorList;
	}

	static function formatDetail($inData, $outputType){

		if ($inData->refId){ //used to preface with isset($inData->refId) but that no longer returns correctly?
			$outArray=array(
				'refId'=>$inData->refId,
				'uri'=>$inData->uri,
				'shortId'=>$inData->shortId,
				'anchor'=>$inData->anchor,
				'accessCount'=>$inData->accessCount,
				'created'=>$inData->created,
				'categoryList'=>\Application_Model_Category::formatOutput($inData->categoryNodes, $outputType)
				);
		}
		else{
			$outArray=array();
		}

			return $outArray;
	}

	public function makeNew($objArray, $suppressFlush){

		$outArray=array();

		if (!isset($suppressFlush)){ $suppressFlush=false; }

//==


			$this->generate();

			foreach ($objArray as $label=>$data){

				if ($label=='categoryList'){
				
					$categoryNotes=$this->hookUpCategories($data);
				
				}
				else{
					$this->entity->$label=$data;
				}
			}

			$this->entityManager->persist($this->entity);

			if (!$suppressFlush){
				$this->entityManager->flush();
			}

			$outArray[]=$this->entity;
		

//==
		return $outArray;
	}
	
	private function hookUpCategories($inArray){
	
			$outArray=array();
	
		for ($i=0, $len=count($inArray); $i<$len; $i++){
			$element=$inArray[$i];
			
			$category=new \Application_Model_Category();
			$category->generate();
			
			foreach ($element as $label=>$data){
				$category->entity->$label=$data;
			}
			
			
			$entityClassName="Q\\Entity\\BookmarkCategoryNode";
			$node=new $entityClassName();
		
			$node->category=$category->entity;
			$node->bookmark=$this->entity;
			$this->entityManager->persist($node);
						
		}
	
	
	}

}

