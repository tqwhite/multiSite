<?php

class UploadController extends Q_Controller_Base
{
	private $errorList=array();
	
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function fileAction()
    {
        $outArray=array();
		foreach ($_FILES as $fileName=>$fileData){

			$outFileName=$this->moveOneFile($fileName, $fileData);
			$outArray[$fileName]=$outFileName;
		}

		$status=(count($this->errorList)>0)?false:true;
		
		$this->_helper->json(array(
			'status'=>$status,
			'messages'=>$this->errorList,
			'data'=>$outArray
		));
    }
    

private function moveOneFile($fileName, $fileData){
$multiSite=\Zend_Registry::get('multiSite');
$writeableTmpDir=$multiSite['server']['hashImageDir'].'/';
$tmpDir=$writeableTmpDir."uploadedFiles/";

if (!is_dir($tmpDir)){
	exec("mkdir $tmpDir;");
}

$sessionNamespace = new Zend_Session_Namespace(); //this is the default namespace, no need for complexity

if (!$sessionNamespace->fileNameUniqueifier){
	$sessionNamespace->fileNameUniqueifier=time();
}

$outFileName=md5($sessionNamespace->fileNameUniqueifier.$fileData['name']);
$outFilePath=$tmpDir.$outFileName;
		$completionStatus=false; //assume error
		
	//VALIDATION THANKS TO contributor 'CertaiN ' on http://www.php.net//manual/en/features.file-upload.php#114004	
	try {
	
		// Undefined | Multiple Files | $_FILES Corruption Attack
		// If this request falls under any of them, treat it invalid.
		if (
			!isset($_FILES[$fileName]['error']) ||
			is_array($_FILES[$fileName]['error'])
		) {
			throw new Exception('Invalid parameters.');
		}

		// Check $_FILES[$fileName]['error'] value.
		switch ($_FILES[$fileName]['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new Exception('No file sent.');
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new Exception('File is too large; Limit is '.str_replace('M', ' MegaBytes', ini_get(upload_max_filesize)));
			default:
				throw new Exception('Unknown errors:'.$_FILES[$fileName]['error']);
		}

		// You should also check filesize here. 
		if ($_FILES[$fileName]['size'] > 10000000) { //TEN MEG? ARBITRARY?
			throw new Exception('Exceeded filesize limit. Only ten magabytes allowed');
		}

		// DO NOT TRUST $_FILES[$fileName]['mime'] VALUE !!
		// Check MIME Type by yourself.
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		if (false === $ext = array_search(
			$finfo->file($_FILES[$fileName]['tmp_name']),
			array(
				'jpg' => 'image/jpeg',
				'png' => 'image/png',
				'gif' => 'image/gif',
			),
			true
		)) {
			throw new Exception('Invalid file format.');
		}

		// You should name it uniquely.
		// DO NOT USE $_FILES[$fileName]['name'] WITHOUT ANY VALIDATION !!
		// On this example, obtain safe unique name from its binary data.
		if (!move_uploaded_file(
			$_FILES[$fileName]['tmp_name'],
			$outFilePath
		)) {
			throw new Exception('Failed to move uploaded file.');
		}

		$completionStatus=true;
		


	} catch (Exception $e) {

		$this->errorList[]=array($fileName, $e->getMessage());
		$completionStatus=false;
	}

	if ($completionStatus){
		return $outFilePath;
	}
	else{

		return false;
	}
}

}



