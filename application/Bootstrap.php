<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

private $initFlag;


public function _initDatabaseStuff(){
	$config=$this->getOptions();
	
	if (isset($config['databaseSpecs'])){
		Zend_Registry::set('databaseSpecs', $config['databaseSpecs']);
	}
	
}

public function _initExposeStructures(){

$front = Zend_Controller_Front::getInstance();

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	$config=$this->getOptions();
	$config['multiSite']['content']['path']=realpath($config['multiSite']['content']['path']);
	$config['multiSite']['rootDomainSegment']=ROOT_DOMAIN_SEGMENT; //this allows links among the multiSites to vary from dev to demo to production

	Zend_Registry::set('multiSite', $config['multiSite']);
	
	Zend_Registry::set('emailSender', $config['emailSender']);

	//$multiSite=Zend_Registry::get('multiSite');
}

public function _initSiteDirectory(){
    $initFlag=((isset($_GET['initIfNeeded']) && $_GET['initIfNeeded']=='true') || (isset($_POST['initIfNeeded']) && $_POST['initIfNeeded']=='true'));
	Zend_Registry::set('initIfNeeded', $initFlag);

    if ($initFlag){ $this->initFlag=true;}

		$multiSite=Zend_Registry::get('multiSite');

		$testFileName=$multiSite['content']['path'].'/tmp';

if ($this->initFlag){
		file_put_contents($testFileName, 'tmp');
		if (!is_readable($testFileName)){
			die("bootstrap says, {$multiSite['content']['path']} is not writeable, hit the chmod or init yourself");
		}
		shell_exec("rm $testFileName");
}

$defaultRoute=<<<defaultRoute
routes._default.route = ''
routes._default.defaults.title='Home'
routes._default.defaults.module = multiPanel		;page type
routes._default.defaults.controller = generate		;always the same
routes._default.defaults.action =container			;always the same

routes.sitemap.route = '/sitemap/'
routes.sitemap.noList = true						;noList==true prevents route from appearing on sitemap
routes.sitemap.defaults.module = siteMap		;page type
routes.sitemap.defaults.controller = generate		;always the same
routes.sitemap.defaults.action =container			;always the same
defaultRoute;


	$directories=array(
		$multiSite['content']['path']=>'',
		$multiSite['content']['path'].'/'.SITE_VARIATION=>array(
			'routes.ini'=>$defaultRoute
		),
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL'=>'',
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/CSS'=>array(
			'siteSpecific.css'=>'.filesInThisDirectoryAreAppliedToYourPages{a:red;}'
		),
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/LAYOUTS'=>array(
			'README'=>'Site specific variations of the files in application/layout override the distribution versions'
		),
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/COMPONENTS'=>array(
			'README'=>'Site specific variations of the files in COMPONENTS are used when a page-specific version is no present'
		),
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/sitemap'=>array(
			'noFilesRequired'=>'This is a placeholder to let you know that you no user files are needed for this page type.'
		)
	);

		foreach ($directories as $directory=>$data){
			$this->makeDir($directory);
			if ($data){
				foreach ($data as $label2=>$data2){
					$filePath=$directory.'/'.$label2;
					$this->makeFile($filePath, $data2);
				}
			}
		}
}

public function _initSession(){
	Zend_Session::start();

	$front = Zend_Controller_Front::getInstance();
//	$front->registerPlugin(new Q\Plugin\Authorize\Check());

}

protected function _initRoutes(){
	//thanks: http://www.devpatch.com/2010/02/load-routes-from-routes-ini-config-file-in-zend-application-bootstrap/
    $front = Zend_Controller_Front::getInstance();
    $router = $front->getRouter();

//$router->removeDefaultRoutes();

	$multiSite=Zend_Registry::get('multiSite');

    $config = new Zend_Config_Ini($multiSite['content']['path'].'/'.SITE_VARIATION.'/routes.ini'); //note, a second parameter, eg, 'production' can be paired with [production] in routes.ini

	Zend_Registry::set('routes', $config->toArray());

    $router->addConfig($config,'routes');

}

private function makeDir($directory){
	if (!is_dir($directory)){
		if ($this->initFlag){
			$cmdString="mkdir $directory";
			echo "<div style='color:green;'>DIRECTORY: creating $directory, result=".shell_exec($cmdString)."</div>";
		}
		else{
			echo "<div style='color:red;'>MISSING DIRECTORY: $directory</div>";
		}
	}
	else{

		if ($this->initFlag){
			echo "<div style='color:gray;'>DIRECTORY: $directory <u>already exists</u></div>";
		}
	}
}


private function makeFile($filePath, $contents){
		if (!is_readable($filePath)){
		if ($this->initFlag){
			echo "<div style='color:green;'>FILE: creating $filePath</siv>";
			file_put_contents($filePath, $contents);}
		else{
			echo "<div style='color:red;'>MISSING FILE: $filePath</div>";
		}

		}
	else{
		if ($this->initFlag){
			echo "<div style='color:gray;'>FILE: $filePath <u>already exists</u></div>";
		}
	}
}

/*
	protected function _initError ()
	{

		$front = Zend_Controller_Front::getInstance();
	$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler());
		$front->throwExceptions( false );
	
	
		if (PHP_SAPI == 'cli')
		{
			$error->setErrorHandlerController ('error');
			$error->setErrorHandlerAction ('cli');
		}
	}
*/
}//end of class

