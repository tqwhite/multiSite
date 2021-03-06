<?php
 class Q_Controller_Action_Helper_InitPageTypeDirectory extends Zend_Controller_Action_Helper_Abstract {

 	private $routeDirectory;

 	private $alreadyExistsList;

	 public function direct($directories) {
	 
echo "<div style='background:gray;margin:5px 0px;' tqdebug>".__FILE__.", line ".__LINE__."</div>";  
		$this->alreadyExistsList=array();

		$this->routeDirectory=$this->getRouteDirectory();

		foreach ($directories as $label=>$data){

			if ($label=='ROOTFILES'){
				$directory=$this->routeDirectory;
				$this->makeDir($directory);
			}
			else{
				$directory=$this->routeDirectory.$label;
				$this->makeDir($directory);
			}

			if ($data){
				foreach ($data as $label2=>$data2){
					if (isset($this->alreadyExistsList[$directory]) && $directory!=$this->routeDirectory){echo "DIRECTORY: $directory already exists<BR/>";continue;}
					$filePath=$directory.'/'.$label2;
					$this->makeFile($filePath, $data2);
				}
			}
		}

	 }

private function makeDir($directory){
	if (!is_dir($directory)){
		$cmdString="mkdir $directory; chmod 777 $directory;";
		echo "<div style='color:green;'>DIRECTORY: creating $directory, result=".shell_exec($cmdString)."</div>";
	}
	else{
		$this->alreadyExistsList[$directory]=true;
		echo "<div style='color:gray;'>DIRECTORY: $directory <u>already exists</u></div>";
	}
}

private function getRouteDirectory(){
	$this->multiSite=Zend_Registry::get('multiSite');
	$baseDir=realpath($this->multiSite['content']['path']).'/';
	$siteDirectory=$baseDir.SITE_VARIATION.'/';
	$routeName=Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
    return $siteDirectory.$routeName.'/';
}


private function makeFile($filePath, $contents){
		if (!is_readable($filePath)){
			file_put_contents($filePath, $contents);
			$result=shell_exec("chmod 777 $filePath;");
			echo "<div style='color:green;'>FILE: creating $filePath, result=$result</div>";
		}
	else{
		echo "<div style='color:gray;'>FILE: $filePath <u>already exists</u></div>";
	}
}
 } //end of class