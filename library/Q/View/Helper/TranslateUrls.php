<?php
 class Q_View_Helper_TranslateUrls extends Zend_View_Helper_Abstract {

	 public function translateUrls($rawUrl) {

		$substitutionList=array(
			array(
				'pattern'=>'<!baseDomain!>',
				'replace'=>$_SERVER['HTTP_HOST']
			)
		);

		$outString=$rawUrl;
		foreach ($substitutionList as $data){
			$outString=str_replace($data['pattern'], $data['replace'], $outString);
		}

	return $outString;

	 }


 }