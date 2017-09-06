<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class SignInTest extends AbstractService
{
    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testInit()
    {
        // ADD SCHOOL
        $this->setIdentity(1,1);
        $data = $this->jsonRpc('page.add', [
            'title' => 'université de monaco',
            'short_title' => 'IUM buisness school',
            'logo' => 'token',
            'description' => 'une description',
            'website' => 'www.ium.com','programme' => 'super programme','background' => 'background',
            'type' => 'organization',
            'phone' => '+33480547852','contact' => 'contact@ium.com','contact_id' => 1,
            'address' => [
                "street_no" => 12,
                "street_type" => "rue",
                "street_name" => "du stade",
                "city" => ["name" => "Monaco"],
                "country" => ["name" => "Monaco"]
            ]]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
    
    public function testCanAdd()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('preregistration.add', [
            'account_token' => '123456', 
            'firstname' => 'christophe', 
            'lastname' => 'robert', 
            'email' => 'crobert@thestudnet.com', 
            'organization_id' => 1,
            'user_id' => 4
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , '123456');
        $this->assertEquals($data['jsonrpc'] , 2.0); 
    }
    
    public function testCanGet()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('preregistration.get', [
            'account_token' => '123456'
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 6);
        $this->assertEquals($data['result']['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result']['firstname'] , "christophe");
        $this->assertEquals($data['result']['lastname'] , "robert");
        $this->assertEquals($data['result']['organization_id'] , 1);
        $this->assertEquals($data['result']['account_token'] , 123456);
        $this->assertEquals($data['result']['user_id'] , 4);
        $this->assertEquals($data['jsonrpc'] , 2.0); 
    }
    
    public function testCanSignPassword()
    {
        $data = $this->jsonRpc('user.signIn', [
            'account_token' => '123456', 
            'password' => 'tutu'
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 15);
        $this->assertEquals($data['result']['id'] , 4);
        $this->assertEquals(!empty($data['result']['token']) , true);
        $this->assertEquals($data['result']['created_date'] , null);
        $this->assertEquals($data['result']['firstname'] , "Salim");
        $this->assertEquals($data['result']['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['nickname'] , null);
        $this->assertEquals($data['result']['suspension_date'] , null);
        $this->assertEquals($data['result']['suspension_reason'] , null);
        $this->assertEquals($data['result']['organization_id'] , null);
        $this->assertEquals($data['result']['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['avatar'] , null);
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][2] , "user");
        $this->assertEquals(!empty($data['result']['wstoken']) , true);
        $this->assertEquals(!empty($data['result']['fbtoken']) , true);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanAddAddUser()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('preregistration.add', [
            'account_token' => '1234567',
            'firstname' => 'christophe',
            'lastname' => 'robert',
            'email' => 'crobert2@thestudnet.com',
            'organization_id' => 1,
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , '1234567');
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanSignPasswordAddUser()
    {
        $data = $this->jsonRpc('user.signIn', [
            'account_token' => '1234567',
            'password' => 'tutu'
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 15);
        $this->assertEquals($data['result']['id'] , 8);
        $this->assertEquals(!empty($data['result']['token']) , true);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['firstname'] , "christophe");
        $this->assertEquals($data['result']['lastname'] , "robert");
        $this->assertEquals($data['result']['nickname'] , null);
        $this->assertEquals($data['result']['suspension_date'] , null);
        $this->assertEquals($data['result']['suspension_reason'] , null);
        $this->assertEquals($data['result']['organization_id'] , 1);
        $this->assertEquals($data['result']['email'] , "crobert2@thestudnet.com");
        $this->assertEquals($data['result']['avatar'] , null);
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals(count($data['result']['roles']) , 1);
        $this->assertEquals($data['result']['roles'][2] , "user");
        $this->assertEquals(!empty($data['result']['wstoken']) , true);
        $this->assertEquals(!empty($data['result']['fbtoken']) , true);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanAddLinkedin()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('preregistration.add', [
            'account_token' => '12345678',
            'firstname' => 'christophe',
            'lastname' => 'robert',
            'email' => 'crobert3@thestudnet.com',
            'organization_id' => 1,
           // 'user_id' => 1
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , '12345678');
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanSignLinkdIn()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('user.linkedinSignIn', [
           // 'account_token' => '12345678',
            'code' => 'AQSQ5LYJmmq6O-ZdmjtbVoosOlrd_-SZbwbJ8tApSYVzabNN0RInfgmVtT0QY6-VqCo7w3vG90rmxfn7VXKBjBVjXD1r-OVzGzAYdgvFyjuyx5uPBGOaZGn9w1V-zL2duOEDXM81XZNv_E11Sp3wJKi7sTKnDg'
        ]);
        
        print_r($data);
        
    }
    
    /*public function testCanSignLinkdInAfter()
    {
        $data = $this->jsonRpc('user.linkedinSignIn', [
            'code' => 'AQSQ5LYJmmq6O-ZdmjtbVoosOlrd_-SZbwbJ8tApSYVzabNN0RInfgmVtT0QY6-VqCo7w3vG90rmxfn7VXKBjBVjXD1r-OVzGzAYdgvFyjuyx5uPBGOaZGn9w1V-zL2duOEDXM81XZNv_E11Sp3wJKi7sTKnDg'
        ]);
        
        print_r($data);
    }*/
    
    

}
