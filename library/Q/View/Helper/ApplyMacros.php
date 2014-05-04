<?php
 class Q_View_Helper_ApplyMacros extends Zend_View_Helper_Abstract {

	 public function applyMacros($macros, $inString) {

		global $thiss;

		$keyList=$this->flattenArray($macros);
		
		$outString=\Q\Utils::templateReplace(Array(
			'template'=>$inString,
			'replaceObject'=>$keyList
		));
		
		return $outString;

	 }
	 
	 private function flattenArray($array, $prefix = '')
	{
		$result = array();

		foreach ($array as $key => $value)
		{
			$new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

			if (is_array($value))
			{
				$result = array_merge($result, $this->flattenArray($value, $new_key));
			}
			else
			{
				$new_key=preg_replace('/.ini|.html|.php/','', $new_key);
		
				$result[$new_key] = $value;
			}
		}

		return $result;
	}

 }