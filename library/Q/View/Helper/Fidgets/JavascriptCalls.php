<?php
/*
	Accumulates JS script tag calls from the various page component files.

Originally, this routine generated a series of <script> calls to specified javascript
files.

Later, when RequireJS was added, it was updated to create

	require(['blah.js', 'blah2.js'], callbackFunctionName);
	
But, since this was complicated, the possibility of needing to create a call to a different
AMD (or other) dependency manager was considered. Consequently, thought the default is
still <script> tags, this can be used with a template data structure to create pretty much
anything. EG,

	$demoTemplate=array(
		'itemTemplate'=>"'<!filePath!>', ",
		'cleanupRegEx'=>"/, $/",
	'wrapper'=>"require([<!itemString!>]);"
	);

Only itemTemplate is required.

The template parameter can also be a string, eg,

	$demoTemplateString="<script src='<!filePath!>'>";

The class is accessed with a call such as

	$this->javascriptCalls($control, $parameter);
	
$control can be
	setDefaultLibrary
	setTemplate
	emitJavascript

or, the path (within setDefaultLibrary, if present) of a javascript file.

A template can be set either with setTemplate or as the second paraemter for emitJavascript.

*/
class Q_View_Helper_Fidgets_JavascriptCalls extends Zend_View_Helper_Abstract {

	private $resultString = '';
	private $defaultLibrary = '';
	private $alreadyIn = array();
	private $template = array();
	private $callList = array();

	public function javascriptCalls($control, $parameter = '') {

		if ($control == 'setDefaultLibrary') {
			$this->defaultLibrary = $parameter;
			return;
		}

		if ($control == 'setTemplate') {
			$this->setTemplate($parameter);
			return;
		}

		if ($control == 'emitJavascript') {
			if ($parameter) {
				$this->setTemplate($parameter);
			}
			$this->render();
			if (isset($this->template['cleanupRegEx'])) {
				$this->resultString = preg_replace($this->template['cleanupRegEx'], '', $this->resultString);
			}
			if (isset($this->template['wrapper'])) {
				$this->resultString = preg_replace('/<!itemString!>/', $this->resultString, $this->template['wrapper']);
			}
			return $this->resultString;
		}

		$this->callList[] = array('fileNameList' => $control, 'filePath' => $parameter);

	}

	private function setTemplate($parameter) {

		if (is_string($parameter)) {
			$this->template['itemTemplate'] = $parameter;
		}
		if (is_array($parameter)) {
			if (!isset($parameter['itemTemplate'])) {
				throw new \Exception('Q\\View\Helpers\JavascriptCalls says, No itemTemplate on templateArray');
			}
			$this->template = $parameter;
		}
	}

	private function render() {
		for ($i = 0, $len = count($this->callList);$i < $len;$i++) {
			$element = $this->callList[$i];
			$this->addFileToResult($element['fileNameList'], $element['filePath']);
		}
	}

	private function addFileToResult($fileNameList, $filePath) {

		if (!$filePath) {
			$filePath = $this->defaultLibrary;
		}

		if (isset($this->template['itemTemplate'])) {
			$template = $this->template['itemTemplate'];
		} else {
			$template = "<script src='<!filePath!>'></script>\n";
		}

		if (is_array($fileNameList)) {
			for ($i = 0, $len = count($fileNameList);$i < $len;$i++) {
				$element = $fileNameList[$i];
				if (isset($this->alreadyIn[$filePath . $element])) {
					return;
				}
				$this->alreadyIn[$filePath . $element] = true;
				$this->resultString.= str_replace('<!filePath!>', "$filePath$element", $template);
			}
		} elseif (is_string($fileNameList)) {
			if (isset($this->alreadyIn[$filePath . $fileNameList])) {
				return;
			}
			$this->alreadyIn[$filePath . $fileNameList] = true;
			$this->resultString.= str_replace('<!filePath!>', "$filePath$fileNameList", $template);
		}
	}

}
