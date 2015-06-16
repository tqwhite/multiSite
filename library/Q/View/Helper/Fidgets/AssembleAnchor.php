<?php
class Q_View_Helper_Fidgets_AssembleAnchor extends Zend_View_Helper_Abstract {


public function assembleAnchor($anchorPair){

		if (is_string($anchorPair)){
			$anchorPair=array('title'=>$anchorPair);
		}
		
		$attributeString='';
		if (isset($anchorPair['attributes'])){
			foreach ($anchorPair['attributes'] as $attributeName=>$attributeValue){
				$attributeString.="$attributeName='$attributeValue' ";
			}
		}
		
		$anchorPair['title']=isset($anchorPair['title'])?$anchorPair['title']:"NO TITLE SPECIFIED";
		$anchorPair['url']=isset($anchorPair['url'])?$anchorPair['url']:"#";
		
		$outString="<a href='{$anchorPair['url']}'$attributeString>{$anchorPair['title']}</a>";
		
		return $outString;
		
	}
	
	}