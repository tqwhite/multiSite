<?php
class Q_View_Helper_Fidgets_ConvertToNavList extends Zend_View_Helper_Abstract {
	private $specs;
	public function convertToNavList($content, $specs) {
	$this->specs=$specs;
		$outString='';
		
		
		for ($i=0, $len=count($content); $i<$len; $i++){
			$element=$content[$i];
			$sectionString=$this->makeSection($element);
		
		$outString.="
			<ul class='{$specs['navLocationClass']}'>
				$sectionString
			</ul>
		";
		}
		
		return $outString;
		
		
	}
	
	private function makeSection($contentSection){
	
		$title=$this->view->assembleAnchor($contentSection['title']);
		$links=isset($contentSection['links'])?$contentSection['links']:array();
		$liString='';
		
		for ($i=0, $len=count($links); $i<$len; $i++){
			$element=$links[$i];
			$liString.="<li>".$this->view->assembleAnchor($element)."</li>
			";
		}
		
		if ($liString){
		$outString="
		<li class='has-dropdown'>
			$title
			<ul class='dropdown'>
				$liString
			</ul>
		</li>
		";
		}
		else {
			$outString="<li  class='active'>$title</li>";
		}
		return $outString;
	}
	
	
}
