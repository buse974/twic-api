<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ItemTest extends AbstractService
{
    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testPageAdd()
    {
        $this->setIdentity(1, 1);
        $data = $this->jsonRpc('page.add', [
            'title' => 'super title',
            'logo' => 'logo',
            'background' => 'background',
            'description' => 'description',
            'confidentiality' => 1,
            'type' => 'course',
            'admission' => 'free',
            'start_date' => '2015-00-00 00:00:00',
            'end_date' => '2016-00-00 00:00:00',
            'location' => 'location',
            'organization_id' => 1,
            'users' => [
                ['user_id' => 1,'role' => 'admin', 'state' => 'member'],
                ['user_id' => 2,'role' => 'admin', 'state' => 'member'],
                ['user_id' => 3,'role' => 'admin', 'state' => 'member'],
            ],
            'docs' => [
                ['name' => 'name', 'link' => 'link', 'type' => 'type']
            ]
        ]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);

        return $data['id'];
    }

    /**
     * @depends testPageAdd
     */
    public function testPageUserAdd($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.add', [
          'page_id' => $id,
          'user_id' => 4,
          'role' => 'admin',
          'state' => 'member'
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

    }

    /**
     * @depends testPageAdd
     */
    public function testPageUserUpdate($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.update', [
          'page_id' => $id,
          'user_id' => 4,
          'role' => 'student',
          'state' => 'member'
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    
}
