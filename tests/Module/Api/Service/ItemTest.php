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
                ['user_id' => 3,'role' => 'student', 'state' => 'member'],
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
          'order' => 1,
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
          'order' => 1,
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
          'order' => 1,
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
          'order' => 1,
          //'start_date',
          //'end_date',
          'parent_id' => $data['id']
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 4);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }


    /**
     * @depends testPageAdd
     */
    public function testItemUpdate($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.update', [
          'id' => 1,
          'title' => 'Ma Section 1',
          'description' => 'une description de section',
          'is_available' => true,
          'is_published' => true,
          'points' => 11,
          'order' => 3,
          //'start_date',
          //'end_date',
          //'parent_id'
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    /**
     * @depends testPageAdd
     */
    public function testGetListId($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.getListId', [
        'page_id' => $id,
      //  'parent_id' =>
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 1);
      $this->assertEquals($data['result'][1] , 3);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testPageAdd
     */
    public function testGetListIdParent($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.getListId', [
        'parent_id' => 1
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 1);
      $this->assertEquals($data['result'][1] , 4);
      $this->assertEquals($data['jsonrpc'] , 2.0); 
    }

    /**
     * @depends testPageAdd
     */
    public function testGet($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.get', [
        'id' => [1,2,3,4]
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 4);
      $this->assertEquals(count($data['result'][1]) , 15);
      $this->assertEquals($data['result'][1]['id'] , 1);
      $this->assertEquals($data['result'][1]['title'] , "Ma Section 1");
      $this->assertEquals($data['result'][1]['description'] , "une description de section");
      $this->assertEquals($data['result'][1]['type'] , "FOLDER");
      $this->assertEquals($data['result'][1]['is_available'] , 1);
      $this->assertEquals($data['result'][1]['is_published'] , 1);
      $this->assertEquals($data['result'][1]['order'] , 3);
      $this->assertEquals($data['result'][1]['start_date'] , null);
      $this->assertEquals($data['result'][1]['end_date'] , null);
      $this->assertEquals(!empty($data['result'][1]['updated_date']) , true);
      $this->assertEquals(!empty($data['result'][1]['created_date']) , true);
      $this->assertEquals($data['result'][1]['parent_id'] , null);
      $this->assertEquals($data['result'][1]['page_id'] , 1);
      $this->assertEquals($data['result'][1]['user_id'] , 1);
      $this->assertEquals($data['result'][1]['points'] , 11);
      $this->assertEquals(count($data['result'][2]) , 15);
      $this->assertEquals($data['result'][2]['id'] , 2);
      $this->assertEquals($data['result'][2]['title'] , "Ma Section 2");
      $this->assertEquals($data['result'][2]['description'] , "une description de section 2");
      $this->assertEquals($data['result'][2]['type'] , "FOLDER");
      $this->assertEquals($data['result'][2]['is_available'] , 0);
      $this->assertEquals($data['result'][2]['is_published'] , 0);
      $this->assertEquals($data['result'][2]['order'] , 1);
      $this->assertEquals($data['result'][2]['start_date'] , null);
      $this->assertEquals($data['result'][2]['end_date'] , null);
      $this->assertEquals($data['result'][2]['updated_date'] , null);
      $this->assertEquals(!empty($data['result'][2]['created_date']) , true);
      $this->assertEquals($data['result'][2]['parent_id'] , null);
      $this->assertEquals($data['result'][2]['page_id'] , 1);
      $this->assertEquals($data['result'][2]['user_id'] , 1);
      $this->assertEquals($data['result'][2]['points'] , 6);
      $this->assertEquals(count($data['result'][3]) , 15);
      $this->assertEquals($data['result'][3]['id'] , 3);
      $this->assertEquals($data['result'][3]['title'] , "Ma Section 3");
      $this->assertEquals($data['result'][3]['description'] , "une description de section 3");
      $this->assertEquals($data['result'][3]['type'] , "FOLDER");
      $this->assertEquals($data['result'][3]['is_available'] , 0);
      $this->assertEquals($data['result'][3]['is_published'] , 0);
      $this->assertEquals($data['result'][3]['order'] , 1);
      $this->assertEquals($data['result'][3]['start_date'] , null);
      $this->assertEquals($data['result'][3]['end_date'] , null);
      $this->assertEquals($data['result'][3]['updated_date'] , null);
      $this->assertEquals(!empty($data['result'][3]['created_date']) , true);
      $this->assertEquals($data['result'][3]['parent_id'] , null);
      $this->assertEquals($data['result'][3]['page_id'] , 1);
      $this->assertEquals($data['result'][3]['user_id'] , 1);
      $this->assertEquals($data['result'][3]['points'] , 7);
      $this->assertEquals(count($data['result'][4]) , 15);
      $this->assertEquals($data['result'][4]['id'] , 4);
      $this->assertEquals($data['result'][4]['title'] , "Ma Section 3");
      $this->assertEquals($data['result'][4]['description'] , "une description de section 3");
      $this->assertEquals($data['result'][4]['type'] , "PG");
      $this->assertEquals($data['result'][4]['is_available'] , 0);
      $this->assertEquals($data['result'][4]['is_published'] , 0);
      $this->assertEquals($data['result'][4]['order'] , 1);
      $this->assertEquals($data['result'][4]['start_date'] , null);
      $this->assertEquals($data['result'][4]['end_date'] , null);
      $this->assertEquals($data['result'][4]['updated_date'] , null);
      $this->assertEquals(!empty($data['result'][4]['created_date']) , true);
      $this->assertEquals($data['result'][4]['parent_id'] , 1);
      $this->assertEquals($data['result'][4]['page_id'] , 1);
      $this->assertEquals($data['result'][4]['user_id'] , 1);
      $this->assertEquals($data['result'][4]['points'] , 10);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

}
