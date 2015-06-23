<?php
class Q_View_Helper_Fidgets_FabricateJoyrideList extends Zend_View_Helper_Abstract {

	public function fabricateJoyrideList($panelList) {
		$outString = '';
		$index=0;
		foreach ($panelList as $panelName=>$panel){
			$panelName=$this->view->filenameToLabel($panelName);
			
			$panel=preg_match("/id='(.*?)'.*/i", $panel, $matches);
			
			$id=$matches[1];
			if ($index==0){
$outString.="<li data-id='$id' data-text='Next' data-options='tip_location: top; prev_button: false'>
    <p>$panelName</p>
  </li>";
  }
  else{
$outString.="<li data-id='$id' data-text='Next' data-options='tip_location: top; prev_button: true'>
    <p>$panelName</p>
  </li>";
  }
  
  $index++;

		}
		$outString="<ol class='joyride-list' data-joyride>$outString</ol>";
		return $outString;

	}

}
