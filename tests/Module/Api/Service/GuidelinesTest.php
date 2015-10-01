<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class GuidelinesTest extends AbstractService
{
    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAdd()
    {
        $this->setIdentity(1);

        $data = $this->jsonRpc('guidelines.add', [
            'state' => 'state',
            'data' => json_decode('{"toto":"toto","titi":[1,2,3]}', true),
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
    public function testCanAdd2()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.add', [
            'state' => 'state',
            'data' => json_decode('{"tot2o":"toto2","titi2":[1,2,3,4,5]}',true),
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
    
        return $data['result'];
    }
    
    public function testCanAdd3()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.add', [
            'state' => 'state2',
            'data' => json_decode('{"toto":"toto","titi":[1,2,3]}', true),
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['result'];
    }
    
    /**
     * @depends testCanAdd2
     */
    public function testCanUpdate($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.update', [
            'id' => $id,
            'state' => 'state2',
            'data' => json_decode('{"tot2o":"toto2","titi2":[1,2,3,4,5]}', true),
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanAdd2
     */
    public function testCanDelete($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.delete', [
            'id' => $id
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    
    
    public function testCanIsViewedFalse()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.isViewed', [
            'state' => 'state'
        ]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , false); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGetList()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.getList', [
            'state' => 'state'
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 2);
        $this->assertEquals($data['result'][0]['toto'] , "toto");
        $this->assertEquals(count($data['result'][0]['titi']) , 3);
        $this->assertEquals($data['result'][0]['titi'][0] , 1);
        $this->assertEquals($data['result'][0]['titi'][1] , 2);
        $this->assertEquals($data['result'][0]['titi'][2] , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanIsViewedTrue()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('guidelines.isViewed', [
            'state' => 'state'
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function setIdentity($id)
    {
        $identityMock = $this->getMockBuilder('\Auth\Authentication\Adapter\Model\Identity')
            ->disableOriginalConstructor()
            ->getMock();
        
        $rbacMock = $this->getMockBuilder('\Rbac\Service\Rbac')
            ->disableOriginalConstructor()
            ->getMock();
        
        $identityMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));
        
        $identityMock->expects($this->any())
            ->method('toArray')
            ->will($this->returnValue(array('id' => $id)));
        
        $authMock = $this->getMockBuilder('\Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();
        
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
