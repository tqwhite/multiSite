<?php
 class Q_View_Helper_ShowCodeNav extends Zend_View_Helper_Abstract {

	 public function showCodeNav($codeNav) {
		return "
			<!--
			routeName: {$codeNav['routeName']}	<br/>
			module: {$codeNav['moduleDirectoryPath']}<br/>
			controller: {$codeNav['controller']}<br/>
			content: {$codeNav['contentDirPath']}<br/>
			layout: {$codeNav['actualLayoutFullPath']}<br/>
			-->";

	 }


 }