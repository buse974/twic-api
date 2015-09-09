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

    public function testAddContact()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('contact.add', array('user' => 3));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    public function testGetListRequest()
    {
        $this->setIdentity(3);
    
        $data = $this->jsonRpc('contact.getListRequest', array());
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 3);
        $this->assertEquals(count($data['result'][0]['contact']) , 16);
        $this->assertEquals(count($data['result'][0]['contact']['origin']) , 2);
        $this->assertEquals($data['result'][0]['contact']['origin']['id'] , null);
        $this->assertEquals($data['result'][0]['contact']['origin']['short_name'] , null);
        $this->assertEquals(count($data['result'][0]['contact']['nationality']) , 2);
        $this->assertEquals($data['result'][0]['contact']['nationality']['id'] , null);
        $this->assertEquals($data['result'][0]['contact']['nationality']['short_name'] , null);
        $this->assertEquals($data['result'][0]['contact']['gender'] , null);
        $this->assertEquals($data['result'][0]['contact']['contact_state'] , 2);
        $this->assertEquals(count($data['result'][0]['contact']['school']) , 4);
        $this->assertEquals($data['result'][0]['contact']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][0]['contact']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][0]['contact']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['contact']['id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['firstname'] , "Paul");
        $this->assertEquals($data['result'][0]['contact']['lastname'] , "Boussekey");
        $this->assertEquals($data['result'][0]['contact']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result'][0]['contact']['birth_date'] , null);
        $this->assertEquals($data['result'][0]['contact']['position'] , null);
        $this->assertEquals($data['result'][0]['contact']['school_id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['interest'] , null);
        $this->assertEquals($data['result'][0]['contact']['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['contact']['roles']) , 1);
        $this->assertEquals($data['result'][0]['contact']['roles'][0] , "admin");
        $this->assertEquals(count($data['result'][0]['contact']['program']) , 0);
        $this->assertEquals($data['result'][0]['user_id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['request_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testAcceptContact()
    {
        $this->setIdentity(3);
    
        $data = $this->jsonRpc('contact.accept', array('user' => 1));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testGetListContact()
    {
        $this->setIdentity(2);
    
        $data = $this->jsonRpc('contact.getList', array());
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 3);
        $this->assertEquals(count($data['result'][0]['contact']) , 16);
        $this->assertEquals(count($data['result'][0]['contact']['origin']) , 2);
        $this->assertEquals($data['result'][0]['contact']['origin']['id'] , null);
        $this->assertEquals($data['result'][0]['contact']['origin']['short_name'] , null);
        $this->assertEquals(count($data['result'][0]['contact']['nationality']) , 2);
        $this->assertEquals($data['result'][0]['contact']['nationality']['id'] , null);
        $this->assertEquals($data['result'][0]['contact']['nationality']['short_name'] , null);
        $this->assertEquals($data['result'][0]['contact']['gender'] , null);
        $this->assertEquals($data['result'][0]['contact']['contact_state'] , 3);
        $this->assertEquals(count($data['result'][0]['contact']['school']) , 4);
        $this->assertEquals($data['result'][0]['contact']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][0]['contact']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][0]['contact']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['contact']['id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['firstname'] , "Paul");
        $this->assertEquals($data['result'][0]['contact']['lastname'] , "Boussekey");
        $this->assertEquals($data['result'][0]['contact']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result'][0]['contact']['birth_date'] , null);
        $this->assertEquals($data['result'][0]['contact']['position'] , null);
        $this->assertEquals($data['result'][0]['contact']['school_id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['interest'] , null);
        $this->assertEquals($data['result'][0]['contact']['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['contact']['roles']) , 1);
        $this->assertEquals($data['result'][0]['contact']['roles'][0] , "admin");
        $this->assertEquals(count($data['result'][0]['contact']['program']) , 0);
        $this->assertEquals($data['result'][0]['contact_id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['accepted_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testGetListConcactByUser()
    {
        $this->setIdentity(2);
    
        $data = $this->jsonRpc('contact.getList', array('user' => array('id' => 1)));
         
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][0]) , 3);
        $this->assertEquals(count($data['result'][0]['contact']) , 16);
        $this->assertEquals(count($data['result'][0]['contact']['origin']) , 2);
        $this->assertEquals($data['result'][0]['contact']['origin']['id'] , null);
        $this->assertEquals($data['result'][0]['contact']['origin']['short_name'] , null);
        $this->assertEquals(count($data['result'][0]['contact']['nationality']) , 2);
        $this->assertEquals($data['result'][0]['contact']['nationality']['id'] , null);
        $this->assertEquals($data['result'][0]['contact']['nationality']['short_name'] , null);
        $this->assertEquals($data['result'][0]['contact']['gender'] , null);
        $this->assertEquals($data['result'][0]['contact']['contact_state'] , 0);
        $this->assertEquals(count($data['result'][0]['contact']['school']) , 4);
        $this->assertEquals($data['result'][0]['contact']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][0]['contact']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][0]['contact']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['contact']['id'] , 2);
        $this->assertEquals($data['result'][0]['contact']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result'][0]['contact']['lastname'] , "Hoang");
        $this->assertEquals($data['result'][0]['contact']['email'] , "xhoang@thestudnet.com");
        $this->assertEquals($data['result'][0]['contact']['birth_date'] , null);
        $this->assertEquals($data['result'][0]['contact']['position'] , null);
        $this->assertEquals($data['result'][0]['contact']['school_id'] , 1);
        $this->assertEquals($data['result'][0]['contact']['interest'] , null);
        $this->assertEquals($data['result'][0]['contact']['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['contact']['roles']) , 1);
        $this->assertEquals($data['result'][0]['contact']['roles'][0] , "super_admin");
        $this->assertEquals(count($data['result'][0]['contact']['program']) , 0);
        $this->assertEquals($data['result'][0]['contact_id'] , 2);
        $this->assertEquals(!empty($data['result'][0]['accepted_date']) , true);
        $this->assertEquals(count($data['result'][1]) , 3);
        $this->assertEquals(count($data['result'][1]['contact']) , 16);
        $this->assertEquals(count($data['result'][1]['contact']['origin']) , 2);
        $this->assertEquals($data['result'][1]['contact']['origin']['id'] , null);
        $this->assertEquals($data['result'][1]['contact']['origin']['short_name'] , null);
        $this->assertEquals(count($data['result'][1]['contact']['nationality']) , 2);
        $this->assertEquals($data['result'][1]['contact']['nationality']['id'] , null);
        $this->assertEquals($data['result'][1]['contact']['nationality']['short_name'] , null);
        $this->assertEquals($data['result'][1]['contact']['gender'] , null);
        $this->assertEquals($data['result'][1]['contact']['contact_state'] , 0);
        $this->assertEquals(count($data['result'][1]['contact']['school']) , 4);
        $this->assertEquals($data['result'][1]['contact']['school']['id'] , 1);
        $this->assertEquals($data['result'][1]['contact']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][1]['contact']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][1]['contact']['school']['logo'] , null);
        $this->assertEquals($data['result'][1]['contact']['id'] , 3);
        $this->assertEquals($data['result'][1]['contact']['firstname'] , "Christophe");
        $this->assertEquals($data['result'][1]['contact']['lastname'] , "Robert");
        $this->assertEquals($data['result'][1]['contact']['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result'][1]['contact']['birth_date'] , null);
        $this->assertEquals($data['result'][1]['contact']['position'] , null);
        $this->assertEquals($data['result'][1]['contact']['school_id'] , 1);
        $this->assertEquals($data['result'][1]['contact']['interest'] , null);
        $this->assertEquals($data['result'][1]['contact']['avatar'] , null);
        $this->assertEquals(count($data['result'][1]['contact']['roles']) , 1);
        $this->assertEquals($data['result'][1]['contact']['roles'][0] , "academic");
        $this->assertEquals(count($data['result'][1]['contact']['program']) , 0);
        $this->assertEquals($data['result'][1]['contact_id'] , 3);
        $this->assertEquals(!empty($data['result'][1]['accepted_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testAddContactSchool()
    {
        $this->setIdentity(2);
    
        $data = $this->jsonRpc('contact.addBySchool', array('school' => 1));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 13);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    public function testCanAddUser()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.add', array('firstname' => 'Christophe',
            'gender' => 'm', 'origin' => 1, 'nationality' => 1 , 'lastname' => 'Robert','email' => 'crobertr@thestudnet.com','password' => 'studnet','birth_date' => '21/03/1984','position' => 'une position',
            // 'school_id' => 1,
            'interest' => 'un interet','avatar' => 'un_token'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 7);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddUser
     */
    public function testLogin()
    {
        $data = $this->jsonRpc('user.login', array('user' => 'crobertr@thestudnet.com','password' => 'studnet'));
       
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 11);
        $this->assertEquals($data['result']['id'] , 7);
        $this->assertEquals(! empty($data['result']['token']), true);
        $this->assertEquals($data['result']['created_date'] , null);
        $this->assertEquals($data['result']['firstname'] , "Christophe");
        $this->assertEquals($data['result']['lastname'] , "Robert");
        $this->assertEquals($data['result']['email'] , "crobertr@thestudnet.com");
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][4] , "student");
        $this->assertEquals(count($data['result']['school']) , 4);
        $this->assertEquals($data['result']['school']['id'] , 1);
        $this->assertEquals($data['result']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['school']['logo'] , null);
        $this->assertEquals(!empty($data['result']['wstoken']) , true);
        $this->assertEquals(!empty($data['result']['fbtoken']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['token'];
    }

    public function testLoginIn()
    {
        $data = $this->jsonRpc('user.login', array('user' => 'crobert@thestudnet.com','password' => 'thestudnet'));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 11);
        $this->assertEquals($data['result']['id'] , 3);
        $this->assertEquals(! empty($data['result']['token']), true);
        $this->assertEquals($data['result']['created_date'] , null);
        $this->assertEquals($data['result']['firstname'] , "Christophe");
        $this->assertEquals($data['result']['lastname'] , "Robert");
        $this->assertEquals($data['result']['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][3] , "academic");
        $this->assertEquals(count($data['result']['school']) , 4);
        $this->assertEquals($data['result']['school']['id'] , 1);
        $this->assertEquals($data['result']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['school']['logo'] , null);
        $this->assertEquals(!empty($data['result']['wstoken']) , true);
        $this->assertEquals(!empty($data['result']['fbtoken']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['token'];
    }

    /**
     * @depends testCanAddUser
     */
    public function testUpdate($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.update', array('id' => $id,'firstname' => 'Jean','lastname' => 'Paul','email' => 'jpaul@thestudnet.com','password' => 'studnetnew','birth_date' => '21/03/1985','position' => 'une position new',
            // 'school_id' => 1,
            'interest' => 'un interet new','avatar' => 'un_token_new')
        // 'program_id' => 1
        );
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddUser
     */
    public function testLostPassword($id)
    {
        $data = $this->jsonRpc('user.lostPassword', array('email' => 'jpaul@thestudnet.com'));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddUser
     */
    public function testGet($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.get', array('id' => $id));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 16);
        $this->assertEquals(count($data['result']['origin']) , 2);
        $this->assertEquals($data['result']['origin']['id'] , null);
        $this->assertEquals($data['result']['origin']['short_name'] , null);
        $this->assertEquals(count($data['result']['nationality']) , 2);
        $this->assertEquals($data['result']['nationality']['id'] , null);
        $this->assertEquals($data['result']['nationality']['short_name'] , null);
        $this->assertEquals($data['result']['gender'] , "m");
        $this->assertEquals($data['result']['contact_state'] , 0);
        $this->assertEquals(count($data['result']['school']) , 4);
        $this->assertEquals($data['result']['school']['id'] , 1);
        $this->assertEquals($data['result']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['school']['logo'] , null);
        $this->assertEquals($data['result']['id'] , 7);
        $this->assertEquals($data['result']['firstname'] , "Jean");
        $this->assertEquals($data['result']['lastname'] , "Paul");
        $this->assertEquals($data['result']['email'] , "jpaul@thestudnet.com");
        $this->assertEquals($data['result']['birth_date'] , "0000-00-00T00:00:00Z");
        $this->assertEquals($data['result']['position'] , "une position new");
        $this->assertEquals($data['result']['school_id'] , 1);
        $this->assertEquals($data['result']['interest'] , "un interet new");
        $this->assertEquals($data['result']['avatar'] , "un_token_new");
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][0] , "student");
        $this->assertEquals(count($data['result']['program']) , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
    
    // DELETE
    
    /**
     * @depends testCanAddUser
     */
    public function testDelete($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.delete', array('id' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals($data['result'][7], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testAddlanguage()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.addLanguage', array('language' => array('name' => 'french'),'language_level' => 1));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testDeletelanguage()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.deleteLanguage', array('id' => 1));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
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
