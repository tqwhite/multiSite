<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

public function _initExposeStructures(){

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	$config=$this->getOptions();

	Zend_Registry::set('multiSite', $config['multiSite']);
	//$multiSite=Zend_Registry::get('multiSite');

}

public function _initSession(){
	Zend_Session::start();

	$front = Zend_Controller_Front::getInstance();
//	$front->registerPlugin(new Q\Plugin\Authorize\Check());

}

protected function _initRoutes() {
	//thanks: http://www.devpatch.com/2010/02/load-routes-from-routes-ini-config-file-in-zend-application-bootstrap/
    $front = Zend_Controller_Front::getInstance();
    $router = $front->getRouter();

//$router->removeDefaultRoutes();

	$multiSite=Zend_Registry::get('multiSite');

     $config = new Zend_Config_Ini($multiSite['content']['path'].'/'.SITE_VARIATION.'/routes.ini'); //note, a second parameter, eg, 'production' can be paired with [production] in routes.ini
     $router->addConfig($config,'routes');

//Zend_Debug::dump($front->getRouter());
}

}//end of class

