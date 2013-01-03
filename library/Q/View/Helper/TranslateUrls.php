<?php
 class Q_View_Helper_TranslateUrls extends Zend_View_Helper_Abstract {

	 public function translateUrls($rawUrl) {
		$multiSite=Zend_Registry::get('multiSite');
		$substitutionList=array(
			array(
				'pattern'=>'<!baseDomain!>',
				'replace'=>$_SERVER['HTTP_HOST']
			),
			array(
				'pattern'=>'<!rootDomainSegment!>',
				'replace'=>$multiSite['rootDomainSegment']
			)
		);

		$outString=$rawUrl;
		foreach ($substitutionList as $data){
			$outString=str_replace($data['pattern'], $data['replace'], $outString);
		}
	return $outString;

	 }


 }