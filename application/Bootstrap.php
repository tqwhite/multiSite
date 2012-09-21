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

    if ($initFlag){

		$multiSite=Zend_Registry::get('multiSite');

		$testFileName=$multiSite['content']['path'].'/tmp';

		file_put_contents($testFileName, 'tmp');
		if (!is_readable($testFileName)){
			die("bootstrap says, {$multiSite['content']['path']} is not writeable, hit the chmod or init yourself");
		}
		shell_exec("rm $testFileName");

		$directory = $multiSite['content']['path'];
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION;
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL';
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/CSS';
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION.'/_GLOBAL/LAYOUTS';
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$filePath = realpath($multiSite['content']['path']).'/'.SITE_VARIATION.'/routes.ini';
		if (!is_readable($filePath)){
			echo "creating $filePath<BR>";
			$routeInit="
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
			";
			file_put_contents($filePath, $routeInit);
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION.'/_default';
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION.'/sitemap';
		if (!is_dir($directory)){
			echo "creating $directory<BR>";
			$cmdString="mkdir $directory";
			echo "making $directory result=".shell_exec($cmdString)."<BR>";
		}

		$directory = $multiSite['content']['path'].'/'.SITE_VARIATION.'/sitemap/noFilesRequired';
		if (!is_readable($filePath)){
			echo "creating $filePath<BR>";
			$routeInit="placeholder";
			file_put_contents($filePath, $routeInit);
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

}//end of class

