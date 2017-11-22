<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class GroupTest extends AbstractService
{
    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testPageAdd()
    {
        $this->setIdentity(1,1);
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
                ['user_id' => 3,'role' => 'user', 'state' => 'member'],
                ['user_id' => 4,'role' => 'user', 'state' => 'member'],
                ['user_id' => 5,'role' => 'user', 'state' => 'member'],
            ],
            'docs' => [
                ['name' => 'name', 'link' => 'link', 'type' => 'type']
            ]
        ]);
        
        $this->printCreateTest($data);
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['id'];
    }
    
    /**
     * @depends testPageAdd
     */
    public function testItemAdd($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
            'page_id' => $id,
            'title' => 'Ma Section 1',
            'points' => 5,
            'description' => 'une description de section',
            'type' => 'FOLDER',
            'is_available' => false,
            'is_published' => false,
            //'start_date',
            //'end_date',
            //'parent_id'
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
            'page_id' => $id,
            'title' => 'Ma Section 2',
            'description' => 'une description de section 2',
            'type' => 'FOLDER',
            'points' => 6,
            'is_available' => false,
            'is_published' => false,
            //'start_date',
            //'end_date',
            //'parent_id'
        ]);
        
        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
            'page_id' => $id,
            'title' => 'Ma Section 3',
            'description' => 'une description de section 3',
            'type' => 'FOLDER',
            'points' => 7,
            'is_available' => false,
            'is_published' => false,
            //'start_date',
            //'end_date',
            //'parent_id'
        ]);
        
        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
            'page_id' => $id,
            'title' => 'Ma Section 3',
            'description' => 'une description de section 3',
            'type' => 'PG',
            'points' => 10,
            'is_available' => false,
            'is_published' => false,
            //'start_date',
            //'end_date',
            'parent_id' => $data['id']
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 4);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testAddGroup()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('group.add', [
            'item_id' => 1,
            'name' => "ungroup"
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][0] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testAddGroup2()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('group.add', [
            'item_id' => 2,
            'name' => "ungroup2"
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][0] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testGroupGetList()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('group.getList', [
            'item_id' => [1,2]
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][1]) , 1);
        $this->assertEquals(count($data['result'][1][0]) , 3);
        $this->assertEquals($data['result'][1][0]['id'] , 1);
        $this->assertEquals($data['result'][1][0]['name'] , "ungroup");
        $this->assertEquals($data['result'][1][0]['item_id'] , 1);
        $this->assertEquals(count($data['result'][2]) , 1);
        $this->assertEquals(count($data['result'][2][0]) , 3);
        $this->assertEquals($data['result'][2][0]['id'] , 2);
        $this->assertEquals($data['result'][2][0]['name'] , "ungroup2");
        $this->assertEquals($data['result'][2][0]['item_id'] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0); 
    }
    
    public function testDeleteGroup()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('group.delete', [
            'id' => 1,
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testGroupGetList1()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('group.getList', [
            'item_id' => [1,2]
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][1]) , 0);
        $this->assertEquals(count($data['result'][2]) , 1);
        $this->assertEquals(count($data['result'][2][0]) , 3);
        $this->assertEquals($data['result'][2][0]['id'] , 2);
        $this->assertEquals($data['result'][2][0]['name'] , "ungroup2");
        $this->assertEquals($data['result'][2][0]['item_id'] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
}
