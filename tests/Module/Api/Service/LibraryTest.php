<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class LibraryTest extends AbstractService
{

    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAddDocument()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('library.add', array('name' => 'monfile','link' => 'link', 'token' => 'token'));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 10);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['name'] , "monfile");
        $this->assertEquals($data['result']['link'] , "link");
        $this->assertEquals($data['result']['token'] , "token");
        $this->assertEquals($data['result']['type'] , "f");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals($data['result']['updated_date'] , null);
        $this->assertEquals($data['result']['folder_id'] , null);
        $this->assertEquals($data['result']['owner_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        
        return $data['result']['id'];
    }
    
    /**
     * @depends testCanAddDocument
     */
    public function testCanUpdateDocument($id)
    {
    	$this->setIdentity(1);
    
    	$data = $this->jsonRpc('library.update', array('id' => $id, 'name' => 'monfileupt','link' => 'linkupt', 'token' => 'tokenupt'));

    	$this->assertEquals(count($data) , 3);
    	$this->assertEquals(count($data['result']) , 10);
    	$this->assertEquals($data['result']['id'] , 1);
    	$this->assertEquals($data['result']['name'] , "monfileupt");
    	$this->assertEquals($data['result']['link'] , "linkupt");
    	$this->assertEquals($data['result']['token'] , "tokenupt");
    	$this->assertEquals($data['result']['type'] , "f");
    	$this->assertEquals(!empty($data['result']['created_date']) , true);
    	$this->assertEquals($data['result']['deleted_date'] , null);
    	$this->assertEquals(!empty($data['result']['updated_date']) , true);
    	$this->assertEquals($data['result']['folder_id'] , null);
    	$this->assertEquals($data['result']['owner_id'] , 1);
    	$this->assertEquals($data['id'] , 1);
    	$this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanAddDocument
     */
    public function testCanGetListDocument($id)
    {
    	$this->setIdentity(1);
    
    	$data = $this->jsonRpc('library.getList', array());
   
    	$this->assertEquals(count($data) , 3);
    	$this->assertEquals(count($data['result']) , 1);
    	$this->assertEquals(count($data['result'][0]) , 10);
    	$this->assertEquals($data['result'][0]['id'] , 1);
    	$this->assertEquals($data['result'][0]['name'] , "monfileupt");
    	$this->assertEquals($data['result'][0]['link'] , "linkupt");
    	$this->assertEquals($data['result'][0]['token'] , "tokenupt");
    	$this->assertEquals($data['result'][0]['type'] , "f");
    	$this->assertEquals(!empty($data['result'][0]['created_date']) , true);
    	$this->assertEquals($data['result'][0]['deleted_date'] , null);
    	$this->assertEquals(!empty($data['result'][0]['updated_date']) , true);
    	$this->assertEquals($data['result'][0]['folder_id'] , null);
    	$this->assertEquals($data['result'][0]['owner_id'] , 1);
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
            ->will($this->returnValue(['id' => $id, 'token' => ''+$id+'token']));
        
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
