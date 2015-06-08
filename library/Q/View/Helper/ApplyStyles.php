<?php
class Q_View_Helper_ApplyStyles extends Zend_View_Helper_Abstract {

	private $mediaQuerySpecs;

	public function applyStyles($viewObj, $contentArray, $options = '') {
		global $thiss;
		$thiss = $viewObj;
		if (!function_exists('applyStyle')) {

			function applyStyle($cssString) {
				global $thiss;
				$thiss->headStyle()->appendStyle($cssString);
			}

		}

		if (!$options) {
			$options = array();
		}
		if (!isset($options['skipSuperGlobal'])) {
			$options['skipSuperGlobal'] = false;
		}

		$this->mediaQuerySpecs = $this->getMediaQuerySpecs($contentArray);

		if (isset($contentArray['superGlobalItems']['CSS']) && !$options['skipSuperGlobal']) {
			$cssComponentArray = $this->separateMediaQueries($contentArray['superGlobalItems']['CSS']);
			$cssString = $viewObj->mapServableToHash($contentArray, $cssComponentArray['CSS']);
			$mediaQueries = $this->constructMediaQueries($cssComponentArray['_mediaQueries']);
			$finalArray = array_merge($cssString, $mediaQueries);
			$finalString=implode("\n\n", $finalArray);
				$viewObj->headStyle()->appendStyle($finalString);
		}

		if (isset($contentArray['globalItems']['CSS'])) {
			$cssComponentArray = $this->separateMediaQueries($contentArray['globalItems']['CSS']);
			$cssString = $viewObj->mapServableToHash($contentArray, $cssComponentArray['CSS']);
			$mediaQueries = $this->constructMediaQueries($cssComponentArray['_mediaQueries']);
			$finalArray = array_merge($cssString, $mediaQueries);
			$finalString=implode("\n\n", $finalArray);
				$viewObj->headStyle()->appendStyle($finalString);
		}

		if (isset($contentArray['CSS'])) {
			$cssComponentArray = $this->separateMediaQueries($contentArray['CSS']);
			$cssString = $viewObj->mapServableToHash($contentArray, $cssComponentArray['CSS']);
			$mediaQueries = $this->constructMediaQueries($cssComponentArray['_mediaQueries']);

			$finalArray = array_merge($cssString, $mediaQueries);
			$finalString=implode("\n\n", $finalArray);
				$viewObj->headStyle()->appendStyle($finalString);
		}

	}

	private function separateMediaQueries($cssArray) {
		$cssArrayOut = array();
		$mediaQueryArrayOut = array();

		foreach ($cssArray as $label => $data) {
			if (preg_match('/^_mediaQueries/', $label)) {
				$mediaQueryArrayOut[$label] = $data;
			} else {
				$cssArrayOut[$label] = $data;
			}
		}

		return array('CSS' => $cssArrayOut, '_mediaQueries' => $mediaQueryArrayOut);
	}

	private function constructMediaQueries($mediaQueryArray) {
		$bySizeArray = array();

		$mediaQuerySpecs = $this->mediaQuerySpecs;

		foreach ($mediaQueryArray as $filePath => $querySpec) {
			$cssSelector = (isset($querySpec['_cssSelector'])) ? $querySpec['_cssSelector'] : '';

			if (!$cssSelector) {
				throw new Exception("Q/View/Helper/ApplyStyles.php says, Missing '_cssSelector' in $filePath");
			}

			foreach ($querySpec as $breakpointName => $propertyValue) {
				if ($breakpointName != '_cssSelector') {
					if (!isset($mediaQuerySpecs[$breakpointName])) {
						throw new Exception("Q/View/Helper/ApplyStyles.php says, Missing media query breakpoint '$breakpointName' in $filePath");
					} else {
						$bySizeArray[$breakpointName] = (isset($bySizeArray[$breakpointName])) ? $bySizeArray[$breakpointName] : array();
						$bySizeArray[$breakpointName][] = array('_cssSelector' => $cssSelector, 'classInfo' => $propertyValue);
					}
				}
			}
		}

		if (count($bySizeArray) == 0) {
			return array();
		}
		$outArray = array();

		foreach ($bySizeArray as $breakpointName => $classList) {
			$classListString = '';
			$breakPointQuery = $mediaQuerySpecs[$breakpointName];
			foreach ($classList as $unused => $classItem) {
				if (preg_match('/\w/', $classItem['classInfo'])) {
					$classListString.= "
					{$classItem['_cssSelector']} {
						{$classItem['classInfo']}
					}
				";
				}
			}
			$outArray[$breakPointQuery['sequence']] = "
					@media {$breakPointQuery['criterion']} {
						$classListString
					}
				";
		}

		ksort($outArray);

		return $outArray;
	}

	private function getMediaQuerySpecs($contentArray) {
		$mediaQueryBreakpoints = array();

		$mediaQueryBreakpoints['default'] = array();
		$mediaQueryBreakpoints['default']['sequence'] = "1";
		$mediaQueryBreakpoints['default']['criterion'] = "only screen";

		$mediaQueryBreakpoints['phoneNarrow'] = array();
		$mediaQueryBreakpoints['phoneNarrow']['sequence'] = "1";
		$mediaQueryBreakpoints['phoneNarrow']['criterion'] = "only screen and (min-width: 37.5em)";

		$mediaQueryBreakpoints['phoneWide'] = array();
		$mediaQueryBreakpoints['phoneWide']['sequence'] = "2";
		$mediaQueryBreakpoints['phoneWide']['criterion'] = "only screen and (min-width: 66.7em)";

		$mediaQueryBreakpoints['tabletNarrow'] = array();
		$mediaQueryBreakpoints['tabletNarrow']['sequence'] = "3";
		$mediaQueryBreakpoints['tabletNarrow']['criterion'] = "only screen and (min-width: 75.0em)";

		$mediaQueryBreakpoints['desktopSmall'] = array();
		$mediaQueryBreakpoints['desktopSmall']['sequence'] = "4";
		$mediaQueryBreakpoints['desktopSmall']['criterion'] = "only screen and (min-width: 90.1em)";

		$mediaQueryBreakpoints['tabletWide'] = array();
		$mediaQueryBreakpoints['tabletWide']['sequence'] = "5";
		$mediaQueryBreakpoints['tabletWide']['criterion'] = "only screen and (min-width: 100.1em)";

		$mediaQueryBreakpoints['desktopMedium'] = array();
		$mediaQueryBreakpoints['desktopMedium']['sequence'] = "6";
		$mediaQueryBreakpoints['desktopMedium']['criterion'] = "only screen and (min-width: 120.0em)";

		$mediaQueryBreakpoints['desktopLarge'] = array();
		$mediaQueryBreakpoints['desktopLarge']['sequence'] = "7";
		$mediaQueryBreakpoints['desktopLarge']['criterion'] = "only screen and (min-width: 140.0em)";

		$superGlobal = \Q\Utils::getDottedPath($contentArray, "superGlobalItems,siteSpecs.ini,mediaQueryBreakpoints", ',');
		$global = \Q\Utils::getDottedPath($contentArray, "globalItems,siteSpecs.ini,mediaQueryBreakpoints", ',');
		$local = \Q\Utils::getDottedPath($contentArray, "siteSpecs.ini,mediaQueryBreakpoints", ',');

		$superGlobal = (is_array($superGlobal)) ? $superGlobal : array();
		$global = (is_array($global)) ? $global : array();
		$local = (is_array($local)) ? $local : array();

		$outArray = array_merge($superGlobal, $global, $local);

		return count($outArray) ? $outArray : $mediaQueryBreakpoints;
	}

}
