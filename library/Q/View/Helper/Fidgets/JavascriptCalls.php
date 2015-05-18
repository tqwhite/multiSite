<?php
/*
	Accumulates JS script tag calls from the various page component files.

	first parameter can be either an array list of fileNames or a single filename string

	setDefaultLibrary as first parameter allows omission of the same libraryPath from
	subsequent calls.

	returns finished list of calls when called with no parameters.

	note: Someday I want to make this minify and combine the calls and put them into a 
	single file. That would be so cool.

*/
class Q_View_Helper_Fidgets_JavascriptCalls extends Zend_View_Helper_Abstract {
	
	private $resultString = '';
	private $defaultLibrary = '';
	private $alreadyIn = array();
	
	public function javascriptCalls($fileNameList = '', $libraryPath = '') {
		if ($fileNameList == 'setDefaultLibrary') {
			$this->defaultLibrary = $libraryPath;
			return;
		}
		
		if (!$fileNameList) {
			return $this->resultString;
		}
		
		if (!$libraryPath) {
			$libraryPath = $this->defaultLibrary;
		}
		
		if (is_array($fileNameList)) {
			for ($i = 0, $len = count($fileNameList);$i < $len;$i++) {
				$element = $fileNameList[$i];
				if (isset($this->alreadyIn[$libraryPath . $element])) {
					return;
				}
				$this->alreadyIn[$libraryPath . $element] = true;
				$this->resultString.= "<script src='$libraryPath$element'></script>\n";
			}
		} elseif (is_string($fileNameList)) {
			if (isset($this->alreadyIn[$libraryPath . $fileNameList])) {
				return;
			}
			$this->alreadyIn[$libraryPath . $fileNameList] = true;
			$this->resultString.= "<script src='$libraryPath$fileNameList'></script>\n";
		}
	}
}


