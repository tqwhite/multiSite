<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

public function _initExposeStructures(){

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

	$config=$this->getOptions();
	$config['multiSite']['content']['path']=realpath($config['multiSite']['content']['path']);

	Zend_Registry::set('multiSite', $config['multiSite']);
	//$multiSite=Zend_Registry::get('multiSite');
}

public function _initSiteDirectory(){
    $initFlag=($_GET['initIfNeeded']=='true' || $_POST['initIfNeeded']=='true');
	Zend_Registry::set('initIfNeeded', $initFlag);

    if ($initFlag){

		$multiSite=Zend_Registry::get('multiSite');

		$testFileName=$multiSite['content']['path'].'/tmp';

		file_put_contents($testFileName, 'tmp');
		if (!is_readable($testFileName)){
			die("bootstrap says, {$multiSite['content']['path']} is not writeable, hit the chmod or init yourself");
		}
		shell_exec("rm $testFileName");

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
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/CSS'=>'',
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/LAYOUTS'=>'',
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/sitemap'=>'',
		$multiSite['content']['path'].'/'.SITE_VARIATION.'/sitemap/noFilesRequired'=>'',
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

		echo "base site init complete<BR>";
    }

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

	Zend_Registry::set('routes', $config->toArray());

    $router->addConfig($config,'routes');

}

private function makeDir($directory){
	if (!is_dir($directory)){
		$cmdString="mkdir $directory";
		echo "<div style='color:green;'>DIRECTORY: creating $directory, result=".shell_exec($cmdString)."</div>";
	}
	else{

		echo "<div style='color:gray;'>DIRECTORY: $directory <u>already exists</u></div>";
	}
}


private function makeFile($filePath, $contents){
		if (!is_readable($filePath)){
			echo "<div style='color:green;'>FILE: creating $filePath</siv>";
			file_put_contents($filePath, $contents);
		}
	else{
		echo "<div style='color:gray;'>FILE: $filePath <u>already exists</u></div>";
	}
}

}//end of class
