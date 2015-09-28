<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ResumeTest extends AbstractService
{

    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAddResume()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('resume.add', array(
            'start_date' => '2013-01-02', 
            'end_date' => '2015-01-02', 
            'address' => 'France', 
            'logo' => 'token', 
            'title' => 'super exp', 
            'subtitle' => ' ingenieur R&D', 
            'description' => 'plein de chose', 
            'type' => 1
            ));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
    public function testCanAddResumeTwo()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('resume.add', array(
            'start_date' => '2013-03-02',
            'end_date' => '2015-03-02',
            'address' => 'USA',
            'logo' => 'token2',
            'title' => 'super exp2',
            'subtitle' => ' ingenieur R&D2',
            'description' => 'plein de chose2',
            'type' => 1
        ));

        $this->reset();
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('resume.add', array(
            'start_date' => '2013-03-02',
            'end_date' => '2015-04-02',
            'address' => 'USA',
            'logo' => 'token2',
            'title' => 'super exp2',
            'subtitle' => ' ingenieur R&D2',
            'description' => 'plein de chose2',
            'type' => 1
        ));
        
        $this->reset();
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('resume.add', array(
            'start_date' => '2013-03-02',
            'address' => 'USA',
            'logo' => 'token2',
            'title' => 'super exp2',
            'subtitle' => ' ingenieur R&D2',
            'description' => 'plein de chose2',
            'type' => 1
        ));
        
        $this->reset();
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('resume.add', array(
            'start_date' => '2013-03-02',
            'address' => 'USA',
            'logo' => 'token2',
            'title' => 'super exp2',
            'subtitle' => ' ingenieur R&D2',
            'description' => 'plein de chose2',
            'type' => 1
        ));
    }
    

    
    /**
     * 
     * @depends testCanAddResume
     */
    public function testCanUpdateResume($resume)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('resume.update', array(
            'id' => $resume,
            'start_date' => '2013-01-09',
            'end_date' => '2015-01-09',
            'address' => 'France UPT',
            'logo' => 'token UPT',
            'title' => 'super exp UPT',
            'subtitle' => ' ingenieur R&D UPT',
            'description' => 'plein de chose UPT',
            'type' => 2
        ));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }

    /**
     *
     * @depends testCanAddResume
     */
    public function testCanGetResume($resume)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('resume.get', array(
            'user' => 1
        ));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 5);
        $this->assertEquals(count($data['result'][0]) , 16);
        $this->assertEquals($data['result'][0]['id'] , 4);
        $this->assertEquals($data['result'][0]['start_date'] , "2013-03-02");
        $this->assertEquals($data['result'][0]['end_date'] , null);
        $this->assertEquals($data['result'][0]['address'] , "USA");
        $this->assertEquals($data['result'][0]['title'] , "super exp2");
        $this->assertEquals($data['result'][0]['subtitle'] , " ingenieur R&D2");
        $this->assertEquals($data['result'][0]['logo'] , "token2");
        $this->assertEquals($data['result'][0]['description'] , "plein de chose2");
        $this->assertEquals($data['result'][0]['type'] , 1);
        $this->assertEquals($data['result'][0]['user_id'] , 1);
        $this->assertEquals($data['result'][0]['publisher'] , null);
        $this->assertEquals($data['result'][0]['url'] , null);
        $this->assertEquals($data['result'][0]['cause'] , null);
        $this->assertEquals($data['result'][0]['study'] , null);
        $this->assertEquals($data['result'][0]['grade'] , null);
        $this->assertEquals($data['result'][0]['note'] , null);
        $this->assertEquals(count($data['result'][1]) , 16);
        $this->assertEquals($data['result'][1]['id'] , 5);
        $this->assertEquals($data['result'][1]['start_date'] , "2013-03-02");
        $this->assertEquals($data['result'][1]['end_date'] , null);
        $this->assertEquals($data['result'][1]['address'] , "USA");
        $this->assertEquals($data['result'][1]['title'] , "super exp2");
        $this->assertEquals($data['result'][1]['subtitle'] , " ingenieur R&D2");
        $this->assertEquals($data['result'][1]['logo'] , "token2");
        $this->assertEquals($data['result'][1]['description'] , "plein de chose2");
        $this->assertEquals($data['result'][1]['type'] , 1);
        $this->assertEquals($data['result'][1]['user_id'] , 1);
        $this->assertEquals($data['result'][1]['publisher'] , null);
        $this->assertEquals($data['result'][1]['url'] , null);
        $this->assertEquals($data['result'][1]['cause'] , null);
        $this->assertEquals($data['result'][1]['study'] , null);
        $this->assertEquals($data['result'][1]['grade'] , null);
        $this->assertEquals($data['result'][1]['note'] , null);
        $this->assertEquals(count($data['result'][2]) , 16);
        $this->assertEquals($data['result'][2]['id'] , 3);
        $this->assertEquals($data['result'][2]['start_date'] , "2013-03-02");
        $this->assertEquals($data['result'][2]['end_date'] , "2015-04-02");
        $this->assertEquals($data['result'][2]['address'] , "USA");
        $this->assertEquals($data['result'][2]['title'] , "super exp2");
        $this->assertEquals($data['result'][2]['subtitle'] , " ingenieur R&D2");
        $this->assertEquals($data['result'][2]['logo'] , "token2");
        $this->assertEquals($data['result'][2]['description'] , "plein de chose2");
        $this->assertEquals($data['result'][2]['type'] , 1);
        $this->assertEquals($data['result'][2]['user_id'] , 1);
        $this->assertEquals($data['result'][2]['publisher'] , null);
        $this->assertEquals($data['result'][2]['url'] , null);
        $this->assertEquals($data['result'][2]['cause'] , null);
        $this->assertEquals($data['result'][2]['study'] , null);
        $this->assertEquals($data['result'][2]['grade'] , null);
        $this->assertEquals($data['result'][2]['note'] , null);
        $this->assertEquals(count($data['result'][3]) , 16);
        $this->assertEquals($data['result'][3]['id'] , 2);
        $this->assertEquals($data['result'][3]['start_date'] , "2013-03-02");
        $this->assertEquals($data['result'][3]['end_date'] , "2015-03-02");
        $this->assertEquals($data['result'][3]['address'] , "USA");
        $this->assertEquals($data['result'][3]['title'] , "super exp2");
        $this->assertEquals($data['result'][3]['subtitle'] , " ingenieur R&D2");
        $this->assertEquals($data['result'][3]['logo'] , "token2");
        $this->assertEquals($data['result'][3]['description'] , "plein de chose2");
        $this->assertEquals($data['result'][3]['type'] , 1);
        $this->assertEquals($data['result'][3]['user_id'] , 1);
        $this->assertEquals($data['result'][3]['publisher'] , null);
        $this->assertEquals($data['result'][3]['url'] , null);
        $this->assertEquals($data['result'][3]['cause'] , null);
        $this->assertEquals($data['result'][3]['study'] , null);
        $this->assertEquals($data['result'][3]['grade'] , null);
        $this->assertEquals($data['result'][3]['note'] , null);
        $this->assertEquals(count($data['result'][4]) , 16);
        $this->assertEquals($data['result'][4]['id'] , 1);
        $this->assertEquals($data['result'][4]['start_date'] , "2013-01-09");
        $this->assertEquals($data['result'][4]['end_date'] , "2015-01-09");
        $this->assertEquals($data['result'][4]['address'] , "France UPT");
        $this->assertEquals($data['result'][4]['title'] , "super exp UPT");
        $this->assertEquals($data['result'][4]['subtitle'] , " ingenieur R&D UPT");
        $this->assertEquals($data['result'][4]['logo'] , "token UPT");
        $this->assertEquals($data['result'][4]['description'] , "plein de chose UPT");
        $this->assertEquals($data['result'][4]['type'] , 2);
        $this->assertEquals($data['result'][4]['user_id'] , 1);
        $this->assertEquals($data['result'][4]['publisher'] , null);
        $this->assertEquals($data['result'][4]['url'] , null);
        $this->assertEquals($data['result'][4]['cause'] , null);
        $this->assertEquals($data['result'][4]['study'] , null);
        $this->assertEquals($data['result'][4]['grade'] , null);
        $this->assertEquals($data['result'][4]['note'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * 
     * @depends testCanAddResume
     */
    public function testCanDeleteResume($resume)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('resume.delete', array(
            'id' => $resume
        ));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
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
