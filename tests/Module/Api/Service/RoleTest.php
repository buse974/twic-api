<?php

namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class RoleTest extends AbstractService
{
	public static function setUpBeforeClass()
	{
		system('phing -q reset-db deploy-db');
	
		parent::setUpBeforeClass();
	}
	
	public function testCanAddRole()
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('role.add', array('name' => 'ADMIN'));
		
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 7); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0); 
		
		return $datas['result'];
	}
	
	/**
	 * @depends testCanAddRole
	 */
	public function testCanUpdateRole($id)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('role.update', array('id' => $id, 'name' => 'NEWADMIN'));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	/**
	 * @depends testCanAddRole
	 */
	public function testCanDeleteRole($id)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('role.delete', array('id' => $id));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals($datas['result'] , 1);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	public function testCanAddRoleTwo()
	{
		$this->setIdentity(3);
		$datas = $this->jsonRpc('role.add', array('name' => 'BOSS'));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals($datas['result'] , 8);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	
		return $datas['result'];
	}
	
	/**
	 * @depends testCanAddRoleTwo
	 */
	public function testCanAddRoleUser($id)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('role.addUser', array('role' => $id, 'user' => 1));
	
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