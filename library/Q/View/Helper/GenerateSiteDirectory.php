<?php
class Q_View_Helper_GenerateSiteDirectory extends Zend_View_Helper_Abstract {

	public function generateSiteDirectory($urlIniList) {

		return $this->scrapeList($urlIniList);
	}

	private function scrapeList($inData) {
		$outList = array();
		foreach ($inData as $label => $group) {
			foreach ($group as $label2 => $section) {
				foreach ($section['links'] as $label3 => $linkItem) {
					$tmp = $linkItem;
					$tmp['content'] = $this->getContentZone($linkItem);

					$outList[] = $tmp;
				}
			}
		}
		return $outList;
	}

	private function getContentZone($inData) {
		error_log("TQ WARNING: Q_View_Helper_GenerateSiteDirectory->getContentZone() says, NEVER USES SELECTOR \$inData['selector'] ({$inData['selector']})");

		$targetUrl = $inData['url'];
		$handler = curl_init($targetUrl);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$rawPage = curl_exec($handler);
		curl_close($handler);

		if (!$rawPage) {return "$targetUrl is empty or missing<br/>";}

		$matches = array();
		$tmp = preg_match("/(<ul.*ul>)/i", $rawPage, $matches);
		$nodeHtml = $matches[1];

		return $nodeHtml;
	}
}