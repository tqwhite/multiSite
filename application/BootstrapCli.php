<?php
class BootstrapCli extends Zend_Application_Bootstrap_Bootstrap
{ 

//remember: the commmand line application file itself needs to set 
//  define('APPLICATION_ENV', 'commandLine');
//a good example is in ../scripts/doctrine.php

public function _initExposeStructures(){

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	$config=$this->getOptions();

	Zend_Registry::set('config', $config);

	//$multiSite=Zend_Registry::get('multiSite');
}

}

