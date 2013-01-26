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
				$errorList[]=array($name, "URI already in database");
			}
		}

		return $errorList;
	}

	static function formatDetail($inData, $outputType){
		if (isset($inData->refId) && $inData->refId){
			$outArray=array(
				'refId'=>$inData->refId,
				'uri'=>$inData->uri,
				'shortId'=>$inData->shortId,
				'anchor'=>$inData->anchor,
				'accessCount'=>$inData->accessCount,
				'created'=>$inData->created
				);
		}
		else{
			$outArray=array();
		}


			return $outArray;
	}


	public function getUserByShortId($shortId){
		$query = $this->entityManager->createQuery('SELECT u from Q\Entity\Bookmark u WHERE u.shortId = :shortId');
		$query->setParameters(array(
			'shortId' => $shortId
		));
		$shortIds = $query->getResult();
		return $shortIds[0];
	}

}

