<?php
class Q_View_Helper_Fidgets_ApplyAllMacros extends Zend_View_Helper_Abstract {

	public function applyAllMacros($contentArray, $inString) {
		$outputString = $inString;
		if (isset($contentArray['MACROS'])) {
			$outputString = $this->applyMacros($contentArray['MACROS'], $outputString); //apply page level macros
			
		}

		if (isset($contentArray["globalItems"]['MACROS'])) {
			$outputString = $this->applyMacros($contentArray["globalItems"]['MACROS'], $outputString);
		}

		if (isset($contentArray["superGlobalItems"]['MACROS'])) {
			$outputString = $this->applyMacros($contentArray["superGlobalItems"]['MACROS'], $outputString);
		}

		return $outputString;

	}

	public function applyMacros($macros, $inString) {

		global $thiss;

		$keyList = $this->flattenArray($macros);

		$outString = \Q\Utils::templateReplace(Array('template' => $inString, 'replaceObject' => $keyList));

		return $outString;

	}

	private function flattenArray($array, $prefix = '') {
		$result = array();

		foreach ($array as $key => $value) {
			$new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

			if (is_array($value)) {
				$result = array_merge($result, $this->flattenArray($value, $new_key));
			} else {
				$new_key = preg_replace('/.ini|.html|.php/', '', $new_key);

				$result[$new_key] = $value;
			}
		}

		return $result;
	}

}
