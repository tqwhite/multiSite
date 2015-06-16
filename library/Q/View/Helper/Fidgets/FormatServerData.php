<?php
class Q_View_Helper_Fidgets_FormatServerData extends Zend_View_Helper_Abstract {
	private $specs;
	public function formatServerData($contentArray) {
		$routeName = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();

		$serverData = '';
		$parameters = array('processContentSourceRouteName' => $routeName);

		if (isset($contentArray['globalItems']['_PARAMETERS'])) {
			$parameters = array_merge($parameters, $contentArray['globalItems']['_PARAMETERS']);
		}

		if (isset($contentArray['_PARAMETERS'])) {
			if (!is_array($parameters)) {
				$parameters = array();
			}
			foreach ($contentArray['_PARAMETERS'] as $label => $data) {
				if (is_array($parameters[$label])) {
					$parameters[$label] = array_merge($parameters[$label], $data);
				} else {
					$parameters[$data] = $data;
				}

			}
		}

		if (count($parameters) > 0) {
			$parameters = \Q\Utils::htmlEntities($parameters);
			$parameters = json_encode($parameters);
			$serverData.= "<div class='serverData' id='parameters' style='display:none;'>$parameters</div>";
		}
		if ($serverData == '') {
			$serverData = "<!-- no serverData sent for this page type -->";
		}
		return $serverData;
	}

}
