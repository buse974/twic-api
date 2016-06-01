<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class AssignmentTest extends AbstractService
{

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCreateInit()
    {
        // ADD SCHOOL
        $this->setIdentity(1);
        $data = $this->jsonRpc('school.add', array('name' => 'université de monaco','next_name' => 'buisness school','short_name' => 'IUM','logo' => 'token','describe' => 'une description','website' => 'www.ium.com','programme' => 'super programme','background' => 'background','phone' => '+33480547852','contact' => 'contact@ium.com','contact_id' => 1,'address' => array("street_no" => 12,"street_type" => "rue","street_name" => "du stade","city" => array("name" => "Monaco"),"country" => array("name" => "Monaco"))));
        $school_id = $data['result']['id'];
        $this->reset();

        // ADD SCHOOL USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.update', array('school_id' => $school_id));
        $this->reset();
        
        // ADD PROGRAM
        $this->setIdentity(1);
        $data = $this->jsonRpc('program.add', array('name' => 'program name','school_id' => $school_id,'level' => 'emba','sis' => 'sis'));
        $program_id = $data['result'];
        $this->reset();
   
        // ADD COURSE
        $this->setIdentity(1);
        $data = $this->jsonRpc('course.add', array('title' => 'IMERIR','abstract' => 'un_token','description' => 'description','objectives' => 'objectives','teaching' => 'teaching','attendance' => 'attendance','duration' => 18,'notes' => 'notes','learning_outcomes' => 'learning_outcomes','video_link' => 'http://google.fr','video_token' => 'video_token','material_document' => array(array('type' => 'link','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token','date' => '2011-01-01')),'program_id' => $program_id));
        $course_id = $data['result']['id'];
        $this->reset();

        // ADD COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 1,'course' => $course_id));
        $this->reset();
        
       
        return [
            'school_id' => $school_id,
            'course_id' => $course_id
        ];
    }
   
    /**
     * @depends testCreateInit
     */
    public function testAddItem($data)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', 
            [
                'course' => (int)$data['course_id'],
               // 'grading_policy_id' => 6,
                'title' => 'title',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'IA',
                'ct' => [
                    'date'  => [
                        ['date' => '2016-01-01', 'after' => true],
                    ],
                ],
                'opt' => [
                    'grading' => [
                        'mode' => 'average', 
                        'has_pg' => true, 
                        'pg_nb' => 2, 
                        'pg_auto' => true, 
                        'pg_due_date' => 
                        '2016-10-10', 
                        'pg_can_view' => true, 
                        'user_can_view' => true, 
                        'pg_stars' => true
                    ], 
                ],
                'data' => null,
                'parent' => null,
                'order' => null, 
                'submission' => [
                    [ 'submission_user' => [1,3,4]]
                ],
                'has_all_student' => false,
                'is_grouped' => true,
            ]);
       
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddItem
     */
    public function testGetItem($item_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.get', array('id' => $item_id));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 20);
        $this->assertEquals(count($data['result']['program']) , 2);
        $this->assertEquals($data['result']['program']['id'] , 1);
        $this->assertEquals($data['result']['program']['name'] , "program name");
        $this->assertEquals(count($data['result']['course']) , 2);
        $this->assertEquals($data['result']['course']['id'] , 1);
        $this->assertEquals($data['result']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['ct_date']) , 1);
        $this->assertEquals(count($data['result']['ct_date'][0]) , 4);
        $this->assertEquals($data['result']['ct_date'][0]['id'] , 1);
        $this->assertEquals($data['result']['ct_date'][0]['item_id'] , 1);
        $this->assertEquals(!empty($data['result']['ct_date'][0]['date']) , true);
        $this->assertEquals($data['result']['ct_date'][0]['after'] , 1);
        $this->assertEquals(count($data['result']['opt_grading']) , 8);
        $this->assertEquals($data['result']['opt_grading']['mode'] , "average");
        $this->assertEquals($data['result']['opt_grading']['has_pg'] , 1);
        $this->assertEquals($data['result']['opt_grading']['pg_nb'] , 2);
        $this->assertEquals($data['result']['opt_grading']['pg_auto'] , 1);
        $this->assertEquals(!empty($data['result']['opt_grading']['pg_due_date']) , true);
        $this->assertEquals($data['result']['opt_grading']['pg_can_view'] , 1);
        $this->assertEquals($data['result']['opt_grading']['user_can_view'] , 1);
        $this->assertEquals($data['result']['opt_grading']['pg_stars'] , 1);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy_id'] , null);
        $this->assertEquals($data['result']['title'] , "title");
        $this->assertEquals($data['result']['describe'] , "description");
        $this->assertEquals($data['result']['duration'] , 234);
        $this->assertEquals($data['result']['type'] , "IA");
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['order_id'] , null);
        $this->assertEquals($data['result']['has_submission'] , 1);
        $this->assertEquals($data['result']['start'] , null);
        $this->assertEquals($data['result']['end'] , null);
        $this->assertEquals($data['result']['cut_off'] , null);
        $this->assertEquals($data['result']['is_grouped'] , 1);
        $this->assertEquals($data['result']['has_all_student'] , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddItem
     */
    public function testCanGetSubmission($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.get', [
            'item_id' => $item_id
        ]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result']['submission_user']) , 3); 
        $this->assertEquals(count($data['result']['submission_user'][0]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][0]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['firstname'] , "Paul"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['email'] , "pboussekey@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['school_id'] , 2); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['user_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['start_date'] , null); 
        $this->assertEquals(count($data['result']['submission_user'][1]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][1]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['id'] , 3); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['firstname'] , "Christophe"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['lastname'] , "Robert"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['email'] , "crobert@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['school_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['user_id'] , 3); 
        $this->assertEquals($data['result']['submission_user'][1]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['start_date'] , null); 
        $this->assertEquals(count($data['result']['submission_user'][2]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][2]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['id'] , 4); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['firstname'] , "Salim"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['email'] , "sbendacha@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['school_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['user_id'] , 4); 
        $this->assertEquals($data['result']['submission_user'][2]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['start_date'] , null); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['item_id'] , 1); 
        $this->assertEquals($data['result']['submit_date'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        
        return $data['result']['id'];
    }

    /**
     * @depends testCanGetSubmission
     */
    public function testCanAddDocument($submission_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.addDocument', ['name' => 'monfile','link' => 'http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf','submission_id' => $submission_id]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 11);
        $this->assertEquals($data['result']['id'] , 3);
        $this->assertEquals($data['result']['name'] , "monfile");
        $this->assertEquals($data['result']['link'] , "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf");
        $this->assertEquals($data['result']['token'] , null);
        $this->assertEquals($data['result']['type'] , null);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals($data['result']['updated_date'] , null);
        $this->assertEquals($data['result']['folder_id'] , 1);
        $this->assertEquals($data['result']['owner_id'] , 1);
        $this->assertEquals($data['result']['box_id'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanGetSubmission
     */
    public function testCanGetSubmissionContent($submission_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.getContent', ['submission_id' => $submission_id]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 4); 
        $this->assertEquals(count($data['result']['text_editor']) , 1); 
        $this->assertEquals(count($data['result']['text_editor'][0]) , 5); 
        $this->assertEquals($data['result']['text_editor'][0]['id'] , 1); 
        $this->assertEquals($data['result']['text_editor'][0]['name'] , "Text Editor"); 
        $this->assertEquals($data['result']['text_editor'][0]['text'] , ""); 
        $this->assertEquals($data['result']['text_editor'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result']['text_editor'][0]['submit_date'] , null); 
        $this->assertEquals(count($data['result']['document']) , 1); 
        $this->assertEquals(count($data['result']['document'][0]) , 11); 
        $this->assertEquals($data['result']['document'][0]['id'] , 3); 
        $this->assertEquals($data['result']['document'][0]['name'] , "monfile"); 
        $this->assertEquals($data['result']['document'][0]['link'] , "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf"); 
        $this->assertEquals($data['result']['document'][0]['token'] , null); 
        $this->assertEquals($data['result']['document'][0]['type'] , null); 
        $this->assertEquals(!empty($data['result']['document'][0]['created_date']) , true); 
        $this->assertEquals($data['result']['document'][0]['deleted_date'] , null); 
        $this->assertEquals($data['result']['document'][0]['updated_date'] , null); 
        $this->assertEquals($data['result']['document'][0]['folder_id'] , 1); 
        $this->assertEquals($data['result']['document'][0]['owner_id'] , 1); 
        $this->assertEquals($data['result']['document'][0]['box_id'] , null); 
        $this->assertEquals(count($data['result']['chat']) , 1); 
        $this->assertEquals(count($data['result']['chat'][0]) , 6); 
        $this->assertEquals(count($data['result']['chat'][0]['users']) , 3); 
        $this->assertEquals(count($data['result']['chat'][0]['users'][1]) , 4); 
        $this->assertEquals($data['result']['chat'][0]['users'][1]['id'] , 1); 
        $this->assertEquals($data['result']['chat'][0]['users'][1]['firstname'] , "Paul"); 
        $this->assertEquals($data['result']['chat'][0]['users'][1]['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result']['chat'][0]['users'][1]['avatar'] , null); 
        $this->assertEquals(count($data['result']['chat'][0]['users'][3]) , 4); 
        $this->assertEquals($data['result']['chat'][0]['users'][3]['id'] , 3); 
        $this->assertEquals($data['result']['chat'][0]['users'][3]['firstname'] , "Christophe"); 
        $this->assertEquals($data['result']['chat'][0]['users'][3]['lastname'] , "Robert"); 
        $this->assertEquals($data['result']['chat'][0]['users'][3]['avatar'] , null); 
        $this->assertEquals(count($data['result']['chat'][0]['users'][4]) , 4); 
        $this->assertEquals($data['result']['chat'][0]['users'][4]['id'] , 4); 
        $this->assertEquals($data['result']['chat'][0]['users'][4]['firstname'] , "Salim"); 
        $this->assertEquals($data['result']['chat'][0]['users'][4]['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result']['chat'][0]['users'][4]['avatar'] , null); 
        $this->assertEquals(count($data['result']['chat'][0]['messages']) , 2); 
        $this->assertEquals(count($data['result']['chat'][0]['messages']['list']) , 0); 
        $this->assertEquals($data['result']['chat'][0]['messages']['count'] , 0); 
        $this->assertEquals($data['result']['chat'][0]['id'] , 1); 
        $this->assertEquals($data['result']['chat'][0]['name'] , "Chat"); 
        $this->assertEquals($data['result']['chat'][0]['type'] , 5); 
        $this->assertEquals(!empty($data['result']['chat'][0]['created_date']) , true); 
        $this->assertEquals($data['result']['videoconf'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
    }
    
    /**
     * @depends testCanGetSubmission
     */
    public function testCanAddTextEditor($submission_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('texteditor.add', ['name' => 'monfile','text' => 'text','submission_id' => $submission_id]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
    /**
     * @depends testAddItem
     */
    public function testCanSubmit($item_id)
    {
        $this->setIdentity(1);
        $this->jsonRpc('submission.submit', [
            'item_id' => $item_id
        ]);
        $this->reset();


        $this->setIdentity(3);
        $data = $this->jsonRpc('submission.submit', [
            'item_id' => $item_id
        ]);
        $this->reset();
        
        /*$this->setIdentity(4);
        $data = $this->jsonRpc('submission.submit', [
            'item_id' => $item_id
        ]);*/
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddItem
     */
    public function testCanGetSubmissionAfterSubmit($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.get', [
            'item_id' => $item_id
        ]);
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 4); 
        $this->assertEquals(count($data['result']['submission_user']) , 3); 
        $this->assertEquals(count($data['result']['submission_user'][0]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][0]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['firstname'] , "Paul"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['email'] , "pboussekey@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['school_id'] , 2); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['user_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['grade'] , null); 
        $this->assertEquals(!empty($data['result']['submission_user'][0]['submit_date']) , true); 
        $this->assertEquals($data['result']['submission_user'][0]['start_date'] , null); 
        $this->assertEquals(count($data['result']['submission_user'][1]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][1]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['id'] , 3); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['firstname'] , "Christophe"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['lastname'] , "Robert"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['email'] , "crobert@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['school_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['user_id'] , 3); 
        $this->assertEquals($data['result']['submission_user'][1]['grade'] , null); 
        $this->assertEquals(!empty($data['result']['submission_user'][1]['submit_date']) , true); 
        $this->assertEquals($data['result']['submission_user'][1]['start_date'] , null); 
        $this->assertEquals(count($data['result']['submission_user'][2]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][2]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['id'] , 4); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['firstname'] , "Salim"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['email'] , "sbendacha@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['school_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['user_id'] , 4); 
        $this->assertEquals($data['result']['submission_user'][2]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['start_date'] , null); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['item_id'] , 1); 
        $this->assertEquals($data['result']['submit_date'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

    
        
    }
    
    /**
     * @depends testAddItem
     */
    public function testCanCancelSubmit($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.cancelsubmit', [
            'item_id' => $item_id
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddItem
     */
    public function testCanGetSubmissionAfterCancel($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.get', [
            'item_id' => $item_id
        ]);
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 4); 
        $this->assertEquals(count($data['result']['submission_user']) , 3); 
        $this->assertEquals(count($data['result']['submission_user'][0]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][0]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['firstname'] , "Paul"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['email'] , "pboussekey@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['school_id'] , 2); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['user_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][0]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][0]['start_date'] , null); 
        $this->assertEquals(count($data['result']['submission_user'][1]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][1]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['id'] , 3); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['firstname'] , "Christophe"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['lastname'] , "Robert"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['email'] , "crobert@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['school_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][1]['user_id'] , 3); 
        $this->assertEquals($data['result']['submission_user'][1]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][1]['start_date'] , null); 
        $this->assertEquals(count($data['result']['submission_user'][2]) , 6); 
        $this->assertEquals(count($data['result']['submission_user'][2]['user']) , 12); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['gender'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['id'] , 4); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['firstname'] , "Salim"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['email'] , "sbendacha@thestudnet.com"); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['birth_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['position'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['school_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['interest'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['avatar'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['submission_id'] , 1); 
        $this->assertEquals($data['result']['submission_user'][2]['user_id'] , 4); 
        $this->assertEquals($data['result']['submission_user'][2]['grade'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['submit_date'] , null); 
        $this->assertEquals($data['result']['submission_user'][2]['start_date'] , null); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['item_id'] , 1); 
        $this->assertEquals($data['result']['submit_date'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

    }
    
    /**
     * @depends testAddItem
     */
    public function testGetList($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.getList', [
            'item_id' => $item_id
        ]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 1); 
        $this->assertEquals(count($data['result'][0]) , 7); 
        $this->assertEquals(count($data['result'][0]['submission_user']) , 3); 
        $this->assertEquals(count($data['result'][0]['submission_user'][0]) , 6); 
        $this->assertEquals(count($data['result'][0]['submission_user'][0]['user']) , 12); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['gender'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['firstname'] , "Paul"); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['email'] , "pboussekey@thestudnet.com"); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['birth_date'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['position'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['school_id'] , 2); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['interest'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['avatar'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['user_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['grade'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['submit_date'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][0]['start_date'] , null); 
        $this->assertEquals(count($data['result'][0]['submission_user'][1]) , 6); 
        $this->assertEquals(count($data['result'][0]['submission_user'][1]['user']) , 12); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['gender'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['id'] , 3); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['firstname'] , "Christophe"); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['lastname'] , "Robert"); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['email'] , "crobert@thestudnet.com"); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['birth_date'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['position'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['school_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['interest'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['avatar'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['user_id'] , 3); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['grade'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['submit_date'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][1]['start_date'] , null); 
        $this->assertEquals(count($data['result'][0]['submission_user'][2]) , 6); 
        $this->assertEquals(count($data['result'][0]['submission_user'][2]['user']) , 12); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['gender'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['contact_state'] , 0); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['id'] , 4); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['firstname'] , "Salim"); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['email'] , "sbendacha@thestudnet.com"); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['birth_date'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['position'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['school_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['interest'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['avatar'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user']['has_email_notifier'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['user_id'] , 4); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['grade'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['submit_date'] , null); 
        $this->assertEquals($data['result'][0]['submission_user'][2]['start_date'] , null); 
        $this->assertEquals($data['result'][0]['id'] , 1); 
        $this->assertEquals($data['result'][0]['group_id'] , null); 
        $this->assertEquals($data['result'][0]['group_name'] , null); 
        $this->assertEquals($data['result'][0]['submit_date'] , null); 
        $this->assertEquals($data['result'][0]['is_graded'] , 0);
        $this->assertEquals($data['result'][0]['item_id'] , 1);
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
            ->will($this->returnValue(['id' => $id, 'token' => ''+$id+'token']));
        
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
