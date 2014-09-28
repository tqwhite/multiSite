<?php

class Application_Model_SimpleData
{

public function __construct($args){

	$args['Application_Model_SimpleData.__construct']='Got Here';
	$this->args=$args;



}

public function save(){
	$this->args['Application_Model_SimpleData.save']='Got Here, too';
	return $this->args;
}
}

