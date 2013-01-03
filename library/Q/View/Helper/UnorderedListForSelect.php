<?php
 class Q_View_Helper_UnorderedListForSelect extends Zend_View_Helper_Abstract {

	 public function unorderedListForSelect($list, $components) {

		$outString='';
		foreach ($list as $label=>$data){
			$outString.=$this->makeOneMenu($data, $components);
		}
	return $outString;

	 }

private function makeOneMenu($menuSpec, $components){

	 	$itemTemplate="<li><a href='<!url!>'><!title!></a></li>";
	 	$blockTemplate="<ul class='<!mainClass!>'><li><a href='#'><!title!></a><ul><!linkString!></ul></li></ul>";
		$linkString='';

		$blockTemplate=str_replace('<!mainClass!>', $components['mainClass'], $blockTemplate);

		$title=$menuSpec['title'];
		foreach ($menuSpec['links'] as $label=>$data){
			$itemString=str_replace('<!title!>', $data['title'], $itemTemplate);
			$itemString=str_replace('<!url!>', $data['url'], $itemString);
			$linkString.=$itemString;
		}

			$outString=str_replace('<!title!>', $title, $blockTemplate);
			$outString=str_replace('<!linkString!>', $linkString, $outString);

		return $outString;
}
 }