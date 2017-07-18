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
                ['user_id' => 3,'role' => 'user', 'state' => 'member'],
                ['user_id' => 4,'role' => 'user', 'state' => 'member'],
                ['user_id' => 5,'role' => 'user', 'state' => 'member'],
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
      $this->assertEquals($data['result'][2]['nb_total'] , 3);
      $this->assertEquals($data['result'][2]['nb_grade'] , 0);
      $this->assertEquals($data['result'][2]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][3]) , 3);
      $this->assertEquals($data['result'][3]['nb_total'] , 3);
      $this->assertEquals($data['result'][3]['nb_grade'] , 0);
      $this->assertEquals($data['result'][3]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][5]) , 3);
      $this->assertEquals($data['result'][5]['nb_total'] , 3);
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
      $this->assertEquals(count($data['result']) , 3);
      $this->assertEquals(count($data['result'][2]) , 3);
      $this->assertEquals(count($data['result'][2][0]) , 5);
      $this->assertEquals($data['result'][2][0]['group_id'] , null);
      $this->assertEquals($data['result'][2][0]['rate'] , null);
      $this->assertEquals(count($data['result'][2][0]['users']) , 1);
      $this->assertEquals($data['result'][2][0]['users'][0] , 3);
      $this->assertEquals($data['result'][2][0]['submit_date'] , null);
      $this->assertEquals($data['result'][2][0]['post_id'] , null);
      $this->assertEquals(count($data['result'][2][1]) , 5);
      $this->assertEquals($data['result'][2][1]['group_id'] , null);
      $this->assertEquals($data['result'][2][1]['rate'] , null);
      $this->assertEquals(count($data['result'][2][1]['users']) , 1);
      $this->assertEquals($data['result'][2][1]['users'][0] , 4);
      $this->assertEquals($data['result'][2][1]['submit_date'] , null);
      $this->assertEquals($data['result'][2][1]['post_id'] , null);
      $this->assertEquals(count($data['result'][2][2]) , 5);
      $this->assertEquals($data['result'][2][2]['group_id'] , null);
      $this->assertEquals($data['result'][2][2]['rate'] , null);
      $this->assertEquals(count($data['result'][2][2]['users']) , 1);
      $this->assertEquals($data['result'][2][2]['users'][0] , 5);
      $this->assertEquals($data['result'][2][2]['submit_date'] , null);
      $this->assertEquals($data['result'][2][2]['post_id'] , null);
      $this->assertEquals(count($data['result'][3]) , 3);
      $this->assertEquals(count($data['result'][3][0]) , 5);
      $this->assertEquals($data['result'][3][0]['group_id'] , null);
      $this->assertEquals($data['result'][3][0]['rate'] , null);
      $this->assertEquals(count($data['result'][3][0]['users']) , 1);
      $this->assertEquals($data['result'][3][0]['users'][0] , 3);
      $this->assertEquals($data['result'][3][0]['submit_date'] , null);
      $this->assertEquals($data['result'][3][0]['post_id'] , null);
      $this->assertEquals(count($data['result'][3][1]) , 5);
      $this->assertEquals($data['result'][3][1]['group_id'] , null);
      $this->assertEquals($data['result'][3][1]['rate'] , null);
      $this->assertEquals(count($data['result'][3][1]['users']) , 1);
      $this->assertEquals($data['result'][3][1]['users'][0] , 4);
      $this->assertEquals($data['result'][3][1]['submit_date'] , null);
      $this->assertEquals($data['result'][3][1]['post_id'] , null);
      $this->assertEquals(count($data['result'][3][2]) , 5);
      $this->assertEquals($data['result'][3][2]['group_id'] , null);
      $this->assertEquals($data['result'][3][2]['rate'] , null);
      $this->assertEquals(count($data['result'][3][2]['users']) , 1);
      $this->assertEquals($data['result'][3][2]['users'][0] , 5);
      $this->assertEquals($data['result'][3][2]['submit_date'] , null);
      $this->assertEquals($data['result'][3][2]['post_id'] , null);
      $this->assertEquals(count($data['result'][5]) , 3);
      $this->assertEquals(count($data['result'][5][0]) , 5);
      $this->assertEquals($data['result'][5][0]['group_id'] , null);
      $this->assertEquals($data['result'][5][0]['rate'] , null);
      $this->assertEquals(count($data['result'][5][0]['users']) , 1);
      $this->assertEquals($data['result'][5][0]['users'][0] , 3);
      $this->assertEquals($data['result'][5][0]['submit_date'] , null);
      $this->assertEquals($data['result'][5][0]['post_id'] , null);
      $this->assertEquals(count($data['result'][5][1]) , 5);
      $this->assertEquals($data['result'][5][1]['group_id'] , null);
      $this->assertEquals($data['result'][5][1]['rate'] , null);
      $this->assertEquals(count($data['result'][5][1]['users']) , 1);
      $this->assertEquals($data['result'][5][1]['users'][0] , 4);
      $this->assertEquals($data['result'][5][1]['submit_date'] , null);
      $this->assertEquals($data['result'][5][1]['post_id'] , null);
      $this->assertEquals(count($data['result'][5][2]) , 5);
      $this->assertEquals($data['result'][5][2]['group_id'] , null);
      $this->assertEquals($data['result'][5][2]['rate'] , null);
      $this->assertEquals(count($data['result'][5][2]['users']) , 1);
      $this->assertEquals($data['result'][5][2]['users'][0] , 5);
      $this->assertEquals($data['result'][5][2]['submit_date'] , null);
      $this->assertEquals($data['result'][5][2]['post_id'] , null);
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
      $this->assertEquals($data['result'][2]['nb_total'] , 3);
      $this->assertEquals($data['result'][2]['nb_grade'] , 1);
      $this->assertEquals($data['result'][2]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][3]) , 3);
      $this->assertEquals($data['result'][3]['nb_total'] , 3);
      $this->assertEquals($data['result'][3]['nb_grade'] , 0);
      $this->assertEquals($data['result'][3]['nb_submission'] , 0);
      $this->assertEquals(count($data['result'][5]) , 3);
      $this->assertEquals($data['result'][5]['nb_total'] , 3);
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

    /**
     * @depends testInit
     */
     public function testAddText($id)
     {
       $this->setIdentity(1);
       $data = $this->jsonRpc('library.add', [
         'name' => 'super file',
         'text' => 'super cool',
       ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 13);
        $this->assertEquals($data['result']['id'] , 4);
        $this->assertEquals($data['result']['name'] , "super file");
        $this->assertEquals($data['result']['link'] , null);
        $this->assertEquals($data['result']['token'] , null);
        $this->assertEquals($data['result']['type'] , "text");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals($data['result']['updated_date'] , null);
        $this->assertEquals($data['result']['folder_id'] , null);
        $this->assertEquals($data['result']['owner_id'] , 1);
        $this->assertEquals($data['result']['box_id'] , null);
        $this->assertEquals($data['result']['global'] , 0);
        $this->assertEquals($data['result']['text'] , "super cool");
        $this->assertEquals($data['jsonrpc'] , 2.0);

       return $data['id'];
     }

     /**
      * @depends testInit
      * @depends testAddText
      */
      public function testAddSubmission($id, $library_id)
      {
        $this->setIdentity(5);
        $data = $this->jsonRpc('submission.add', [
          'item_id' => 2,
          'library_id' => $library_id
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['result'];
      }

      /**
       * @depends testAddSubmission
       */
       public function testSubmitSubmission($submission_id)
       {
         $this->setIdentity(5);
         $data = $this->jsonRpc('submission.submit', [
           'id' => $submission_id
         ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
       }


       /**
        * @depends testAddSubmission
        */
       public function testGetLibrary($submission_id)
       {
          $this->setIdentity(1);
          $data = $this->jsonRpc('submission.getListLibrary', [
            'id' => $submission_id
          ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals(count($data['result']) , 1);
          $this->assertEquals(count($data['result'][1]) , 1);
          $this->assertEquals($data['result'][1][0] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
        * @depends testAddSubmission
        * @depends testAddText
        */
       public function testRemoveLibrary($submission_id, $library_id)
       {
           $this->setIdentity(5);
           $data = $this->jsonRpc('submission.remove', [
             'library_id' => $library_id,
             //'submission_id' => $submission_id
           ]);

           $this->assertEquals(count($data) , 3);
           $this->assertEquals($data['id'] , 1);
           $this->assertEquals($data['result'] , 1);
           $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
        * @depends testAddSubmission
        */
       public function testGetLibraryAfterRemove($submission_id)
       {
           $this->setIdentity(1);
           $data = $this->jsonRpc('submission.getListLibrary', [
             'id' => $submission_id
           ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals(count($data['result']) , 0);
          $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       * @depends testInit
       */
       public function testGetListItemAfterRateSubmit($id)
       {
         $this->setIdentity(1);
         $data = $this->jsonRpc('item.getInfo', [
           'id' => [2,3,5],
         ]);

         $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 3);
        $this->assertEquals(count($data['result'][2]) , 3);
        $this->assertEquals($data['result'][2]['nb_total'] , 3);
        $this->assertEquals($data['result'][2]['nb_grade'] , 1);
        $this->assertEquals($data['result'][2]['nb_submission'] , 1);
        $this->assertEquals(count($data['result'][3]) , 3);
        $this->assertEquals($data['result'][3]['nb_total'] , 3);
        $this->assertEquals($data['result'][3]['nb_grade'] , 0);
        $this->assertEquals($data['result'][3]['nb_submission'] , 0);
        $this->assertEquals(count($data['result'][5]) , 3);
        $this->assertEquals($data['result'][5]['nb_total'] , 3);
        $this->assertEquals($data['result'][5]['nb_grade'] , 0);
        $this->assertEquals($data['result'][5]['nb_submission'] , 0);
        $this->assertEquals($data['jsonrpc'] , 2.0);
       }


       /**
       * @depends testInit
       */
       public function testAddQuiz($page_id)
       {
         //TODO a passer en parametre
         $section_id = 1;
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('item.add', [
           'page_id' => $page_id,
           'title' => 'Assignment',
           'description' => 'un super quiz',
           'type' => 'QUIZ',
           'points' => 7,
           'is_available' => 1,
           'is_published' => false,
           'parent_id' => $section_id,
         ]);

         $item_quiz = $data['result'];
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('quiz.add', [
           'name' => 'super quiz',
           'item_id' => $item_quiz
         ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals($data['result'] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);

          return $item_quiz;
       }

       /**
       *
       * @depends testAddQuiz
       **/
       public function testItemGetForCheckQuiz($item_quiz)
       {
           $this->setIdentity(1);
           $data = $this->jsonRpc('item.get', [
             'id' => [$item_quiz]
           ]);

           $this->assertEquals(count($data) , 3);
           $this->assertEquals($data['id'] , 1);
           $this->assertEquals(count($data['result']) , 1);
           $this->assertEquals(count($data['result'][6]) , 20);
           $this->assertEquals($data['result'][6]['post_id'] , null);
           $this->assertEquals($data['result'][6]['quiz_id'] , 1);
           $this->assertEquals($data['result'][6]['id'] , 6);
           $this->assertEquals($data['result'][6]['title'] , "Assignment");
           $this->assertEquals($data['result'][6]['description'] , "un super quiz");
           $this->assertEquals($data['result'][6]['type'] , "QUIZ");
           $this->assertEquals($data['result'][6]['is_available'] , 1);
           $this->assertEquals($data['result'][6]['is_published'] , 0);
           $this->assertEquals($data['result'][6]['order'] , 5);
           $this->assertEquals($data['result'][6]['start_date'] , null);
           $this->assertEquals($data['result'][6]['end_date'] , null);
           $this->assertEquals($data['result'][6]['updated_date'] , null);
           $this->assertEquals(!empty($data['result'][6]['created_date']) , true);
           $this->assertEquals($data['result'][6]['parent_id'] , 1);
           $this->assertEquals($data['result'][6]['page_id'] , 1);
           $this->assertEquals($data['result'][6]['user_id'] , 1);
           $this->assertEquals($data['result'][6]['points'] , 7);
           $this->assertEquals($data['result'][6]['text'] , null);
           $this->assertEquals($data['result'][6]['library_id'] , null);
           $this->assertEquals($data['result'][6]['participants'] , "all");
           $this->assertEquals($data['jsonrpc'] , 2.0);
       }


}
