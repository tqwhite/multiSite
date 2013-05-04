<?php
class Q_View_Helper_ProcessTemplateArray extends Zend_View_Helper_Abstract {
	
	public function processTemplateArray($args) {
		
		\Q\Utils::validateProperties(array(
			'validatedEntity' => $args,
			'source' => __file__,
			'propertyList' => array(
				array(
					'name' => 'sourceData',
					'requiredType' => 'array'
				),
				array(
					'name' => 'itemTemplate',
					'requiredType' => 'string'
				),
				
				array(
					'name' => 'blockTemplate',
					'importance' => 'optional'
				),
				array(
					'name' => 'offset',
					'importance' => 'optional'
				),
				array(
					'name' => 'count',
					'importance' => 'optional'
				),
				array(
					'name' => 'transformations',
					'importance' => 'optional'
				),
				array(
					'name' => 'referenceData',
					'importance' => 'optional'
				)
			)
		));
		
		$sourceData   = \Q\Utils::makeArrayNumericIndexed($args['sourceData']);
		$itemTemplate = $args['itemTemplate'];
		if (!isset($args['blockTemplate'])) {
			$args['blockTemplate'] = '<!productList!>';
			$blockTemplate         = $args['blockTemplate'];
		} else {
			$blockTemplate = $args['blockTemplate'];
		}
		if (!isset($args['offset'])) {
			$args['offset'] = 0;
			$offset         = $args['offset'];
		} else {
			$offset = $args['offset'];
		}
		if (!isset($args['count'])) {
			$args['count'] = 0;
			$count         = $args['count'];
		} else {
			$count = $args['count'];
		}
		if (!isset($args['transformations'])) {
			$args['transformations'] = array();
			$transformations         = $args['transformations'];
		} else {
			$transformations = $args['transformations'];
		}
		if (!isset($args['referenceData'])) {
			$args['referenceData'] = array();
			$referenceData         = $args['referenceData'];
		} else {
			$referenceData = $args['referenceData'];
		}
		
		$itemString      = '';
		$internalGoodies = array();
		
		//for ($i=$offset, $len=min($offset+$count, count($sourceData)); $i<$len; $i++){
		
		$referenceDataTagList = $this->prepareReferenceData($referenceData);
		
		for ($i = 0, $len = count($sourceData); $i < $len; $i++) {
			$itemRec = $sourceData[$i];
			
			$transformationResult = $this->executeTransformations($transformations, array_merge($itemRec, $referenceDataTagList), $referenceDataTagList);
			
			$enhancedItemRec = array_merge($itemRec, $transformationResult, $referenceDataTagList);
			
			$itemString .= $this->replaceItem($itemTemplate, $enhancedItemRec);
		}
		
		$blockData            = array_merge($internalGoodies, $referenceDataTagList, array('productList' => $itemString));
		$transformationResult = $this->executeTransformations($transformations, $blockData, $referenceDataTagList);
		$blockData            = array_merge($blockData, $transformationResult);
		$outString            = $this->replaceItem($blockTemplate, $blockData);
		
		$outArray['outString']            = $outString;
		$outArray['referenceDataTagList'] = $outString;
		return $outArray;
		
	}
	
	private function replaceItem($template, $itemRec) {
		$outString = $template;
		foreach ($itemRec as $label => $data) {
			if (!is_string($data)) {
				continue;
			}
			$outString = str_replace('<!' . $label . '!>', $data, $outString);
		}
		return $outString;
	}
	
	private function executeTransformations($transformations, $itemRec, $referenceDataTagList) {
		
		$outArray = array();
		foreach ($transformations as $label => $data) {
			$outArray[$label] = $data($itemRec, $referenceDataTagList);
		}
		return $outArray;
	}
	
	private function prepareReferenceData($referenceData) {
		//make this recursive someday to allow such references as <!purchaseData:totalsDisplayObject:grandTotal!>
		$outArray = array();
		foreach ($referenceData as $label => $data) {
			if (gettype($data) != 'array') {
				
				$outArray[$label] = $data;
			} else {
				foreach ($data as $label2 => $data2) {
					$replacementTag            = "$label:$label2";
					$outArray[$replacementTag] = $data2;
				}
			}
		}
		return $outArray;
	}
	
	
}