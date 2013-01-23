<?php
namespace Q\Helpers;
/*
This class looks at a directory and generates a recursive assoc array of its contents.

For a text file, the term 'contents' means the actual text contained in the file. For array
directory, contents means another assoc array reflecting the files inside the directory.

For images, 'contents' means a path to the file that can be accessed by image manipulation
routines.

This class also maintains a list for each type of file, text, directory and image, containing
all the paths to all the files it finds. This is mainly valuable for image files, which
can be accessed to create links from servable space to their real location.

*/

class FileContent{

private $contentDirPath;
private $employer;
private $validatorName;

private $contentArray;

private $ignoreList;

private $hashDir;

public function __construct($args){
	$this->initIgnoreList();
	$this->args=$args;
	if ($args['contentDirPath']){
		$this->contentDirPath=$args['contentDirPath'];
		}
	if ($args['globalItemsDirectoryPath']){
		$this->globalItemsDirectoryPath=$args['globalItemsDirectoryPath'];
		}
	if ($args['employer']){
		$this->employer=$args['employer'];
		}
	if ($args['validatorName']){
		$this->validatorName=$args['validatorName'];
		}

	$multiSite=\Zend_Registry::get('multiSite');
	$this->hashDir=$multiSite['server']['hashImageDir'].'/';

	if (!$this->hashDirIsWritable()){
		die('Q\Helpers\FileContent::__construct() says, {$this->hashDir} is not writable. Do a chmod.');
	}

}

private function hashDirIsWritable(){

	$result=shell_exec("touch {$this->hashDir}/tmp");
	$result=shell_exec("ls {$this->hashDir}/tmp");
	if ($result){
		shell_exec("rm {$this->hashDir}/tmp");
		return true;
	}
	//else
		return false;

}

private function initIgnoreList(){
	$this->ignoreList=array();

	$name='.DS_Store'; $this->ignoreList[$name]=true;
	$name='.'; $this->ignoreList[$name]=true;
	$name='..'; $this->ignoreList[$name]=true;
}


public function startFileExamination($contentDirPath){

	if(!$contentDirPath){$contentDirPath=$this->contentDirPath;}
	else{ $this->contentDirPath=$contentDirPath;}
	if(!$contentDirPath){die("Q\\Helpers\\FileContent\\get() says, \$contentDirPath is undefined and that is bad<br/>");}

	$workingFileArray=array();
	$finishArray=array();

	$outArray=$this->digIntoDir($contentDirPath);
	return $outArray;
}

private function digIntoDir($path){
	$outArray=array();
	$dirList=$this->getDirectories($path);
	$fileList=$this->getFiles($path);

	for ($i=0, $len=count($fileList); $i<$len; $i++){
		$element=$fileList[$i];
		$baseName=basename($element);

		if (isset($this->ignoreList[$baseName])){
			continue;
		}
		else{
			$outArray[$baseName]=$this->getContents($element); //also creates servable has when appropo
		}

	}

	for ($i=0, $len=count($dirList); $i<$len; $i++){
		$element=$dirList[$i];
		$outArray[basename($element)]=$this->digIntoDir($element);
	}

	return $outArray;
}





private function getDirectories($path){
	if (!is_dir($path)){die("Q\\Helpers\\FileContent\\get() says, $path is not a directory");}
	return $this->getDirectoryContents($path, 'dir');
}

private function getFiles($path){
	if (!is_dir($path)){die("Q\\Helpers\\FileContent\\getArray() says, $path is not a directory");}
	return $this->getDirectoryContents($path, 'file');
}

private function getDirectoryContents($path, $kind){

	$list=scandir($path); //default is alpha ascending
	$outList=array();

	for ($i=0, $len=count($list); $i<$len; $i++){
	$fileName=$list[$i];
	if (isset($this->ignoreList[$fileName])){continue;}
	$fullPath=$path.'/'.$fileName;

	switch ($kind){
		case 'dir':
			if (is_dir($fullPath)){$outList[]=$fullPath;}
		break;
		case 'file':
			if (!is_dir($fullPath)){$outList[]=$fullPath;}
		break;
	}

}

	return $outList;
}

private function getContents($filePath){
	$mimeArray=array();

	$nameParts=explode('.', basename($filePath));
	if (!isset($nameParts[1])){$nameParts[1]='default';}

	switch ($nameParts[1]/*file extension*/){
		default:
			$contents=file_get_contents($filePath);
			$file_info = new \finfo(FILEINFO_MIME);
			$mimeType = $file_info->buffer($contents);
			$mimeArray=explode('/', $mimeType);
		break;
		case 'ini':
			$test=new \Zend_Config_Ini($filePath);
			$contents=$test->toArray();
		break;
	}

	if (isset($mimeArray[0]))
		{$switchVar=$mimeArray[0];}
	else
		{$switchVar='default';}

//echo "$switchVar=$filePath<br/>"; //sometimes images arrive in weird condition, this helps.

	switch ($switchVar){
		default:
			return $contents;
		break;
		case 'image':
			return $this->putToMediaHash($filePath);
		break;
		case 'application':
			return "$filePath is not a valid file";
		break;
	}
}

private function putToMediaHash($filePath){

	$hash=md5($filePath);
	$hashPath=$this->hashDir.$hash;
	$hashUrl=str_replace(DOCROOT_DIRECTORY_PATH, '',  $hashPath);
	if (!is_readable($hashPath)){
		$linkCommand="ln -s $filePath $hashPath";
		shell_exec($linkCommand);
	}

	return $hashUrl;
}


public function __get($property){
	switch($property){
		case 'contentArray':
			if (isset($this->contentArray)){return $this->contentArray;}
			$this->contentArray=$this->startFileExamination($this->contentDirPath);
			$this->contentArray['globalItems']=$this->startFileExamination($this->globalItemsDirectoryPath);
			$this->promoteGlobals();

			$employer=$this->employer;
			$validatorName=$this->validatorName;

			if ($this->employer && $this->validatorName && method_exists($employer, $validatorName)){
				$employer->$validatorName($this->contentArray);
			}
			return $this->contentArray;
			break;

	}
}

public function __set($property, $value){
	$this->$property=$value;
}

public function promoteGlobals(){
		$list=$this->contentArray['globalItems']['COMPONENTS'];
		foreach ($list as $label=>$data){
			if (!isset($this->contentArray[$label])){
					$this->contentArray[$label]=$data;
			}
		}
}

}//end of class


