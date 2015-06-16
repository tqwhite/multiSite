<?php
class Q_View_Helper_Fidgets_ConvertToUnorderedList extends Zend_View_Helper_Abstract {
	private $specs;
	public function convertToUnorderedList($content, $specs) {
	
// 	echo "<gr/>Q_View_Helper_Fidgets_ConvertToUnorderedList has not been used before. Remove this
// 		message if you use it. Or, feel free to throw it away if you are cleaning it up. 
// 		It does work, though.<hr/>";
	
		$this->specs = $specs;
		$outString = '';
		foreach ($content as $label => $data) {
			$listElement = $this->makeListElement($label, $data);
			$listElement = "
				<li>
					$listElement
				</li>
			";
			$outString.= $listElement;
		}

		$outString = "
			<ul>
				$outString
			</ul>
		";
		return $outString;

	}

	private function makeListElement($label, $data) {
	if (isset($this->specs['id'])){
		$idString=" id={$this->specs['id']}";
	}
	else{
		$idString="";
	}
	
	if (isset($this->specs['listTitles'])){
		$result = "
		<ul$idString>
			<li class='label'>$label</li>
			<li class='data'>$data</li>
		</ul>
		";
		}
		else{
		$result = "
		<ul$idString>
			<li class='data'>$data</li>
		</ul>
		";
		}
		return $result;
	}

}
