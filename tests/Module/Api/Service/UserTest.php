<?php

namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class UserTest extends AbstractService
{
	public static $session;
	
	public static function setUpBeforeClass()
	{
		system('phing -q reset-db deploy-db');
		
		parent::setUpBeforeClass();
	}
	
	public function testCanAddUser()
	{
	    $this->setIdentity(1);
	    
		$datas = $this->jsonRpc('user.add', array(
			'firstname' => 'Christophe', 
			'lastname' => 'Robert', 
			'email' => 'crobertr@thestudnet.com', 
			'password' => 'studnet', 
			'birth_date' => '21/03/1984', 
			'position' => 'une position', 
			//'school_id' => 1, 
			'interest' => 'un interet', 
			'avatar' => 'un_token'
		));
		
		$this->assertEquals(count($datas) , 3); 
        $this->assertEquals($datas['result'] , 106); 
        $this->assertEquals($datas['id'] , 1); 
        $this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['id'];
	}
	
	/**
	 * @depends testCanAddUser
	 */
	public function testLogin()
	{
		$datas = $this->jsonRpc('user.login', array('user' => 'crobertr@thestudnet.com', 'password' => 'studnet'));
		
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals(count($datas['result']) , 9); 
		$this->assertEquals($datas['result']['id'] , 106); 
		$this->assertEquals(!empty($datas['result']['token']) , true);
		$this->assertEquals($datas['result']['created_date'] , null); 
		$this->assertEquals($datas['result']['firstname'] , "Christophe"); 
		$this->assertEquals($datas['result']['lastname'] , "Robert"); 
		$this->assertEquals($datas['result']['email'] , "crobertr@thestudnet.com"); 
		$this->assertEquals($datas['result']['expiration_date'] , null); 
		$this->assertEquals(count($datas['result']['roles']) , 1); 
		$this->assertEquals($datas['result']['roles'][0] , "student"); 
		$this->assertEquals(!empty($datas['result']['wstoken']) , true); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['result']['token'];
	}
	
	public function testLoginIn()
	{
	    $datas = $this->jsonRpc('user.login', array('user' => 'crobert@thestudnet.com', 'password' => 'thestudnet'));

	    $this->assertEquals(count($datas) , 3);
	    $this->assertEquals(count($datas['result']) , 9);
	    $this->assertEquals($datas['result']['id'] , 3);
	    $this->assertEquals(!empty($datas['result']['token']) , true);
	    $this->assertEquals($datas['result']['created_date'] , null);
	    $this->assertEquals($datas['result']['firstname'] , "Christophe");
	    $this->assertEquals($datas['result']['lastname'] , "Robert");
	    $this->assertEquals($datas['result']['email'] , "crobert@thestudnet.com");
	    $this->assertEquals($datas['result']['expiration_date'] , null);
	    $this->assertEquals(count($datas['result']['roles']) , 1);
	    $this->assertEquals($datas['result']['roles'][0] , "academic");
	    $this->assertEquals(!empty($datas['result']['wstoken']) , true);
	    $this->assertEquals($datas['id'] , 1);
	    $this->assertEquals($datas['jsonrpc'] , 2.0);

	    return $datas['result']['token'];
	}

	/**
	 * @depends testCanAddUser
	 */
	public function testUpdate($id)
	{
		$this->setIdentity(1);
		
		$datas = $this->jsonRpc('user.update', array(
			'id' => $id,
			'firstname' => 'Jean', 
			'lastname' => 'Paul', 
			'email' => 'jpaul@thestudnet.com', 
			'password' => 'studnetnew', 
			'birth_date' => '21/03/1985', 
			'position' => 'une position new', 
			//'school_id' => 1, 
			'interest' => 'un interet new', 
			'avatar' => 'un_token_new', 
			//'program_id' => 1 
		));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals($datas['result'] , 1);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	/**
	 * @depends testCanAddUser
	 */
	public function testGet($id)
	{
	    $this->setIdentity(1);
	    
	    $datas = $this->jsonRpc('user.get', array(
	        'id' => $id,
	    ));
	
	    $this->assertEquals(count($datas) , 3);
	    $this->assertEquals(count($datas['result']) , 12);
	    $this->assertEquals(count($datas['result']['school']) , 3);
	    $this->assertEquals($datas['result']['school']['name'] , "Tempor Limited");
	    $this->assertEquals($datas['result']['school']['short_name'] , "ornare");
	    $this->assertEquals($datas['result']['school']['logo'] , null);
	    $this->assertEquals($datas['result']['id'] , 1);
	    $this->assertEquals($datas['result']['firstname'] , "Jean");
	    $this->assertEquals($datas['result']['lastname'] , "Paul");
	    $this->assertEquals($datas['result']['email'] , "jpaul@thestudnet.com");
	    $this->assertEquals($datas['result']['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
	    $this->assertEquals($datas['result']['birth_date'] , "0000-00-00 00:00:00");
	    $this->assertEquals($datas['result']['position'] , "une position new");
	    $this->assertEquals($datas['result']['school_id'] , 1);
	    $this->assertEquals($datas['result']['interest'] , "un interet new");
	    $this->assertEquals($datas['result']['avatar'] , "un_token_new");
	    $this->assertEquals(count($datas['result']['roles']) , 1);
	    $this->assertEquals($datas['result']['roles'][0] , "super_admin");
	    $this->assertEquals($datas['id'] , 1);
	    $this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	/**
	 * @depends testCanAddUser
	 */
	public function testAddPrrogram($id)
	{
	    $this->setIdentity(1);
	     
	    $datas = $this->jsonRpc('user.addProgram', array('user' => 1, 'program' => 1));
	
	    $this->assertEquals(count($datas) , 3);
	    $this->assertEquals(count($datas['result']) , 1);
	    $this->assertEquals(count($datas['result'][1]) , 1);
	    $this->assertEquals($datas['result'][1][1] , 1);
	    $this->assertEquals($datas['id'] , 1);
	    $this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	
	
	/**
	 * @depends testCanAddUser
	 */
	public function testDelete($id)
	{
		$this->setIdentity(1);
		
		$datas = $this->jsonRpc('user.delete', array(
				'id' => $id,
		));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals(count($datas['result']) , 1);
		$this->assertEquals($datas['result'][1] , 1);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	public function testAddlanguage()
	{
		$this->setIdentity(1);
		
		$datas = $this->jsonRpc('user.addLanguage', array(
				'language' => array('name' => 'french'),
				'language_level' => 1,
		));
		
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0); 
	}
	
	public function testDeletelanguage()
	{
		$this->setIdentity(1);
	
		$datas = $this->jsonRpc('user.deleteLanguage', array(
				'id' => 1,
		));
	
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	public function setIdentity($id)
	{
		$identityMock = $this->getMockBuilder('\Auth\Authentication\Adapter\Model\Identity')
		->disableOriginalConstructor()->getMock();
	
		$rbacMock = $this->getMockBuilder('\Rbac\Service\Rbac')
		->disableOriginalConstructor()->getMock();
	
		$identityMock->expects($this->any())
		->method('getId')
		->will($this->returnValue($id));
	
		$authMock = $this->getMockBuilder('\Zend\Authentication\AuthenticationService')
		->disableOriginalConstructor()->getMock();
	
		$authMock->expects($this->any())
		->method('getIdentity')
		->will($this->returnValue($identityMock));
	
		$authMock->expects($this->any())
		->method('hasIdentity')
		->will($this->returnValue(true));
	
		$rbacMock->expects($this->any())
		->method('isGranted')
		->will($this->returnValue(true));
	
		$serviceManager = $this->getApplicationServiceLocator();
		$serviceManager->setAllowOverride(true);
		$serviceManager->setService('auth.service', $authMock);
		$serviceManager->setService('rbac.service', $rbacMock);
	}
}