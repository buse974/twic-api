<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ExternalTest extends AbstractService
{

    public static $session;
    public static $token;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testUserAdd()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.add', [
            'firstname' => 'firstname_external' , 
            'lastname' => 'lastname_external',
            'email' => 'external@free.fr',
            'roles' => 'external',
            'password' => 'toto',
            'school_id' => 1,
        ]);
       
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 8);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
        
    public function testAuth()
    {
        $this->mockRbac();
        $data = $this->jsonRpc('user.auth', array('user' => 'external@free.fr' , 'password' => 'toto'));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 9);
        $this->assertEquals($data['result']['id'] , 8);
        $this->assertEquals(!empty($data['result']['token']) , true);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['firstname'] , "firstname_external");
        $this->assertEquals($data['result']['lastname'] , "lastname_external");
        $this->assertEquals($data['result']['email'] , "external@free.fr");
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][7] , "external");
        $this->assertEquals(count($data['result']['school']) , 5);
        $this->assertEquals($data['result']['school']['id'] , 1);
        $this->assertEquals($data['result']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['school']['logo'] , null);
        $this->assertEquals($data['result']['school']['background'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        self::$token = $data['result']['token'];
    }
    
    public function testUserAddExtern()
    {
        $this->mockRbac();
        $data = $this->jsonRpc('user.create', array(
            'email' => 'u1@univ.fr', 'firstname' => 'f1', 'lastname' => 'l1', 'uid' => 'azerty'), self::$token);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 19);
        $this->assertEquals(count($data['result']['origin']) , 2);
        $this->assertEquals($data['result']['origin']['id'] , null);
        $this->assertEquals($data['result']['origin']['short_name'] , null);
        $this->assertEquals(count($data['result']['nationality']) , 2);
        $this->assertEquals($data['result']['nationality']['id'] , null);
        $this->assertEquals($data['result']['nationality']['short_name'] , null);
        $this->assertEquals($data['result']['gender'] , null);
        $this->assertEquals($data['result']['contact_state'] , 3);
        $this->assertEquals($data['result']['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['school']) , 5);
        $this->assertEquals($data['result']['school']['id'] , 1);
        $this->assertEquals($data['result']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['school']['logo'] , null);
        $this->assertEquals($data['result']['school']['background'] , null);
        $this->assertEquals($data['result']['id'] , 9);
        $this->assertEquals($data['result']['firstname'] , "f1");
        $this->assertEquals($data['result']['lastname'] , "l1");
        $this->assertEquals($data['result']['email'] , "u1@univ.fr");
        $this->assertEquals($data['result']['birth_date'] , null);
        $this->assertEquals($data['result']['position'] , null);
        $this->assertEquals($data['result']['school_id'] , 1);
        $this->assertEquals($data['result']['interest'] , null);
        $this->assertEquals($data['result']['avatar'] , null);
        $this->assertEquals($data['result']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['background'] , null);
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][0] , "student");
        $this->assertEquals(count($data['result']['program']) , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testUserListExtern()
    {
        $this->mockRbac();
        $data = $this->jsonRpc('user.listing', [], self::$token);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 9);
        $this->assertEquals(count($data['result']['list'][0]) , 13);
        $this->assertEquals($data['result']['list'][0]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][0]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][0]['school']) , 5);
        $this->assertEquals($data['result']['list'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 9);
        $this->assertEquals($data['result']['list'][0]['firstname'] , "f1");
        $this->assertEquals($data['result']['list'][0]['lastname'] , "l1");
        $this->assertEquals($data['result']['list'][0]['email'] , "u1@univ.fr");
        $this->assertEquals($data['result']['list'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][0]['position'] , null);
        $this->assertEquals($data['result']['list'][0]['interest'] , null);
        $this->assertEquals($data['result']['list'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][0]['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['list'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][1]) , 13);
        $this->assertEquals($data['result']['list'][1]['contact_state'] , 0);
        $this->assertEquals($data['result']['list'][1]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][1]['school']) , 5);
        $this->assertEquals($data['result']['list'][1]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][1]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][1]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][1]['id'] , 8);
        $this->assertEquals($data['result']['list'][1]['firstname'] , "firstname_external");
        $this->assertEquals($data['result']['list'][1]['lastname'] , "lastname_external");
        $this->assertEquals($data['result']['list'][1]['email'] , "external@free.fr");
        $this->assertEquals($data['result']['list'][1]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][1]['position'] , null);
        $this->assertEquals($data['result']['list'][1]['interest'] , null);
        $this->assertEquals($data['result']['list'][1]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][1]['roles']) , 1);
        $this->assertEquals($data['result']['list'][1]['roles'][0] , "external");
        $this->assertEquals(count($data['result']['list'][1]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][2]) , 13);
        $this->assertEquals($data['result']['list'][2]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][2]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][2]['school']) , 5);
        $this->assertEquals($data['result']['list'][2]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][2]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][2]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][2]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][2]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][2]['id'] , 7);
        $this->assertEquals($data['result']['list'][2]['firstname'] , "Arthur");
        $this->assertEquals($data['result']['list'][2]['lastname'] , "Flachs");
        $this->assertEquals($data['result']['list'][2]['email'] , "aflachs@thestudnet.com");
        $this->assertEquals($data['result']['list'][2]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][2]['position'] , null);
        $this->assertEquals($data['result']['list'][2]['interest'] , null);
        $this->assertEquals($data['result']['list'][2]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][2]['roles']) , 1);
        $this->assertEquals($data['result']['list'][2]['roles'][0] , "super_admin");
        $this->assertEquals(count($data['result']['list'][2]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][3]) , 13);
        $this->assertEquals($data['result']['list'][3]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][3]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][3]['school']) , 5);
        $this->assertEquals($data['result']['list'][3]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][3]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][3]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][3]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][3]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][3]['id'] , 6);
        $this->assertEquals($data['result']['list'][3]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['list'][3]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['list'][3]['email'] , "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['list'][3]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][3]['position'] , null);
        $this->assertEquals($data['result']['list'][3]['interest'] , null);
        $this->assertEquals($data['result']['list'][3]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][3]['roles']) , 1);
        $this->assertEquals($data['result']['list'][3]['roles'][0] , "admin");
        $this->assertEquals(count($data['result']['list'][3]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][4]) , 13);
        $this->assertEquals($data['result']['list'][4]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][4]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][4]['school']) , 5);
        $this->assertEquals($data['result']['list'][4]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][4]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][4]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][4]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][4]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][4]['id'] , 5);
        $this->assertEquals($data['result']['list'][4]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['list'][4]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['list'][4]['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][4]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][4]['position'] , null);
        $this->assertEquals($data['result']['list'][4]['interest'] , null);
        $this->assertEquals($data['result']['list'][4]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][4]['roles']) , 1);
        $this->assertEquals($data['result']['list'][4]['roles'][0] , "instructor");
        $this->assertEquals(count($data['result']['list'][4]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][5]) , 13);
        $this->assertEquals($data['result']['list'][5]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][5]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][5]['school']) , 5);
        $this->assertEquals($data['result']['list'][5]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][5]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][5]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][5]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][5]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][5]['id'] , 4);
        $this->assertEquals($data['result']['list'][5]['firstname'] , "Salim");
        $this->assertEquals($data['result']['list'][5]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['list'][5]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['list'][5]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][5]['position'] , null);
        $this->assertEquals($data['result']['list'][5]['interest'] , null);
        $this->assertEquals($data['result']['list'][5]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][5]['roles']) , 1);
        $this->assertEquals($data['result']['list'][5]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['list'][5]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][6]) , 13);
        $this->assertEquals($data['result']['list'][6]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][6]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][6]['school']) , 5);
        $this->assertEquals($data['result']['list'][6]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][6]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][6]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][6]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][6]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][6]['id'] , 3);
        $this->assertEquals($data['result']['list'][6]['firstname'] , "Christophe");
        $this->assertEquals($data['result']['list'][6]['lastname'] , "Robert");
        $this->assertEquals($data['result']['list'][6]['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][6]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][6]['position'] , null);
        $this->assertEquals($data['result']['list'][6]['interest'] , null);
        $this->assertEquals($data['result']['list'][6]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][6]['roles']) , 1);
        $this->assertEquals($data['result']['list'][6]['roles'][0] , "academic");
        $this->assertEquals(count($data['result']['list'][6]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][7]) , 13);
        $this->assertEquals($data['result']['list'][7]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][7]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][7]['school']) , 5);
        $this->assertEquals($data['result']['list'][7]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][7]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][7]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][7]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][7]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][7]['id'] , 2);
        $this->assertEquals($data['result']['list'][7]['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['list'][7]['lastname'] , "Hoang");
        $this->assertEquals($data['result']['list'][7]['email'] , "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][7]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][7]['position'] , null);
        $this->assertEquals($data['result']['list'][7]['interest'] , null);
        $this->assertEquals($data['result']['list'][7]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][7]['roles']) , 1);
        $this->assertEquals($data['result']['list'][7]['roles'][0] , "admin");
        $this->assertEquals(count($data['result']['list'][7]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][8]) , 13);
        $this->assertEquals($data['result']['list'][8]['contact_state'] , 3);
        $this->assertEquals($data['result']['list'][8]['contacts_count'] , 8);
        $this->assertEquals(count($data['result']['list'][8]['school']) , 5);
        $this->assertEquals($data['result']['list'][8]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][8]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][8]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][8]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][8]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][8]['id'] , 1);
        $this->assertEquals($data['result']['list'][8]['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][8]['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][8]['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][8]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][8]['position'] , null);
        $this->assertEquals($data['result']['list'][8]['interest'] , null);
        $this->assertEquals($data['result']['list'][8]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][8]['roles']) , 1);
        $this->assertEquals($data['result']['list'][8]['roles'][0] , "super_admin");
        $this->assertEquals(count($data['result']['list'][8]['program']) , 0);
        $this->assertEquals($data['result']['count'] , 9);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }

}
