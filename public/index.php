<?php
ini_set('default_charset', 'utf-8');

define('DOCROOT_DIRECTORY_PATH', realpath(dirname(__FILE__)).'/'); //needed for AJAX apps

if (!defined('APPLICATION_PATH')){
	define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
}

if (!defined('APPLICATION_ENV')){
	if (getenv('APPLICATION_ENV')){
		$environmentName=getenv('APPLICATION_ENV'); //just in case it somehow gets set by (non-existent) previous code
	}
	else{
		$environmentName='production';
	}
    define('APPLICATION_ENV', $environmentName);
}

if (!defined('SITE_VARIATION')){
	if (getenv('SITE_VARIATION')){
		$variationName=getenv('SITE_VARIATION'); //just in case it somehow gets set by (non-existent) previous code
	}
	else{
		$variationName='parent';
	}
    define('SITE_VARIATION', $variationName);
}

// Ensure library/ is on include_path
set_include_path(
	implode(
		PATH_SEPARATOR,
		array(
			realpath(APPLICATION_PATH . '/../library'),
			get_include_path(),
		)
	)
);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();