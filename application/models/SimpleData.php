<?php

class Application_Model_SimpleData
{
	
	public function __construct($args)
	{
		
		$args['Application_Model_SimpleData.__construct'] = 'Got Here';
		$this->args                                       = $args;
		return $this->setupDbConnection();
		
	}
	
	private function setupDbConnection()
	{
		
		$databaseSpecs = Zend_Registry::get('databaseSpecs');
		
		$db = new Zend_Db_Adapter_Pdo_Mysql($databaseSpecs);
		
		$userDbname    = $this->args['databaseSpecs']['dbName'];
		$userTablename = $this->args['databaseSpecs']['tableName'];
		
		
		if (!$this->checkIfUserDbExists($db, $userDbname)) {
			$test = $db->query("create database $userDbname");
		}
		
		$databaseSpecs['dbname'] = $userDbname;
		$db                      = new Zend_Db_Adapter_Pdo_Mysql($databaseSpecs);
		
		if (!$this->checkIfUserTableExists($db, $userTablename)) {
			$aql = "CREATE TABLE `$userDbname`.`$userTablename` (`refId` varchar(36) NOT NULL, PRIMARY KEY (`refId`));";
			$test = $db->query($aql);
		}
		
		
		$this->databaseName=$userDbname;
		$this->tablename=$userTablename;
		$this->dbConnection = $db;
		
	} //end of method
	
	
	public function updateColumns($formParams){

	$columnFormats=$this->args['databaseSpecs']['columnFormats'];

	$sql="describe {$this->tablename}";
		$dbFieldList = $this->dbConnection->fetchAssoc($sql);

			foreach ($formParams as $label=>$data){
				if (!array_key_exists($label, $dbFieldList)){
				
				if (array_key_exists($label, $columnFormats)){
					$format=$columnFormats[$label];
				}
				elseif (array_key_exists('default', $columnFormats)){
					$format=$columnFormats['default'];
				}
				else{
					$format="varchar(255)";
				}
				
				$sql="alter table {$this->tablename} add $label $format";
				
				$test = $this->dbConnection->query($sql);


				}
		}
	}
	
	public function save($formParams)
	{
	
		$sql="select * from {$this->tablename} where refId='{$formParams['refId']}'";
		$result=$this->dbConnection->fetchAssoc($sql);

		if (!count($result)){
			$this->dbConnection->insert($this->tablename, $formParams);
		}
		else{
			$this->dbConnection->update($this->tablename, $formParams);
		}
	
		$this->args['Application_Model_SimpleData.save'] = 'Got Here, too';
		return $this->args;
	}
	
	private function checkIfUserDbExists($db, $userDbname)
	{
		
		$test = $db->fetchAssoc('show databases');
		
		foreach ($test as $label => $data) {
			if ($label == $userDbname) {
				return true;
			}
		}
		return false;
	}
	
	private function checkIfUserTableExists($db, $userTablename)
	{
		
		$test = $db->fetchAssoc('show tables');
		
		foreach ($test as $label => $data) {
			if ($label == $userTablename) {
				return true;
			}
		}
		return false;
	}
}
