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
          'start_date' => '2015-10-10',
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
          'start_date' => '2015-10-12',
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
          'start_date' => '2015-10-14',
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
          'start_date' => '2015-10-16',
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
        $this->assertEquals($data['result'] , 8);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $page_id;
    }

    public function testItemGetListTimeline()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.getListTimeline', [
          'filter' => [
            'n' => 1,
            'p' => 1,
            'c' => ['item.order_date' => '<'],
            'o' => ['item.order_date DESC'],
            's' => '2015-10-13'
          ]
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 5);
        $this->assertEquals(!empty($data['result'][0]['order_date']) , true);
        $this->assertEquals($data['result'][0]['timeline_type'] , "S");
        $this->assertEquals($data['result'][0]['id'] , 4);
        $this->assertEquals($data['result'][0]['parent_id'] , 1);
        $this->assertEquals($data['result'][0]['page_id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
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
      $this->assertEquals(count($data['result'][1]) , 5);
      $this->assertEquals($data['result'][1][0] , 2);
      $this->assertEquals($data['result'][1][1] , 3);
      $this->assertEquals($data['result'][1][2] , 4);
      $this->assertEquals($data['result'][1][3] , 5);
      $this->assertEquals($data['result'][1][4] , 6);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    * @depends testInit
    */
    public function testGetListIdPagination($id)
    {
      // 5 STUDENT USER
      $this->setIdentity(5);
      $data = $this->jsonRpc('item.getListAssignmentId', [
        'page_id' => $id,
        'filter' => [
          's' => '0',
          'n' => 10,
          'p' => 1,
          'd' => '<',
        ]
      ]);

      //@TODO p!!!!!!!! our la coverture a faire de vrai test !!!!!!!!
      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 0);
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
      $this->assertEquals(count($data['result'][2][0]) , 6);
      $this->assertEquals($data['result'][2][0]['group_id'] , null);
      $this->assertEquals(count($data['result'][2][0]['rate']) , 0);
      $this->assertEquals(count($data['result'][2][0]['users']) , 1);
      $this->assertEquals($data['result'][2][0]['users'][0] , 3);
      $this->assertEquals($data['result'][2][0]['submit_date'] , null);
      $this->assertEquals($data['result'][2][0]['post_id'] , null);
      $this->assertEquals($data['result'][2][0]['item_id'] , 2);
      $this->assertEquals(count($data['result'][2][1]) , 6);
      $this->assertEquals($data['result'][2][1]['group_id'] , null);
      $this->assertEquals(count($data['result'][2][1]['rate']) , 0);
      $this->assertEquals(count($data['result'][2][1]['users']) , 1);
      $this->assertEquals($data['result'][2][1]['users'][0] , 4);
      $this->assertEquals($data['result'][2][1]['submit_date'] , null);
      $this->assertEquals($data['result'][2][1]['post_id'] , null);
      $this->assertEquals($data['result'][2][1]['item_id'] , 2);
      $this->assertEquals(count($data['result'][2][2]) , 6);
      $this->assertEquals($data['result'][2][2]['group_id'] , null);
      $this->assertEquals(count($data['result'][2][2]['rate']) , 0);
      $this->assertEquals(count($data['result'][2][2]['users']) , 1);
      $this->assertEquals($data['result'][2][2]['users'][0] , 5);
      $this->assertEquals($data['result'][2][2]['submit_date'] , null);
      $this->assertEquals($data['result'][2][2]['post_id'] , null);
      $this->assertEquals($data['result'][2][2]['item_id'] , 2);
      $this->assertEquals(count($data['result'][3]) , 3);
      $this->assertEquals(count($data['result'][3][0]) , 6);
      $this->assertEquals($data['result'][3][0]['group_id'] , null);
      $this->assertEquals(count($data['result'][3][0]['rate']) , 0);
      $this->assertEquals(count($data['result'][3][0]['users']) , 1);
      $this->assertEquals($data['result'][3][0]['users'][0] , 3);
      $this->assertEquals($data['result'][3][0]['submit_date'] , null);
      $this->assertEquals($data['result'][3][0]['post_id'] , null);
      $this->assertEquals($data['result'][3][0]['item_id'] , 3);
      $this->assertEquals(count($data['result'][3][1]) , 6);
      $this->assertEquals($data['result'][3][1]['group_id'] , null);
      $this->assertEquals(count($data['result'][3][1]['rate']) , 0);
      $this->assertEquals(count($data['result'][3][1]['users']) , 1);
      $this->assertEquals($data['result'][3][1]['users'][0] , 4);
      $this->assertEquals($data['result'][3][1]['submit_date'] , null);
      $this->assertEquals($data['result'][3][1]['post_id'] , null);
      $this->assertEquals($data['result'][3][1]['item_id'] , 3);
      $this->assertEquals(count($data['result'][3][2]) , 6);
      $this->assertEquals($data['result'][3][2]['group_id'] , null);
      $this->assertEquals(count($data['result'][3][2]['rate']) , 0);
      $this->assertEquals(count($data['result'][3][2]['users']) , 1);
      $this->assertEquals($data['result'][3][2]['users'][0] , 5);
      $this->assertEquals($data['result'][3][2]['submit_date'] , null);
      $this->assertEquals($data['result'][3][2]['post_id'] , null);
      $this->assertEquals($data['result'][3][2]['item_id'] , 3);
      $this->assertEquals(count($data['result'][5]) , 3);
      $this->assertEquals(count($data['result'][5][0]) , 6);
      $this->assertEquals($data['result'][5][0]['group_id'] , null);
      $this->assertEquals(count($data['result'][5][0]['rate']) , 0);
      $this->assertEquals(count($data['result'][5][0]['users']) , 1);
      $this->assertEquals($data['result'][5][0]['users'][0] , 3);
      $this->assertEquals($data['result'][5][0]['submit_date'] , null);
      $this->assertEquals($data['result'][5][0]['post_id'] , null);
      $this->assertEquals($data['result'][5][0]['item_id'] , 5);
      $this->assertEquals(count($data['result'][5][1]) , 6);
      $this->assertEquals($data['result'][5][1]['group_id'] , null);
      $this->assertEquals(count($data['result'][5][1]['rate']) , 0);
      $this->assertEquals(count($data['result'][5][1]['users']) , 1);
      $this->assertEquals($data['result'][5][1]['users'][0] , 4);
      $this->assertEquals($data['result'][5][1]['submit_date'] , null);
      $this->assertEquals($data['result'][5][1]['post_id'] , null);
      $this->assertEquals($data['result'][5][1]['item_id'] , 5);
      $this->assertEquals(count($data['result'][5][2]) , 6);
      $this->assertEquals($data['result'][5][2]['group_id'] , null);
      $this->assertEquals(count($data['result'][5][2]['rate']) , 0);
      $this->assertEquals(count($data['result'][5][2]['users']) , 1);
      $this->assertEquals($data['result'][5][2]['users'][0] , 5);
      $this->assertEquals($data['result'][5][2]['submit_date'] , null);
      $this->assertEquals($data['result'][5][2]['post_id'] , null);
      $this->assertEquals($data['result'][5][2]['item_id'] , 5);
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
        'id' => [2,3,8],
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
            'item_id' => 2
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
            'item_id' => 2
          ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals(count($data['result']) , 1);
          $this->assertEquals($data['result'][0] , 1);
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
             'item_id' => 2
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
           'item_id' => $item_quiz,
           'questions' => [
              [
                'text' => "une question 1",
                'points' => 5,
                'type' => 'simple',
                'answers' =>  [['text' => 'la une', 'is_correct' => true], ['text' => 'la deux', 'is_correct' => false], ['text' => 'la trois', 'is_correct' => false]]
              ],
              [
                'text' => "une question 2",
                'points' => 6,
                'type' => 'multiple',
                'answers' =>  [['text' => 'la une2', 'is_correct' => true], ['text' => 'la deux2', 'is_correct' => false], ['text' => 'la trois2', 'is_correct' => true]]
              ],
              [
                'text' => "une question 3",
                'points' => 6,
                'type' => 'text',
                'answers' =>  [['text' => 'la une3']]
              ],
           ],
         ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals($data['result'] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);

          return [ 'item_quiz' => $item_quiz , 'quiz_id' => $data['result'] ];
       }

       /**
       * @depends testAddQuiz
       */
       public function testGetQuiz($quiz_id)
       {
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('quiz.get', [
           'id' => $quiz_id['quiz_id']
         ]);

         $this->assertEquals(count($data) , 3);
         $this->assertEquals($data['id'] , 1);
         $this->assertEquals(count($data['result']) , 1);
         $this->assertEquals(count($data['result'][1]) , 8);
         $this->assertEquals(count($data['result'][1]['quiz_question']) , 3);
         $this->assertEquals(count($data['result'][1]['quiz_question'][0]) , 7);
         $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer']) , 3);
         $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][0]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['quiz_question_id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['text'] , "la une");
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['is_correct'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['order'] , null);
         $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][1]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['id'] , 2);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['quiz_question_id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['text'] , "la deux");
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['is_correct'] , 0);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['order'] , null);
         $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][2]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['id'] , 3);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['quiz_question_id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['text'] , "la trois");
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['is_correct'] , 0);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['order'] , null);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['point'] , null);
         $this->assertEquals($data['result'][1]['quiz_question'][0]['text'] , "une question 1");
         $this->assertEquals($data['result'][1]['quiz_question'][0]['type'] , "simple");
         $this->assertEquals($data['result'][1]['quiz_question'][0]['order'] , null);
         $this->assertEquals(count($data['result'][1]['quiz_question'][1]) , 7);
         $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer']) , 3);
         $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][0]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['id'] , 4);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['quiz_question_id'] , 2);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['text'] , "la une2");
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['is_correct'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['order'] , null);
         $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][1]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['id'] , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['quiz_question_id'] , 2);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['text'] , "la deux2");
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['is_correct'] , 0);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['order'] , null);
         $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][2]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['id'] , 6);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['quiz_question_id'] , 2);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['text'] , "la trois2");
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['is_correct'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['order'] , null);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['id'] , 2);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['point'] , null);
         $this->assertEquals($data['result'][1]['quiz_question'][1]['text'] , "une question 2");
         $this->assertEquals($data['result'][1]['quiz_question'][1]['type'] , "multiple");
         $this->assertEquals($data['result'][1]['quiz_question'][1]['order'] , null);
         $this->assertEquals(count($data['result'][1]['quiz_question'][2]) , 7);
         $this->assertEquals(count($data['result'][1]['quiz_question'][2]['quiz_answer']) , 1);
         $this->assertEquals(count($data['result'][1]['quiz_question'][2]['quiz_answer'][0]) , 5);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['id'] , 7);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['quiz_question_id'] , 3);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['text'] , "la une3");
         $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['is_correct'] , 0);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['order'] , null);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['id'] , 3);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_id'] , 1);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['point'] , null);
         $this->assertEquals($data['result'][1]['quiz_question'][2]['text'] , "une question 3");
         $this->assertEquals($data['result'][1]['quiz_question'][2]['type'] , "text");
         $this->assertEquals($data['result'][1]['quiz_question'][2]['order'] , null);
         $this->assertEquals($data['result'][1]['id'] , 1);
         $this->assertEquals($data['result'][1]['name'] , "super quiz");
         $this->assertEquals($data['result'][1]['attempt_count'] , null);
         $this->assertEquals($data['result'][1]['time_limit'] , null);
         $this->assertEquals($data['result'][1]['created_date'] , null);
         $this->assertEquals($data['result'][1]['item_id'] , 9);
         $this->assertEquals($data['result'][1]['user_id'] , 1);
         $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       * @depends testAddQuiz
       */
       public function testUpdateQuestion($quiz_id)
       {
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('quiz.updateQuestions', [
          'questions' => [
             ['id' => 1, 'text' => 'nouveau text', 'type' => 'multiple', 'point' =>  18]
           ]
         ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][0] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('quiz.updateQuestions', [
         'questions' => [
            ['id' => 2, 'text' => 'nouveau text', 'type' => 'simple', 'point' =>  18]
          ]
        ]);

       $this->assertEquals(count($data) , 3);
       $this->assertEquals($data['id'] , 1);
       $this->assertEquals(count($data['result']) , 1);
       $this->assertEquals($data['result'][0] , 1);
       $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       * @depends testAddQuiz
       */
       public function testUpdateAnswers($quiz_id)
       {
          $this->reset();
          $this->setIdentity(1);
          $data = $this->jsonRpc('quiz.updateAnswers', [
            'answers' => [
              ['id' => 3, 'text' => 'nouveau text answer', 'is_correct' => true]
            ]
          ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals(count($data['result']) , 1);
          $this->assertEquals($data['result'][0] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);

          $this->reset();
          $this->setIdentity(1);
          $data = $this->jsonRpc('quiz.updateAnswers', [
            'answers' => [
              ['id' => 4, 'text' => 'nouveau text answer', 'is_correct' => false]
            ]
          ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals(count($data['result']) , 1);
          $this->assertEquals($data['result'][0] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       * @depends testAddQuiz
       */
       public function testGetQuizAfterUpdate($quiz_id)
       {
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('quiz.get', [
           'id' => $quiz_id['quiz_id']
         ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][1]) , 8);
        $this->assertEquals(count($data['result'][1]['quiz_question']) , 3);
        $this->assertEquals(count($data['result'][1]['quiz_question'][0]) , 7);
        $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer']) , 3);
        $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][0]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['text'] , "la une");
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['is_correct'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['order'] , null);
        $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][1]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['text'] , "la deux");
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['is_correct'] , 0);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['order'] , null);
        $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][2]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['id'] , 3);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['text'] , "nouveau text answer");
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['is_correct'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['order'] , null);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['point'] , 18);
        $this->assertEquals($data['result'][1]['quiz_question'][0]['text'] , "nouveau text");
        $this->assertEquals($data['result'][1]['quiz_question'][0]['type'] , "multiple");
        $this->assertEquals($data['result'][1]['quiz_question'][0]['order'] , null);
        $this->assertEquals(count($data['result'][1]['quiz_question'][1]) , 7);
        $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer']) , 3);
        $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][0]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['id'] , 4);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['quiz_question_id'] , 2);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['text'] , "nouveau text answer");
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['is_correct'] , 0);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['order'] , null);
        $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][1]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['id'] , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['quiz_question_id'] , 2);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['text'] , "la deux2");
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['is_correct'] , 0);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][1]['order'] , null);
        $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][2]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['id'] , 6);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['quiz_question_id'] , 2);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['text'] , "la trois2");
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['is_correct'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][2]['order'] , null);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['point'] , 18);
        $this->assertEquals($data['result'][1]['quiz_question'][1]['text'] , "nouveau text");
        $this->assertEquals($data['result'][1]['quiz_question'][1]['type'] , "simple");
        $this->assertEquals($data['result'][1]['quiz_question'][1]['order'] , null);
        $this->assertEquals(count($data['result'][1]['quiz_question'][2]) , 7);
        $this->assertEquals(count($data['result'][1]['quiz_question'][2]['quiz_answer']) , 1);
        $this->assertEquals(count($data['result'][1]['quiz_question'][2]['quiz_answer'][0]) , 5);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['id'] , 7);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['quiz_question_id'] , 3);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['text'] , "la une3");
        $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['is_correct'] , 0);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_answer'][0]['order'] , null);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['id'] , 3);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['point'] , null);
        $this->assertEquals($data['result'][1]['quiz_question'][2]['text'] , "une question 3");
        $this->assertEquals($data['result'][1]['quiz_question'][2]['type'] , "text");
        $this->assertEquals($data['result'][1]['quiz_question'][2]['order'] , null);
        $this->assertEquals($data['result'][1]['id'] , 1);
        $this->assertEquals($data['result'][1]['name'] , "super quiz");
        $this->assertEquals($data['result'][1]['attempt_count'] , null);
        $this->assertEquals($data['result'][1]['time_limit'] , null);
        $this->assertEquals($data['result'][1]['created_date'] , null);
        $this->assertEquals($data['result'][1]['item_id'] , 9);
        $this->assertEquals($data['result'][1]['user_id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
       }

      /**
      * @depends testAddQuiz
      */
      public function testAddUserAnswer()
      {
        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.addUserAnswer', [
          'quiz_question_id' => 1,
          'quiz_answer_id' => 1
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.addUserAnswer', [
          'quiz_question_id' => 1,
          'quiz_answer_id' => 3
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.addUserAnswer', [
          'quiz_question_id' => 2,
          'quiz_answer_id' => 6
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 3);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.addUserAnswer', [
          'quiz_question_id' => 3,
          'text' => 'la une3'
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 4);
        $this->assertEquals($data['jsonrpc'] , 2.0);
      }

      /**
      * @depends testAddQuiz
      */
      public function testGetUserAnswer()
      {
        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.getUserAnswer', [
          'id' => 1
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][1]) , 4);
        $this->assertEquals(count($data['result'][1][0]) , 6);
        $this->assertEquals($data['result'][1][0]['id'] , 1);
        $this->assertEquals($data['result'][1][0]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][0]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1][0]['quiz_answer_id'] , 1);
        $this->assertEquals($data['result'][1][0]['user_id'] , 3);
        $this->assertEquals($data['result'][1][0]['text'] , null);
        $this->assertEquals(count($data['result'][1][1]) , 6);
        $this->assertEquals($data['result'][1][1]['id'] , 2);
        $this->assertEquals($data['result'][1][1]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][1]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1][1]['quiz_answer_id'] , 3);
        $this->assertEquals($data['result'][1][1]['user_id'] , 3);
        $this->assertEquals($data['result'][1][1]['text'] , null);
        $this->assertEquals(count($data['result'][1][2]) , 6);
        $this->assertEquals($data['result'][1][2]['id'] , 3);
        $this->assertEquals($data['result'][1][2]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][2]['quiz_question_id'] , 2);
        $this->assertEquals($data['result'][1][2]['quiz_answer_id'] , 6);
        $this->assertEquals($data['result'][1][2]['user_id'] , 3);
        $this->assertEquals($data['result'][1][2]['text'] , null);
        $this->assertEquals(count($data['result'][1][3]) , 6);
        $this->assertEquals($data['result'][1][3]['id'] , 4);
        $this->assertEquals($data['result'][1][3]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][3]['quiz_question_id'] , 3);
        $this->assertEquals($data['result'][1][3]['quiz_answer_id'] , null);
        $this->assertEquals($data['result'][1][3]['user_id'] , 3);
        $this->assertEquals($data['result'][1][3]['text'] , "la une3");
        $this->assertEquals($data['jsonrpc'] , 2.0);
      }

      /**
      * @depends testAddQuiz
      */
      public function testUpdateUserAnswer()
      {
        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.updateUserAnswer', [
          'quiz_answer_id' => 2,
          'id' => 1,
          'text' => 'super upd'
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
      }

      /**
      * @depends testAddQuiz
      */
      public function testGetUserAnswerAfterUpt()
      {
        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.getUserAnswer', [
          'id' => 1
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][1]) , 4);
        $this->assertEquals(count($data['result'][1][0]) , 6);
        $this->assertEquals($data['result'][1][0]['id'] , 1);
        $this->assertEquals($data['result'][1][0]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][0]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1][0]['quiz_answer_id'] , 2);
        $this->assertEquals($data['result'][1][0]['user_id'] , 3);
        $this->assertEquals($data['result'][1][0]['text'] , "super upd");
        $this->assertEquals(count($data['result'][1][1]) , 6);
        $this->assertEquals($data['result'][1][1]['id'] , 2);
        $this->assertEquals($data['result'][1][1]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][1]['quiz_question_id'] , 1);
        $this->assertEquals($data['result'][1][1]['quiz_answer_id'] , 3);
        $this->assertEquals($data['result'][1][1]['user_id'] , 3);
        $this->assertEquals($data['result'][1][1]['text'] , null);
        $this->assertEquals(count($data['result'][1][2]) , 6);
        $this->assertEquals($data['result'][1][2]['id'] , 3);
        $this->assertEquals($data['result'][1][2]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][2]['quiz_question_id'] , 2);
        $this->assertEquals($data['result'][1][2]['quiz_answer_id'] , 6);
        $this->assertEquals($data['result'][1][2]['user_id'] , 3);
        $this->assertEquals($data['result'][1][2]['text'] , null);
        $this->assertEquals(count($data['result'][1][3]) , 6);
        $this->assertEquals($data['result'][1][3]['id'] , 4);
        $this->assertEquals($data['result'][1][3]['quiz_id'] , 1);
        $this->assertEquals($data['result'][1][3]['quiz_question_id'] , 3);
        $this->assertEquals($data['result'][1][3]['quiz_answer_id'] , null);
        $this->assertEquals($data['result'][1][3]['user_id'] , 3);
        $this->assertEquals($data['result'][1][3]['text'] , "la une3");
        $this->assertEquals($data['jsonrpc'] , 2.0);

      }

      /**
      * @depends testAddQuiz
      */
      public function testRemoveUserAnswer()
      {
        $this->reset();
        $this->setIdentity(3);
        $data = $this->jsonRpc('quiz.removeUserAnswer', [
          'id' => 1,
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
      }

       /**
       * @depends testAddQuiz
       */
       public function testRemoveAnswers($quiz_id)
       {
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('quiz.removeAnswers', [
           'quiz_answer_id' => 1
         ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals($data['result'] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       * @depends testAddQuiz
       */
       public function testRemoveQuestions($quiz_id)
       {
         $this->reset();
         $this->setIdentity(1);
         $data = $this->jsonRpc('quiz.removeQuestions', [
           'quiz_question_id' => $quiz_id['quiz_id']
         ]);

         $this->assertEquals(count($data) , 3);
         $this->assertEquals($data['id'] , 1);
         $this->assertEquals($data['result'] , 1);
         $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       * @depends testAddQuiz
       */
       public function testGetQuizAfterRemove($quiz_id)
       {
          $this->reset();
          $this->setIdentity(1);
          $data = $this->jsonRpc('quiz.get', [
            'id' => $quiz_id['quiz_id']
          ]);

          $this->assertEquals(count($data) , 3);
          $this->assertEquals($data['id'] , 1);
          $this->assertEquals(count($data['result']) , 1);
          $this->assertEquals(count($data['result'][1]) , 8);
          $this->assertEquals(count($data['result'][1]['quiz_question']) , 2);
          $this->assertEquals(count($data['result'][1]['quiz_question'][0]) , 7);
          $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer']) , 3);
          $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][0]) , 5);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['id'] , 4);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['quiz_question_id'] , 2);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['text'] , "nouveau text answer");
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['is_correct'] , 0);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][0]['order'] , null);
          $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][1]) , 5);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['id'] , 5);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['quiz_question_id'] , 2);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['text'] , "la deux2");
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['is_correct'] , 0);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][1]['order'] , null);
          $this->assertEquals(count($data['result'][1]['quiz_question'][0]['quiz_answer'][2]) , 5);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['id'] , 6);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['quiz_question_id'] , 2);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['text'] , "la trois2");
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['is_correct'] , 1);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_answer'][2]['order'] , null);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['id'] , 2);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['quiz_id'] , 1);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['point'] , 18);
          $this->assertEquals($data['result'][1]['quiz_question'][0]['text'] , "nouveau text");
          $this->assertEquals($data['result'][1]['quiz_question'][0]['type'] , "simple");
          $this->assertEquals($data['result'][1]['quiz_question'][0]['order'] , null);
          $this->assertEquals(count($data['result'][1]['quiz_question'][1]) , 7);
          $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer']) , 1);
          $this->assertEquals(count($data['result'][1]['quiz_question'][1]['quiz_answer'][0]) , 5);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['id'] , 7);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['quiz_question_id'] , 3);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['text'] , "la une3");
          $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['is_correct'] , 0);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_answer'][0]['order'] , null);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['id'] , 3);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['quiz_id'] , 1);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['point'] , null);
          $this->assertEquals($data['result'][1]['quiz_question'][1]['text'] , "une question 3");
          $this->assertEquals($data['result'][1]['quiz_question'][1]['type'] , "text");
          $this->assertEquals($data['result'][1]['quiz_question'][1]['order'] , null);
          $this->assertEquals($data['result'][1]['id'] , 1);
          $this->assertEquals($data['result'][1]['name'] , "super quiz");
          $this->assertEquals($data['result'][1]['attempt_count'] , null);
          $this->assertEquals($data['result'][1]['time_limit'] , null);
          $this->assertEquals($data['result'][1]['created_date'] , null);
          $this->assertEquals($data['result'][1]['item_id'] , 9);
          $this->assertEquals($data['result'][1]['user_id'] , 1);
          $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       *
       * @depends testAddQuiz
       **/
       public function testItemGetForCheckQuiz($item_quiz)
       {
           $this->setIdentity(1);
           $data = $this->jsonRpc('item.get', [
             'id' => [$item_quiz['item_quiz']]
           ]);

           $this->assertEquals(count($data) , 3);
           $this->assertEquals($data['id'] , 1);
           $this->assertEquals(count($data['result']) , 1);
           $this->assertEquals(count($data['result'][9]) , 21);
           $this->assertEquals($data['result'][9]['post_id'] , null);
           $this->assertEquals($data['result'][9]['quiz_id'] , 1);
           $this->assertEquals($data['result'][9]['id'] , 9);
           $this->assertEquals($data['result'][9]['title'] , "Assignment");
           $this->assertEquals($data['result'][9]['description'] , "un super quiz");
           $this->assertEquals($data['result'][9]['type'] , "QUIZ");
           $this->assertEquals($data['result'][9]['is_available'] , 1);
           $this->assertEquals($data['result'][9]['is_published'] , 0);
           $this->assertEquals($data['result'][9]['order'] , 8);
           $this->assertEquals($data['result'][9]['start_date'] , null);
           $this->assertEquals($data['result'][9]['end_date'] , null);
           $this->assertEquals($data['result'][9]['is_grade_published'] , null);
           $this->assertEquals($data['result'][9]['updated_date'] , null);
           $this->assertEquals(!empty($data['result'][9]['created_date']) , true);
           $this->assertEquals($data['result'][9]['parent_id'] , 1);
           $this->assertEquals($data['result'][9]['page_id'] , 1);
           $this->assertEquals($data['result'][9]['user_id'] , 1);
           $this->assertEquals($data['result'][9]['points'] , 7);
           $this->assertEquals($data['result'][9]['text'] , null);
           $this->assertEquals($data['result'][9]['library_id'] , null);
           $this->assertEquals($data['result'][9]['participants'] , "all");
           $this->assertEquals($data['jsonrpc'] , 2.0);
       }

       /**
       *
       * @depends testAddQuiz
       **/
       public function testSubmissionGetPostId($item_quiz)
       {
         $this->setIdentity(5);
         $data = $this->jsonRpc('submission.getPostId', [
           'item_id' => $item_quiz['item_quiz']
         ]);

         $this->assertEquals(count($data) , 3);
         $this->assertEquals($data['id'] , 1);
         $this->assertEquals($data['result'] , 6);
         $this->assertEquals($data['jsonrpc'] , 2.0);
      }
}
