<?php
 class Q_View_Helper_GenerateSiteDirectory extends Zend_View_Helper_Abstract {

	 public function generateSiteDirectory($urlIniList) {

		return $this->scrapeList($urlIniList);
	 }



    private function scrapeList($inData){
		$outList=array();
    	foreach ($inData as $label=>$group){
			foreach ($group as $label2=>$section){
				foreach($section['links'] as $label3=>$linkItem){
						$tmp=$linkItem;
					$tmp['content']=$this->getContentZone($linkItem);

					$outList[]=$tmp;
					}
			}
		}
		return $outList;
    }

	private function getContentZone($inData){
		$targetUrl=$inData['url'];
		$handler=curl_init($targetUrl);

		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$rawPage=curl_exec($handler);
		curl_close($handler);
		$dom = new Zend_Dom_Query($rawPage);
		if (isset($inData['selector'])){
		$results = $dom->query($inData['selector']);


		foreach ($results as $node) {
			$nodeHtml=$results->getDocument()->saveXML($node);
			if ($nodeHtml){break;} //verify that it found something
			}
		}
		else{
			$nodeHtml=$rawPage;
		}
		return $nodeHtml;
	}
 }