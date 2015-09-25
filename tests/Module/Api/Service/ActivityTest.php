<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ActivityTest extends AbstractService
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
        
        $data = $this->jsonRpc('activity.add', [
            'activities' => [[
                'source' => [
                    'id' => 3,
                    'name' => 'namesource',
                    'data' => 'datasource'],
                'date' => '2005-01-02',
                'event' => 'event',
                'object' => [
                    'id' => 3,
                    'name' => 'nameobj',
                    'data' => 'dataobj'],
                'target' => [
                    'id' => 3,
                    'name' => 'nametarget',
                    'data' => 'datatarget'
                ]
            ]]
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][0] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testCanAddTwo()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('activity.add', [
            'activities' => [[
                'source' => [
                    'id' => 3,
                    'name' => 'namesource',
                    'data' => 'datasource'],
                'date' => '2005-01-02',
                'event' => 'eventdeux',
                'object' => [
                    'id' => 3,
                    'name' => 'nameobj',
                    'data' => 'dataobj'
                ],'target' => [
                        'id' => 3,
                    'name' => 'nametarget',
                    'data' => 'datatarget'
                ],
            ]],
        ]);
        
        $this->printCreateTest($data);
    }

    public function testCanGetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('activity.getList', array());
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 12);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['source_id'], 3);
        $this->assertEquals($data['result'][0]['source_name'], "namesource");
        $this->assertEquals($data['result'][0]['source_data'], "datasource");
        $this->assertEquals($data['result'][0]['event'], "event");
        $this->assertEquals($data['result'][0]['object_id'], 3);
        $this->assertEquals($data['result'][0]['object_name'], "nameobj");
        $this->assertEquals($data['result'][0]['object_data'], "dataobj");
        $this->assertEquals($data['result'][0]['target_id'], 3);
        $this->assertEquals($data['result'][0]['target_name'], "nametarget");
        $this->assertEquals($data['result'][0]['target_data'], "datatarget");
        $this->assertEquals(! empty($data['result'][0]['date']), true);
        $this->assertEquals(count($data['result'][1]), 12);
        $this->assertEquals($data['result'][1]['id'], 2);
        $this->assertEquals($data['result'][1]['source_id'], 3);
        $this->assertEquals($data['result'][1]['source_name'], "namesource");
        $this->assertEquals($data['result'][1]['source_data'], "datasource");
        $this->assertEquals($data['result'][1]['event'], "eventdeux");
        $this->assertEquals($data['result'][1]['object_id'], 3);
        $this->assertEquals($data['result'][1]['object_name'], "nameobj");
        $this->assertEquals($data['result'][1]['object_data'], "dataobj");
        $this->assertEquals($data['result'][1]['target_id'], 3);
        $this->assertEquals($data['result'][1]['target_name'], "nametarget");
        $this->assertEquals($data['result'][1]['target_data'], "datatarget");
        $this->assertEquals(! empty($data['result'][1]['date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanGetListDeux()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('activity.getList', array('event' => 'event'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 12);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['source_id'], 3);
        $this->assertEquals($data['result'][0]['source_name'], "namesource");
        $this->assertEquals($data['result'][0]['source_data'], "datasource");
        $this->assertEquals($data['result'][0]['event'], "event");
        $this->assertEquals($data['result'][0]['object_id'], 3);
        $this->assertEquals($data['result'][0]['object_name'], "nameobj");
        $this->assertEquals($data['result'][0]['object_data'], "dataobj");
        $this->assertEquals($data['result'][0]['target_id'], 3);
        $this->assertEquals($data['result'][0]['target_name'], "nametarget");
        $this->assertEquals($data['result'][0]['target_data'], "datatarget");
        $this->assertEquals(! empty($data['result'][0]['date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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
