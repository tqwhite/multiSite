<?php

class Q_Controller_Base extends Zend_Controller_Action
{
	private $defaultLayoutPath;
	private $layoutDirectoryPath;
	private $layoutSiteDirectoryName;
	private $setVariationLayout;
	private $layoutFullPath;
	private $contentDirectoryPath;
	private $layoutName;

	private $contentObj;
	private $layoutContainer;

    public function init($args)
    {
		$this->defaultLayoutPath=Zend_Layout::getMvcInstance()->getLayoutPath();
		$this->layoutSiteDirectoryName=SITE_VARIATION.'/';
		$this->routeName=Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();

		$multiSite=Zend_Registry::get('multiSite');

		if (isset($args['controllerType']) && $args['controllerType']=='cms'){
			$this->contentDirectoryPath=$multiSite['content']['path'].'/'.SITE_VARIATION.'/'.$this->routeName.'/';
			$this->layoutDirectoryPath=$this->genLayoutContentDir();
		}
		$this->setVariationLayout('layout'); //set default
    }

    private function genLayoutContentDir(){
		$multiSite=Zend_Registry::get('multiSite');
    	return $multiSite['content']['path'].'/'.SITE_VARIATION.'/LAYOUTS/';
    }

    public function getCodeNav($method){
    	$codeNav=array();
		$codeNav['controller']=$method;
		$codeNav['layoutShortPath']=$this->_helper->layout()->getLayout();
		$codeNav['layoutFullPath']=$this->layoutFullPath;
		$codeNav['contentDirPath']=$this->contentObj->contentDirPath;
		$codeNav['routeName']=$this->routeName;
		$codeNav['contentDirectoryPath']=$this->contentDirectoryPath;
		return $codeNav;
    }

	protected function setVariationLayout($layoutName){
	/*
		Sets the layout in the SITE_VARIATION folder if it exists
		or in the folder named in multiSite.layout.defaultDirName (in application.ini)
	*/
		$multiSite=Zend_Registry::get('multiSite');
		$defaultDirName=$multiSite['layout']['defaultDirName'].'/';

		$siteLayout=$this->layoutDirectoryPath.$layoutName;
		$defaultlayout=$this->defaultLayoutPath.$defaultDirName.$layoutName;

		if ($this->checkLayoutExists($siteLayout)){
			$this->layoutFullPath=$siteLayout;
			Zend_Layout::getMvcInstance()->setLayoutPath($this->layoutDirectoryPath);
			$this->_helper->layout()->setLayout($layoutName);
		}
		elseif ($this->checkLayoutExists($defaultlayout)){
			$this->layoutFullPath=$defaultlayout;
			Zend_Layout::getMvcInstance()->setLayoutPath($this->defaultLayoutPath);
			$this->_helper->layout()->setLayout($defaultDirName.$layoutName);
		}
		else{
			die("Q_Controller_Base says, '$layoutName' does not exist in either '{$this->layoutSiteDirectoryName}' or '$defaultDirName'");
		}

		$this->layoutName=$layoutName;
		$this->layoutDirectoryPath=$this->genLayoutContentDir();
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
}



