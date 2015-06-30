<?php

class Q_View_Helper_Fidgets_ConvertToNavList extends Zend_View_Helper_Abstract {

	private $specs;
	private $accessOtherPageSupport;

	public function convertToNavList($content, $specs, $contentArray, $accessOtherPageSupport) {

		$this->specs = $specs;
		$this->accessOtherPageSupport = $accessOtherPageSupport;

		$outString = '';

		for ($i = 0, $len = count($content);$i < $len;$i++) {
			$element = $content[$i];
			$sectionString = $this->makeSection($element, $specs, $contentArray);

			$outString.= $sectionString;
		}

		return $outString;

	}

	private function makeSection($contentSection, $specs, $contentArray) {

		if (isset($contentSection['autoSections'])) {
			$linkSection = array();
			for ($i = 0, $len = count($contentSection['autoSections']);$i < $len;$i++) {
				$element = $contentSection['autoSections'][$i];
				
				$remotePageSpec=$this->parseRemotePageCall($element);
				$folderPanelArray=$this->getOtherPageContentArray($remotePageSpec);


				if (!count($folderPanelArray)) {
					continue;
				}
				$section = $this->makeContentAnchorList($folderPanelArray, $remotePageSpec);

				$linkSection = array_merge_recursive($linkSection, $section);

			};

			$completeSection = array('links' => $linkSection, 'title' => $contentSection['title']);

			$outString = $this->makeSection($completeSection, $specs, $contentArray);

		}

		else {

			if (isset($contentSection['url'])) {
				$title = $this->view->assembleAnchor($contentSection);
			} else {
				$title = $this->view->assembleAnchor($contentSection['title']);
			}

			$links = isset($contentSection['links']) ? $contentSection['links'] : array();
			$liString = '';

			for ($i = 0, $len = count($links);$i < $len;$i++) {
				$element = $links[$i];
				$liString.= "<li>" . $this->view->assembleAnchor($element) . "</li>
			";
			}

			if ($liString) {
				$outString = "
				<li class='has-dropdown'>
					$title
					<ul class='dropdown'>
						$liString
					</ul>
				</li>
				";
			} else {
				$outString = "<li  class='active'>$title</li>";
			}
		}
		$outString = "
			<ul class='{$specs['navLocationClass']}'>
				$outString
			</ul>
		";
		return $outString;
	}
	
	private function parseRemotePageCall($element){
		preg_match("/<!(.*?):(.*?)!>/", $element, $match);
		return array(
			'routeName'=>$match[1],
			'folderName'=>$match[2]
		);
	}

	private function makeContentAnchorList($folderPanelArray, $remotePageSpec) {
		$outArray = array();
		$routeName=$remotePageSpec['routeName'];
		
		$isCurrentPage=($this->accessOtherPageSupport['routeName']==$routeName);
		
		if ($routeName==Zend_Registry::get('defaultRouteName')){
			$routeName='';
		}
		
		foreach ($folderPanelArray as $label => $data) {
			preg_match("/id='(.*?)'/i", $data, $idMatch);
			preg_match("/title='(.*?)'/i", $data, $titleMatch);

			if (isset($idMatch[1])) {
				$id = $idMatch[1];
				$title = isset($titleMatch[1]) ? $titleMatch[1] : preg_replace('/[^a-zA-Z0-9]/', ' ', $idMatch[1]);
			} else {
				die("ConvertToNavList.makeContentAnchorList() says, directory $folderName has item $label that has no 'id' attribute and is included in a 'autoSection' call in headNav.ini");
			}
			
			if ($isCurrentPage){
			$outArray[] = array('url' => "#id=$id", 'title' => $title);
			}
			else{
			$outArray[] = array('url' => "/$routeName?id=$id", 'title' => $title);
			}

		}

		return $outArray;

	}

	private function getOtherPageContentArray($remotePageSpec) {

		// 	'contentDirPath' => $this->contentDirectoryPath,
		// 	'superGlobalItemsDirectoryPath' => $this->superGlobalItemsDirectoryPath,
		// 	'globalItemsDirectoryPath' => $this->globalItemsDirectoryPath,
		// 	'employer' => $this,
		// 	'validatorName' => 'validateContentStructure',
		// 	'routeName'=>$this->routeName

		// $tmp = $this->accessOtherPageSupport;
		// $tmp['employer'] = ''; //'employer' is a Zend class and it won't dump()
		// \Q\Utils::dumpWeb($tmp, "tmp");
		
		$routeName=$remotePageSpec['routeName'];
		
		$this->accessOtherPageSupport['contentDirPath'] = preg_replace('/' . $this->accessOtherPageSupport['routeName'] . '$/', $routeName, $this->accessOtherPageSupport['contentDirPath']);
		$foreignContentObj = new Q\Helpers\FileContent($this->accessOtherPageSupport);
		$foreignContentArray=$foreignContentObj->contentArray;
		$foreignContentFolderArray=(isset($foreignContentArray[$remotePageSpec['folderName']]))?$foreignContentArray[$remotePageSpec['folderName']]:array();
		return $foreignContentFolderArray;

	}

}
