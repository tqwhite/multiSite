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

class FileContent {
	
	private $contentDirPath;
	private $employer;
	private $validatorName;
	
	private $contentArray;
	
	private $ignoreList;
	
	private $hashDir;
	
	public function __construct($args) {
		
		$this->initIgnoreList();
		$this->args = $args;
		if ($args['superGlobalItemsDirectoryPath']) {
			$this->superGlobalItemsDirectoryPath = $args['superGlobalItemsDirectoryPath'];
		}
		if ($args['contentDirPath']) {
			$this->contentDirPath = $args['contentDirPath'];
		}
		if ($args['globalItemsDirectoryPath']) {
			$this->globalItemsDirectoryPath = $args['globalItemsDirectoryPath'];
		}
		if ($args['employer']) {
			$this->employer = $args['employer'];
		}
		if ($args['validatorName']) {
			$this->validatorName = $args['validatorName'];
		}
		
		$multiSite = \Zend_Registry::get('multiSite');
		$this->hashDir = $multiSite['server']['hashImageDir'] . '/';
		
		if (!$this->hashDirIsWritable()) {
			die('Q\Helpers\FileContent::__construct() says, {$this->hashDir} is not writable. Do a chmod.');
		}
		
		
	}
	
	private function hashDirIsWritable() {
		
		$result = shell_exec("touch {$this->hashDir}/tmp");
		$result = shell_exec("ls {$this->hashDir}/tmp");
		if ($result) {
			shell_exec("rm {$this->hashDir}/tmp");
			return true;
		}
		else
		return false;
		
		
	}
	
	private function initIgnoreList() {
		$this->ignoreList = array();
		
		$name = '.DS_Store';
		$this->ignoreList[$name] = true;
		$name = '.';
		$this->ignoreList[$name] = true;
		$name = '..';
		$this->ignoreList[$name] = true;
	}
	
	public function startFileExamination($contentDirPath) {
		
		if (!$contentDirPath) {
			$contentDirPath = $this->contentDirPath;
		} else {
			$this->contentDirPath = $contentDirPath;
		}
		if (!$contentDirPath) {
			die("Q\\Helpers\\FileContent\\get() says, \$contentDirPath is undefined and that is bad<br/>");
		}
		
		$workingFileArray = array();
		$finishArray = array();
		
		$outArray = $this->digIntoDir($contentDirPath);
		return $outArray;
	}
	
	private function digIntoDir($path) {
		$outArray = array();
		$dirList = $this->getDirectories($path);
		$fileList = $this->getFiles($path);
		
		for ($i = 0, $len = count($fileList);$i < $len;$i++) {
			$element = $fileList[$i];
			$baseName = basename($element);
			
			if (isset($this->ignoreList[$baseName])) {
				continue;
			} else {
				$outArray[$baseName] = $this->getContents($element);
			}
			
			
		}
		
		for ($i = 0, $len = count($dirList);$i < $len;$i++) {
			$element = $dirList[$i];
			$outArray[basename($element) ] = $this->digIntoDir($element);
			
			if (is_array($outArray[basename($element) ])) {
				foreach ($outArray[basename($element) ] as $label2 => $data2) {
					if (is_array($data2) && !preg_match('/\.ini/', $label2)) {
						
						foreach ($data2 as $label3 => $data3) {
							$outArray[basename($element) ]["$label2/$label3"] = $data3;
						}
						unset($outArray[basename($element) ][$label2]);
					}
					
					
				}
			}
			
			
		}
		
		return $outArray;
	}
	
	private function getDirectories($path) {
		if (!is_dir($path)) {
			die("Q\\Helpers\\FileContent\\get() says, $path is not a directory");
		}
		return $this->getDirectoryContents($path, 'dir');
	}
	
	private function getFiles($path) {
		if (!is_dir($path)) {
			die("Q\\Helpers\\FileContent\\getArray() says, $path is not a directory");
		}
		return $this->getDirectoryContents($path, 'file');
	}
	
	private function getDirectoryContents($path, $kind) {
		
		$list = scandir($path); //default is alpha ascending
		$outList = array();
		
		for ($i = 0, $len = count($list);$i < $len;$i++) {
			$fileName = $list[$i];
			if (isset($this->ignoreList[$fileName])) {
				continue;
			}
			$fullPath = $path . '/' . $fileName;
			
			if (substr(basename($fullPath), 0, 1) == '.') {
				continue;
			} //OSX keep sticking .DS_Store everywhere
			
			switch ($kind) {
				case 'dir':
					if (is_dir($fullPath)) {
						$outList[] = $fullPath;
					}
				break;
				case 'file':
					if (!is_dir($fullPath)) {
						$outList[] = $fullPath;
					}
				break;
			}
			
			
		}
		
		return $outList;
	}
	
	private function getContents($filePath) {
		$mimeArray = array();
		
		$nameParts = explode('.', basename($filePath));
		if (!isset($nameParts[1])) {
			$nameParts[1] = 'default';
		}
		
		switch ($nameParts[1] /*file extension*/
		) {
			default:
				$contents = file_get_contents($filePath);
				$file_info = new \finfo(FILEINFO_MIME);
				$mimeType = $file_info->buffer($contents);
				$mimeArray = explode('/', $mimeType);
			break;
			case 'ini':
				$test = new \Zend_Config_Ini($filePath);
				$contents = $test->toArray();
			break;
		}
		
		if (isset($mimeArray[0])) {
			switch ($mimeArray[0]) {
				case 'application':
					$switchVar = $mimeArray[1];
				break;
				default:
					$switchVar = $mimeArray[0];
				break;
			}
		} else {
			$switchVar = 'default';
		}
		
		//echo "$switchVar=$filePath<br/>"; //sometimes images arrive in weird condition, this helps.
		
		switch ($switchVar) {
			default:
				return $contents;
			break;
			case 'pdf; charset=binary':
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
	
	private function putToMediaHash($filePath) {
		
		$neuteredFilePath = str_replace('/', '_', $filePath);
		$hash = $neuteredFilePath . '_' . md5($filePath);
		$maxLen = 254;
		if (strlen($hash) > $maxLen) {
			$replacementText = '_NAMETOOLONG_';
			$replacementLen = strlen($replacementText);
			
			$origLen = strlen($hash);
			$excessLen = $origLen - $maxLen + $replacementLen;
			$startDiscard = floor($origLen / 2 - $excessLen / 2);
			$middle = substr($hash, $startDiscard, $excessLen);
			
			$hash = str_replace($middle, $replacementText, $hash);
			$finalLen = strlen($hash);
		}
		$hash = $hash . '_' . strlen($hash);
		$hashPath = $this->hashDir . $hash;
		$hashUrl = str_replace(DOCROOT_DIRECTORY_PATH, '', $hashPath);
		if (!is_readable($hashPath)) {
			$linkCommand = "ln -s $filePath $hashPath";
			shell_exec($linkCommand);
		}
		
		return $hashUrl;
	}
	
	public function __get($property) {
		switch ($property) {
			case 'contentArray':
				if (isset($this->contentArray)) {
					return $this->contentArray;
				}
				$this->contentArray = $this->startFileExamination($this->contentDirPath);
				$this->contentArray['superGlobalItems'] = $this->startFileExamination($this->superGlobalItemsDirectoryPath);
				$this->contentArray['globalItems'] = $this->startFileExamination($this->globalItemsDirectoryPath);
				$this->promoteGlobals();
				
				$employer = $this->employer;
				$validatorName = $this->validatorName;
				

				if ($this->employer && $this->validatorName && method_exists($employer, $validatorName) && (!isset($this->args['validationDoesNotApply']) || !$this->args['validationDoesNotApply'])) {
					$employer->$validatorName($this->contentArray);
				}
				return $this->contentArray;
			break;
				
				
		}
	}
	
	public function __set($property, $value) {
		$this->$property = $value;
	}
	
	public function promoteGlobals() {
		
		$this->promoteComponents();
		
		$this->promoteOneGlobal('IMAGES', 'images'); //HACKERY: this lower case upper case dichotomy is horrible, they should all be the same. I promist to rewrite someday, tqii
		$this->promoteOneGlobal('ATTACHMENTS', 'ATTACHMENTS');
		$this->promoteOneGlobal('COMPONENTS', 'COMPONENTS');
		$this->promoteOneGlobal('MACROS', 'MACROS');
		$this->promoteOneGlobal('CSS', 'CSS');
		$this->promoteOneGlobal('JS', 'JS');
		
		//at present, _PARAMETERS is merged in default/layout.phtml
		//CSS does the promotion by inserting into page in correct order
		
		
	}
	
	private function promoteOneGlobal($globalFolderName, $localFolderName) {
		
		if (isset($this->contentArray['globalItems']) && isset($this->contentArray['globalItems'][$globalFolderName]) && isset($this->contentArray['globalItems'])) {
			
			$list = $this->contentArray['globalItems'][$globalFolderName];
			if (!isset($this->contentArray[$localFolderName]) || !is_array($this->contentArray[$localFolderName])) {
				$this->contentArray[$localFolderName] = array();
			}
			foreach ($list as $label => $data) {
				
				if (!isset($this->contentArray[$localFolderName][$label])) {
					
					$this->contentArray[$localFolderName][$label] = $data;
				}
			}
		}
		
		if (isset($this->contentArray['superGlobalItems']) && isset($this->contentArray['superGlobalItems'][$globalFolderName]) && isset($this->contentArray['superGlobalItems'])) {
			
			$list = $this->contentArray['superGlobalItems'][$globalFolderName];
			if (!isset($this->contentArray[$localFolderName]) || !is_array($this->contentArray[$localFolderName])) {
				$this->contentArray[$localFolderName] = array();
			}
			foreach ($list as $label => $data) {
				
				if (!isset($this->contentArray[$localFolderName][$label])) {
					
					$this->contentArray[$localFolderName][$label] = $data;
				}
			}
		}
	}
	
	private function promoteComponents() {
		
// 		These are different because I think of them as macros, not servable
// 		probably not valid and can be rewritten when the upper/lower dichotomy is fixed
		
		if (!isset($this->contentArray['globalItems'])) {
			die("site globalItems directory is missing");
		}
		
		$list = $this->contentArray['globalItems']['COMPONENTS'];
		foreach ($list as $label => $data) {
			if (!isset($this->contentArray[$label])) {
				$this->contentArray[$label] = $data;
			}
		}
		
		if (isset($this->contentArray['superGlobalItems']['COMPONENTS'])) {
			$list = $this->contentArray['superGlobalItems']['COMPONENTS'];
			foreach ($list as $label => $data) {
				if (!isset($this->contentArray[$label])) {
					$this->contentArray[$label] = $data;
				}
			}
		}
	}
	
	
} //end of class

