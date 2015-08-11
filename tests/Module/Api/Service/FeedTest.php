<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class FeedTest extends AbstractService
{

    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testAdd()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.add', array('content' => 'UN FEED','link' => 'link','video' => 'token','picture' => 'tokenp','document' => 'tokend'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testAddTwo()
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.add', array('content' => 'UN FEED2','link' => 'link2','video' => 'token2','picture' => 'tokenp2','document' => 'tokend2'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testgetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.getList', array());

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][0]) , 9);
        $this->assertEquals(count($data['result'][0]['user']) , 5);
        $this->assertEquals(count($data['result'][0]['user']['school']) , 4);
        $this->assertEquals($data['result'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][0]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['user']['id'] , 2);
        $this->assertEquals($data['result'][0]['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result'][0]['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result'][0]['id'] , 2);
        $this->assertEquals($data['result'][0]['content'] , "UN FEED2");
        $this->assertEquals($data['result'][0]['user_id'] , 2);
        $this->assertEquals($data['result'][0]['link'] , "link2");
        $this->assertEquals($data['result'][0]['video'] , "token2");
        $this->assertEquals($data['result'][0]['picture'] , "tokenp2");
        $this->assertEquals($data['result'][0]['document'] , "tokend2");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals(count($data['result'][1]) , 9);
        $this->assertEquals(count($data['result'][1]['user']) , 5);
        $this->assertEquals(count($data['result'][1]['user']['school']) , 4);
        $this->assertEquals($data['result'][1]['user']['school']['id'] , 1);
        $this->assertEquals($data['result'][1]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][1]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][1]['user']['school']['logo'] , null);
        $this->assertEquals($data['result'][1]['user']['id'] , 1);
        $this->assertEquals($data['result'][1]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result'][1]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result'][1]['user']['avatar'] , null);
        $this->assertEquals($data['result'][1]['id'] , 1);
        $this->assertEquals($data['result'][1]['content'] , "UN FEED");
        $this->assertEquals($data['result'][1]['user_id'] , 1);
        $this->assertEquals($data['result'][1]['link'] , "link");
        $this->assertEquals($data['result'][1]['video'] , "token");
        $this->assertEquals($data['result'][1]['picture'] , "tokenp");
        $this->assertEquals($data['result'][1]['document'] , "tokend");
        $this->assertEquals(!empty($data['result'][1]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddTwo
     */
    public function testAddComment($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.addComment', array('content' => 'UN FEED2','id' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddTwo
     */
    public function testgetListComment($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.GetListComment', array('id' => $feed));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 4);
        $this->assertEquals(count($data['result'][0]['user']) , 5);
        $this->assertEquals(count($data['result'][0]['user']['school']) , 4);
        $this->assertEquals($data['result'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][0]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['user']['id'] , 2);
        $this->assertEquals($data['result'][0]['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result'][0]['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['content'] , "UN FEED2");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    // DELETE 
    
    /**
     * @depends testAddComment
     */
    public function testdeleteComment($feed_comment)
    {
        $this->setIdentity(2);
    
        $data = $this->jsonRpc('feed.deleteComment', array('id' => $feed_comment));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
    
    /**
     * @depends testAdd
     */
    public function testdelete($feed)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('feed.delete', array('id' => $feed));
    
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
