<?php
class Q_View_Helper_Fidgets_AssembleAnchor extends Zend_View_Helper_Abstract {


public function assembleAnchor($anchorPair){
		if (is_string($anchorPair)){
			$anchorPair=array('title'=>$anchorPair);
		}
		
		$anchorPair['title']=isset($anchorPair['title'])?$anchorPair['title']:"NO TITLE SPECIFIED";
		$anchorPair['url']=isset($anchorPair['url'])?$anchorPair['url']:"#";
		
		$outString="<a href='{$anchorPair['url']}'>{$anchorPair['title']}</a>";
		
		return $outString;
		
	}
	
	}