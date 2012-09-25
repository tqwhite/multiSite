<?php

class Q_Controller_Base extends Zend_Controller_Action
{
	private $moduleDirectoryPath;
	private $multiSite;
	private $baseDir;
	private $defaultLayoutPath;
	private $layoutDirectoryPath;
	private $layoutSiteDirectoryName;
	private $siteDirectoryPath;
	private $actualLayoutFullPath;
	private $layoutName;
	private $contentDirectoryPath;
	private $globalItemsDirectoryPath;

	private $contentObj;
	private $layoutContainer;

    public function init($args){

		if (isset($args['controllerType']) && $args['controllerType']=='cms'){

			$initFlag=Zend_Registry::get('initIfNeeded');

			if ($initFlag){
				$this->executeInit();
				die("<div style='font-weight:bold;margin-top:20px;'>Default directories/files initialization complete.</div><div style='margin-top:20px;'>Check it out: <a href='".'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL']."'>". $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL']."</a></div>");
			}

			$front = Zend_Controller_Front::getInstance();
			$this->moduleDirectoryPath=$front->getModuleDirectory();

			$this->multiSite=Zend_Registry::get('multiSite');

			$this->baseDir=$this->getBaseDir().'/';

			$this->defaultLayoutPath=Zend_Layout::getMvcInstance()->getLayoutPath();
			$this->routeName=Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
			$this->layoutSiteDirectoryName=SITE_VARIATION.'/';

			$this->siteDirectoryPath=$this->getSiteDirectoryPath();
			$this->layoutDirectoryPath=$this->genLayoutContentDir();
			$this->contentDirectoryPath=$this->getContentDir();
			$this->globalItemsDirectoryPath=$this->getGlobalItemsDir();
			$this->setVariationLayout('layout'); //set default

			//echo Zend_Layout::getMvcInstance()->getLayoutPath(); exit;
		}
    }

    private function getSiteDirectoryPath(){
    	$directory=$this->baseDir.SITE_VARIATION;
    	return $this->checkDir($directory);
    }

    private function getBaseDir(){
    	$directory=$this->multiSite['content']['path'].'/';
    	return $this->checkDir($directory);
    }

    private function getContentDir(){
    	$directory=$this->siteDirectoryPath.'/'.$this->routeName.'/';
    	return $this->checkDir($directory);
    }

    private function getGlobalItemsDir(){
    	$directory=$this->siteDirectoryPath.'/_GLOBAL/';
    	return $this->checkDir($directory);
    }

    private function genLayoutContentDir(){
    	$directory=$this->siteDirectoryPath.'/_GLOBAL/LAYOUTS/';
    	return $this->checkDir($directory);
    }

    private function genCssDir(){
    	$directory=$this->siteDirectoryPath.'/_GLOBAL/CSS/';
    	return $this->checkDir($directory);
    }

    private function checkDir($directory){

    	if (is_dir($directory)){
    		return realpath($directory);
    	}
    	else{
    		echo("controller_base::getContentDir says, $directory does not exist 3");
    	}
    }

    public function getCodeNav($method){
    	$codeNav=array();
		$codeNav['controller']=$method;
		$codeNav['layoutShortPath']=$this->_helper->layout()->getLayout();
		$codeNav['actualLayoutFullPath']=$this->actualLayoutFullPath;
		$codeNav['contentDirPath']=$this->contentObj->contentDirPath;
		$codeNav['routeName']=$this->routeName;
		$codeNav['moduleDirectoryPath']=$this->moduleDirectoryPath;
		$codeNav['contentDirectoryPath']=$this->contentDirectoryPath;
		$codeNav['globalItemsDirectoryPath']=$this->globalItemsDirectoryPath;
		$codeNav['zendactualLayoutFullPath']=$this->defaultLayoutPath;
		return $codeNav;
    }

	protected function setVariationLayout($layoutName){
	/*
		Sets the layout in the SITE_VARIATION folder if it exists
		or in the folder named in multiSite.layout.defaultDirName (in application.ini)
	*/
		$multiSite=$this->multiSite;
		$defaultDirName=$multiSite['layout']['defaultDirName'].'/';

		$siteLayout=$this->layoutDirectoryPath.$layoutName;
		$defaultlayout=$this->defaultLayoutPath.$defaultDirName.$layoutName;

		if ($this->checkLayoutExists($siteLayout)){
			$this->actualLayoutFullPath=$siteLayout;
			Zend_Layout::getMvcInstance()->setLayoutPath($this->layoutDirectoryPath);
			$this->_helper->layout()->setLayout($layoutName);
		}
		elseif ($this->checkLayoutExists($defaultlayout)){
			$this->actualLayoutFullPath=$defaultlayout;
			Zend_Layout::getMvcInstance()->setLayoutPath($this->defaultLayoutPath);
			$this->_helper->layout()->setLayout($defaultDirName.$layoutName);
		}
		else{
			die("Q_Controller_Base says, '$layoutName' does not exist in either '{$this->layoutSiteDirectoryName}' or '$defaultDirName'");
		}

		$this->layoutName=$layoutName;
	}

	private function checkLayoutExists($layout){
		$viewSuffix=$this->getHelper('viewRenderer')->getViewSuffix();

		$filePath=$layout.'.'.$viewSuffix;
		if (file_exists($filePath)){
			return true;
		}
		return false;
	}

	public function __get($property){
		switch($property){
			case 'contentObj':
				$this->contentObj=new Q\Helpers\FileContent(array(
					'contentDirPath'=>$this->contentDirectoryPath,
					'globalItemsDirectoryPath'=>$this->globalItemsDirectoryPath,
					'employer'=>$this,
					'validatorName'=>'validateContentStructure'
					));

				return $this->contentObj;
				break;
			case 'layoutContainer':

				$this->layoutContainer=new Q\Helpers\FileContent(array('contentDirPath'=>$this->layoutDirectoryPath));
				return $this->layoutContainer;
				break;

		}
	}

	public function __set($property, $value){
		$this->$property=$value;
	}

	private function executeInit(){
		if (method_exists($this, 'initializeContentDirectory')){
			$this->initializeContentDirectory();
		}
		else{
			die("No directory initiallization available");
		}
	}
}


