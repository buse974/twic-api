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
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 7);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testCanAddRole
     */
    public function testCanUpdateRole($id)
    {
        $this->setIdentity(3);
        
        $datas = $this->jsonRpc('role.update', array('id' => $id,'name' => 'NEWADMIN'));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddRole
     */
    public function testCanDeleteRole($id)
    {
        $this->setIdentity(3);
        
        $datas = $this->jsonRpc('role.delete', array('id' => $id));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    public function testCanAddRoleTwo()
    {
        $this->setIdentity(3);
        $datas = $this->jsonRpc('role.add', array('name' => 'BOSS'));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 8);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testCanAddRoleTwo
     */
    public function testCanAddRoleUser($id)
    {
        $this->setIdentity(3);
        
        $datas = $this->jsonRpc('role.addUser', array('role' => $id,'user' => 1));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

}