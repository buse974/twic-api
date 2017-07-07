<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ItemRateTest extends AbstractService
{
    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testInit()
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
            'location' => 'location',
            'organization_id' => 1,
            'users' => [
                ['user_id' => 1,'role' => 'admin', 'state' => 'member'],
                ['user_id' => 2,'role' => 'admin', 'state' => 'member'],
                ['user_id' => 3,'role' => 'student', 'state' => 'member'],
                ['user_id' => 4,'role' => 'student', 'state' => 'member'],
                ['user_id' => 5,'role' => 'student', 'state' => 'member'],
            ]
        ]);

        $page_id = $data['id'];

        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
          'page_id' => $page_id,
          'title' => 'Section',
          'description' => 'une description de section',
          'type' => 'SCT',
          'is_available' => 1,
          'is_published' => true,
        ]);

        $section_id = $data['id'];

        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
          'page_id' => $page_id,
          'title' => 'Media',
          'description' => 'Media',
          'type' => 'MEDIA',
          'points' => 6,
          'is_available' => 1,
          'is_published' => true,
          'parent_id' => $section_id,
        ]);

        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
          'page_id' => $page_id,
          'title' => 'Assignment',
          'description' => 'une description d\'Assignment',
          'type' => 'A',
          'points' => 7,
          'is_available' => 1,
          'is_published' => true,
          'parent_id' => $section_id,
        ]);


        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
          'page_id' => $page_id,
          'title' => 'Ma Page',
          'description' => 'Page',
          'type' => 'PG',
          'is_available' => 1,
          'is_published' => true,
          'parent_id' => $section_id,
        ]);

        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', [
          'page_id' => $page_id,
          'title' => 'Assignment',
          'description' => 'une description d\'Assignment 2',
          'type' => 'A',
          'points' => 7,
          'is_available' => 1,
          'is_published' => false,
          'parent_id' => $section_id,
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 5);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $page_id;
    }

    /**
    * @depends testInit
    */
    public function testGetListId($id)
    {
      // 5 STUDENT USER
      $this->setIdentity(5);
      $data = $this->jsonRpc('item.getListAssignmentId', [
        'page_id' => $id,
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 1);
      $this->assertEquals(count($data['result'][1]) , 2);
      $this->assertEquals($data['result'][1][0] , 2);
      $this->assertEquals($data['result'][1][1] , 3);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    * @depends testInit
    */
    public function testGetListIdAdmin($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.getListAssignmentId', [
        'page_id' => $id,
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 1);
      $this->assertEquals(count($data['result'][1]) , 3);
      $this->assertEquals($data['result'][1][0] , 2);
      $this->assertEquals($data['result'][1][1] , 3);
      $this->assertEquals($data['result'][1][2] , 5);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    * @depends testInit
    */
    public function testGetListItem($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.getInfo', [
        'id' => [2,3,5],
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 3);
      $this->assertEquals(count($data['result'][2]) , 3);
      $this->assertEquals($data['result'][2]['nb_total'] , 5);
      $this->assertEquals($data['result'][2]['nb_grade'] , 0);
      $this->assertEquals($data['result'][2]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][3]) , 3);
      $this->assertEquals($data['result'][3]['nb_total'] , 5);
      $this->assertEquals($data['result'][3]['nb_grade'] , 0);
      $this->assertEquals($data['result'][3]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][5]) , 3);
      $this->assertEquals($data['result'][5]['nb_total'] , 5);
      $this->assertEquals($data['result'][5]['nb_grade'] , 0);
      $this->assertEquals($data['result'][5]['nb_submission'] , 0);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    * @depends testInit
    */
    public function testGetListSubmission($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.getListSubmission', [
        'id' => [2,3,5],
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 5);
      $this->assertEquals(count($data['result'][0]) , 2);
      $this->assertEquals(count($data['result'][0]['users']) , 1);
      $this->assertEquals($data['result'][0]['users'][0] , 1);
      $this->assertEquals(count($data['result'][0]['sub']) , 4);
      $this->assertEquals($data['result'][0]['sub']['item_id'] , null);
      $this->assertEquals($data['result'][0]['sub']['submit_date'] , null);
      $this->assertEquals($data['result'][0]['sub']['is_graded'] , null);
      $this->assertEquals($data['result'][0]['sub']['post_id'] , null);
      $this->assertEquals(count($data['result'][1]) , 2);
      $this->assertEquals(count($data['result'][1]['users']) , 1);
      $this->assertEquals($data['result'][1]['users'][0] , 2);
      $this->assertEquals(count($data['result'][1]['sub']) , 4);
      $this->assertEquals($data['result'][1]['sub']['item_id'] , null);
      $this->assertEquals($data['result'][1]['sub']['submit_date'] , null);
      $this->assertEquals($data['result'][1]['sub']['is_graded'] , null);
      $this->assertEquals($data['result'][1]['sub']['post_id'] , null);
      $this->assertEquals(count($data['result'][2]) , 2);
      $this->assertEquals(count($data['result'][2]['users']) , 1);
      $this->assertEquals($data['result'][2]['users'][0] , 3);
      $this->assertEquals(count($data['result'][2]['sub']) , 4);
      $this->assertEquals($data['result'][2]['sub']['item_id'] , null);
      $this->assertEquals($data['result'][2]['sub']['submit_date'] , null);
      $this->assertEquals($data['result'][2]['sub']['is_graded'] , null);
      $this->assertEquals($data['result'][2]['sub']['post_id'] , null);
      $this->assertEquals(count($data['result'][3]) , 2);
      $this->assertEquals(count($data['result'][3]['users']) , 1);
      $this->assertEquals($data['result'][3]['users'][0] , 4);
      $this->assertEquals(count($data['result'][3]['sub']) , 4);
      $this->assertEquals($data['result'][3]['sub']['item_id'] , null);
      $this->assertEquals($data['result'][3]['sub']['submit_date'] , null);
      $this->assertEquals($data['result'][3]['sub']['is_graded'] , null);
      $this->assertEquals($data['result'][3]['sub']['post_id'] , null);
      $this->assertEquals(count($data['result'][4]) , 2);
      $this->assertEquals(count($data['result'][4]['users']) , 1);
      $this->assertEquals($data['result'][4]['users'][0] , 5);
      $this->assertEquals(count($data['result'][4]['sub']) , 4);
      $this->assertEquals($data['result'][4]['sub']['item_id'] , null);
      $this->assertEquals($data['result'][4]['sub']['submit_date'] , null);
      $this->assertEquals($data['result'][4]['sub']['is_graded'] , null);
      $this->assertEquals($data['result'][4]['sub']['post_id'] , null);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    * @depends testInit
    */
    public function testGrade($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.grade', [
        'item_id' => 2,
        'rate' => 66,
        'user_id' => 5,
        //'group_id' => null,
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals($data['result'] , true);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    * @depends testInit
    */
    public function testGetListItemAfterRate($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.getInfo', [
        'id' => [2,3,5],
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 3);
      $this->assertEquals(count($data['result'][2]) , 3);
      $this->assertEquals($data['result'][2]['nb_total'] , 5);
      $this->assertEquals($data['result'][2]['nb_grade'] , 1);
      $this->assertEquals($data['result'][2]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][3]) , 3);
      $this->assertEquals($data['result'][3]['nb_total'] , 5);
      $this->assertEquals($data['result'][3]['nb_grade'] , 0);
      $this->assertEquals($data['result'][3]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][5]) , 3);
      $this->assertEquals($data['result'][5]['nb_total'] , 5);
      $this->assertEquals($data['result'][5]['nb_grade'] , 0);
      $this->assertEquals($data['result'][5]['nb_submission'] , 0);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

   /**
    * @depends testInit
    */
    public function testPublishItem($id)
    {
      $this->setIdentity(1);
      $data = $this->jsonRpc('item.publish', [
        'id' => [2,3,5],
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals($data['result'] , 1);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }
}
