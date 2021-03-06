<?php

class TestController extends Zend_Controller_Action
{

    private $doctrineContainer = null;

    private $em = null;


    public function init()
    {
        parent::init(array('controllerType'=>'json'));

		$this->doctrineContainer=Zend_Registry::get('doctrine');
		$this->em=$this->doctrineContainer->getEntityManager();
    }

    public function indexAction()
    {
		
    }

    public function databaseAction()
    {

    echo "DATABASE\n";
		$locale=$this->getRequest()->getParam('locale');

	switch ($locale){
		case 'qDev':
			$db = new Zend_Db_Adapter_Pdo_Mysql(array(
				'host'     => '127.0.0.1',
				'username' => 'tq',
				'password' => '',
				'dbname'   => 'test1'
			));
		break;
		case 'demo':
			$db = new Zend_Db_Adapter_Pdo_Mysql(array(
				'host'     => 'localhost',
				'username' => 'goodearthsite',
				'password' => 'glory*snacks',
				'dbname'   => 'goodEarthDemoData'
			));
		break;

		}

		$stmt = $db->query('select * from example');

		print_r($stmt->fetch());
		echo '<p/>'.Zend_Version::VERSION;
    }

    public function doctrineAction()
    {
		$this->doctrineContainer=Zend_Registry::get('doctrine');
		$initSchema=$this->getRequest()->getParam('initSchema');

		$u=new GE\Entity\User();
		$u->firstName='tq';
		$u->lastName='white';
		$u->userName='tq'.  uniqid();
		$u->password='12345';
	//	$u->userName='tqwhite';

		$em=$this->doctrineContainer->getEntityManager();
		$em->persist($u);
		$em->flush();
		$em->clear();

		$users=$em
			->createQuery('select u from GE\Entity\User u')
			->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

/*
		foreach ($users as $user){
		    echo 'refId='.$user->refId.'<br/>';
		}
*/
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(false);



		$this->_helper->json(array(
			status=>1,
			data=>$users
		));


    }

    public function utilityAction()
    {
// $request = new Zend_Controller_Request_Http(); 
// $this->getFrontController()
// ->setResponse(new Zend_Controller_Response_Cli)
// ->setRequest($request
// ->setControllerName('utilityController')
// ->setActionName('adhocAction'))
// ->setRouter(new Zend_Controller_Router_Route)
// ->setParam('noViewRenderer', true);
// 
// 
//     $response = new Zend_Controller_Response_Http();
// 
//     $frontController->dispatch($newRequest, $response);

//phpinfo();

	$this->view->message='hello from test/utility<br/>';

    }


}











