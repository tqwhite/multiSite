<?php

class UtilityController extends Q_Controller_Base
{

    public function init()
    {
    //    parent::init(array('controllerType'=>'json'));
        parent::init(array('controllerType'=>'zend'));
    }

    public function indexAction()
    {
        // action body
    }

    public function migrateAction()
    {
		$updateSchema=$this->getRequest()->getParam('updateSchema');
		
		
		$doctrineContainer=Zend_Registry::get('doctrine');
		$entityManager=$doctrineContainer->getEntityManager();

		if($updateSchema=='pleaseRiskMyHappiness'){


			echo "Doing these things...<p/>";

			$cmd="php ../scripts/doctrine.php orm:schema-tool:update --dump-sql;";
			$result=shell_exec($cmd);

			if (strlen($result)<2){$result="database is up to date. no changes required.";}

			$out=str_replace(';', ';<p/>', $result);
			$out=str_replace(',', ',<br/>&nbsp;&nbsp;&nbsp;&nbsp;', $out);
			echo "<div style='font-family:sans-serif;font-size:10pt;margin:40px 0px 0px 50px;'>$out</div>";


			//if you run the code below, it will actually change the database, SO DON'T

			$entityManager=$doctrineContainer->getEntityManager();
			$classes=array(
				$entityManager->getClassMetadata('Q\Entity\Bookmark')
			);
			$tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
			echo $tool->updateSchema($classes, true);
			echo '<p/>And they are done.<BR>';
			exit;
		}
		else{

			$cmd="php ../scripts/doctrine.php orm:schema-tool:update --dump-sql;";
			$result=shell_exec($cmd);

			if (strlen($result)<2){$result="database is up to date. no changes required.";}

			$out=str_replace(';', ';<p/>', $result);
			$out=str_replace(',', ',<br/>&nbsp;&nbsp;&nbsp;&nbsp;', $out);
			echo "<div style='font-family:sans-serif;font-size:10pt;margin:40px 0px 0px 50px;color:red;'>$out</div>";
			exit;
		
    }
    }

    public function adhocAction()
    {
/*
$request = new Zend_Controller_Request_Http();
 $this->getFrontController()
 ->setResponse(new Zend_Controller_Response_Cli)
 ->setRequest($request->setControllerName('testContr‌​oller')
 ->setActionName('testAction'))
 ->setRouter(new Zend_Controller_Router_Route)
 ->setParam('noViewRenderer', true);


return;

	$source=array(
		array('uri'=>'http://justkidding.com', 'anchor'=>'justkidding', 'shortId'=>'shortness')
	);

	$newObj=new \Application_Model_Bookmark();
	$newObj->newFromArrayList($source, false);
	echo "done"; exit;
	return $newObj;
*/
return;
    }


    public function queryAction()
    {

$this->view->subForm=$this->view->action('adhoc','utility');


return;
    }



    public function databaseAction()
    {
    
		//minimal demonstration of database functionality
    
		$query='show tables';
	
		$config=$this->getInvokeArg('bootstrap')->getOptions();
	
		$dbParms=$config['resources']['doctrine']['dbal']['connections']['default']['parameters'];
	
		$display=$dbParms;
		$display['password']='****';
    
		echo Zend_Version::VERSION."\n<p/>";
    	echo "DATABASE ($query)\n<p/>";

		$dbParms['username']=$dbParms['user'];
		
		$db = new Zend_Db_Adapter_Pdo_Mysql($dbParms);

		$stmt = $db->query($query);
		
		\Q\Utils::dumpWeb($display);
		\Q\Utils::dumpWeb($stmt->fetchAll());
		exit;
    }

}







