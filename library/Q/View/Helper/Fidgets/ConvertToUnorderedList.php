<?php
class Q_View_Helper_Fidgets_ConvertToUnorderedList extends Zend_View_Helper_Abstract {
	private $specs;
	public function convertToUnorderedList($content, $specs) {
	$this->specs=$specs;
		$outString='';
		foreach ($content as $label=>$data){
			$listElement=$this->makeListElement($label, $data);
			$listElement="
				<li>
					$listElement
				</li>
			";
			$outString.=$listElement;
		}
		
		$outString="
			<ul>
				$outString
			</ul>
		";
		return $outString;
		
		
	}
	
	private function makeListElement($label, $data){
		$result="
		<ul>
			<li class='label'>$label</li>
			<li class='data'>$data</li>
		</ul>
		";
		return $result;
	}
	
	
}
