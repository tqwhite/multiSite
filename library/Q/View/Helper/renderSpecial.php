<?php
 class Q_View_Helper_RenderSpecial extends Zend_View_Helper_Abstract {

	 public function renderSpecial($name, $employer, $directory='') {

		if ($directory){
			$mvcInstance=Zend_Layout::getMvcInstance();
			$holdDir=$employer->getScriptPaths();
			$employer->setScriptPath($directory);

			$outString=$employer->render($name);

			$employer->setScriptPath($holdDir);
		}
		else{
			$outString=$employer->render($name);
		}
	return $outString;

	 }


 }