<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class GradingTest extends AbstractService
{

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCreateInit()
    {
        // ADD SCHOOL
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('school.add', array('name' => 'université de monaco','next_name' => 'buisness school','short_name' => 'IUM','logo' => 'token','describe' => 'une description','website' => 'www.ium.com','programme' => 'super programme','background' => 'background','phone' => '+33480547852','contact' => 'contact@ium.com','contact_id' => 1,'address' => array("street_no" => 12,"street_type" => "rue","street_name" => "du stade","city" => array("name" => "Monaco"),"country" => array("name" => "Monaco"))));
        $school_id = $data['result']['id'];
        $this->reset();

        // ADD SCHOOL USER
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('user.update', array('school_id' => $school_id));
        $this->reset();
        
        // ADD PROGRAM
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('program.add', array('name' => 'program name','school_id' => $school_id,'level' => 'emba','sis' => 'sis'));
        $program_id = $data['result'];
        $this->reset();
   
        // ADD COURSE
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('course.add', array('title' => 'IMERIR','abstract' => 'un_token','description' => 'description','objectives' => 'objectives','teaching' => 'teaching','attendance' => 'attendance','duration' => 18,'notes' => 'notes','learning_outcomes' => 'learning_outcomes','video_link' => 'http://google.fr','video_token' => 'video_token','material_document' => array(array('type' => 'link','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token','date' => '2011-01-01')),'program_id' => $program_id));
        $course_id = $data['result']['id'];
        $this->reset();

        // ADD SECOND STUDENT
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('userrole.deleteByUser', array('id' => 7));
        $data = $this->jsonRpc('userrole.add', array('user' => 7, 'role' => 4));
        $this->reset();

        // ADD THIRD STUDENT
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('userrole.deleteByUser', array('id' => 6));
        $data = $this->jsonRpc('userrole.add', array('user' => 6, 'role' => 4));
        $this->reset();
        
        // ADD COURSE USER
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('user.addCourse', array('user' => [4, 7, 6],'course' => $course_id));
        $this->reset();
        
        // ADD GRADING POLICY
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('gradingpolicy.add', array('course_id' => $course_id,'name' => 'tata','grade' => 50));
        $grading_policy_id = $data['result'];
        $this->reset();        
        
        // ADD ITEM
        $this->setIdentity(1, 5);
        $data = $this->jsonRpc('item.add', 
            [
                'type' => 'IA',
                'title' => 'item',
                'desc' => 'blablabla', 
                'course' => $course_id, 
                'grading_policy_id' => $grading_policy_id,  
                'submission' => [
                    [ 'submission_user' => [4]],
                    [ 'submission_user' => [7]],
                    [ 'submission_user' => [6]]
                ],
                'has_all_student' => false,
                'is_grouped' => true,
                'opt' => [ 
                        'grading' => [ 
                            'mode' => 'average', 
                            'has_pg' => 0, 
                            'pg_nb'=>1, 
                            'pg_auto' => 1, 
                            'pg_due_date' => null,
                            'pg_can_view' => 1,
                            'user_can_view' => 1,
                            'pg_stars' => 0
                        ]
                    ]                            
        ]);
        
        $item_id = $data['result'];
        $this->reset();
        
        // ADD SUBMISSION
        $this->setIdentity(4, 4);
        $data = $this->jsonRpc('submission.get', array('item_id' => $item_id));
        $submission_id = $data['result']['id'];
        $this->reset();
        
        
        // ADD SUBMISSION2
        $this->setIdentity(7, 4);
        $data = $this->jsonRpc('submission.get', array('item_id' => $item_id));
        $submission_id2 = $data['result']['id'];
        $this->reset();
        
        return [
            'school_id' => $school_id,
            'course_id' => $course_id,
            'grading_policy_id' => $grading_policy_id,
            'item_id' => $item_id,
            'submission_id' => $submission_id,
            'submission_id2' => $submission_id2
        ];
    }
   
   
     /**
     * @depends testCreateInit
     */
    public function testAddCriteria($params)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('criteria.add', array('grading_policy' => $params['grading_policy_id'],'name' => 'criteria','points' => 40, 'description' =>  'description!!'));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        
        return $data['result'];
    }
    
     /**
     * @depends testCreateInit
     */
    public function testAddCriteria2($params)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('criteria.add', array('grading_policy' => $params['grading_policy_id'],'name' => 'criteria2','points' => 50, 'description' =>  'description!!'));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 2); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        
        return $data['result'];
    }
    
    
      /**
     * @depends testCreateInit
     */
    public function testAssignUser($params)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.assignGraders', array('id' => $params['submission_id'],'users' => [7, 6]));
        
	    $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        return $data['result'];
    }
    
    
      /**
     * @depends testCreateInit
     */
    public function testAssignUser2($params)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.assignGraders', array('id' => $params['submission_id2'],'users' => [4]));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        return $data['result'];
    }
    
      /**
     * @depends testAddCriteria
     */
    public function testGetCriteria($criteria)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('criteria.get', array('id' => $criteria));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 5); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['name'] , "criteria"); 
        $this->assertEquals($data['result']['points'] , 40); 
        $this->assertEquals($data['result']['description'] , "description!!"); 
        $this->assertEquals($data['result']['grading_policy_id'] , 6); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 


        
        return $data['result'];
    }
    
      /**
     * @depends testCreateInit 
     * @depends testAddCriteria
     * @depends testAddCriteria2
     */
    public function testPairRates($params, $criteria, $criteria2)
    {
        $this->setIdentity(7);
        $data = $this->jsonRpc('submission.pairRates', ['id' => $params['submission_id'], 'criterias' => [ $criteria => [ 4 => 30 ], $criteria2 => [ 4 => 40]]]);
       
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        return $data['result'];
    }
    
    
      /**
     * @depends testCreateInit 
     * @depends testAddCriteria
     * @depends testAddCriteria2
     */
    public function testPairRates2($params, $criteria, $criteria2)
    {
        $this->setIdentity(6);
        $data = $this->jsonRpc('submission.pairRates', ['id' => $params['submission_id'], 'criterias' => [ $criteria => [ 4 => 40 ], $criteria2 => [ 4 => 40]]]);
      
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }
    
      /**
     * @depends testCreateInit 
     * @depends testAddCriteria
     * @depends testAddCriteria2
     */
    public function testInstructorRates($params, $criteria, $criteria2)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.instructorRates', [
            'id' => $params['submission_id'], 
            'criterias' => [ 
                $criteria => [ 4 => 10 ], 
                $criteria2 => [ 4 => 10]]]);
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }
    
       /**
     * @depends testCreateInit 
     */
   public function testInstructorRates2($params)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.instructorRates', ['id' => $params['submission_id2'], 'grades' => [ 7  => 10 ]]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , $params['submission_id2']); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }
    
      /**
     * @depends testCreateInit 
     * @depends testAddCriteria
     * @depends testAddCriteria2
     */
    public function testPairRates3($params, $criteria, $criteria2)
    {
        $this->setIdentity(6);
        $data = $this->jsonRpc('submission.pairRates', ['id' => $params['submission_id'], 'criterias' => [ $criteria => [ 4 => 50 ], $criteria2 => [ 4 => 50]]]);
      
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }
    
      /**
     * @depends testCreateInit 
     */
    public function testPairRates4($params)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('submission.pairRates', ['id' => $params['submission_id2'], 'grades' => [ 7  => 35 ]]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        
        return $data['result'];
    }
    
    /**
     * @depends testCreateInit 
     */
    public function testgetUserGrades($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.getUserGrades', ['id' => $params['submission_id']]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 1); 
        $this->assertEquals(count($data['result'][0]) , 7); 
        $this->assertEquals($data['result'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['user_id'] , 4); 
        $this->assertEquals($data['result'][0]['grade'] , 95); 
        $this->assertEquals($data['result'][0]['submit_date'] , null); 
        $this->assertEquals($data['result'][0]['overwritten'] , 0); 
        $this->assertEquals($data['result'][0]['start_date'] , null); 
        $this->assertEquals($data['result'][0]['end_date'] , null);
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }
    
      
    /**
     * @depends testCreateInit 
     */
    public function testgetPGCriterias($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.getPGCriterias', ['id' => $params['submission_id']]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result'][0]) , 5);
        $this->assertEquals($data['result'][0]['pg_id'] , 6);
        $this->assertEquals($data['result'][0]['user_id'] , 4);
        $this->assertEquals($data['result'][0]['criteria_id'] , 1);
        $this->assertEquals($data['result'][0]['submission_id'] , 1);
        $this->assertEquals($data['result'][0]['points'] , 50);
        $this->assertEquals(count($data['result'][1]) , 5);
        $this->assertEquals($data['result'][1]['pg_id'] , 6);
        $this->assertEquals($data['result'][1]['user_id'] , 4);
        $this->assertEquals($data['result'][1]['criteria_id'] , 2);
        $this->assertEquals($data['result'][1]['submission_id'] , 1);
        $this->assertEquals($data['result'][1]['points'] , 50);
        $this->assertEquals(count($data['result'][2]) , 5);
        $this->assertEquals($data['result'][2]['pg_id'] , 7);
        $this->assertEquals($data['result'][2]['user_id'] , 4);
        $this->assertEquals($data['result'][2]['criteria_id'] , 1);
        $this->assertEquals($data['result'][2]['submission_id'] , 1);
        $this->assertEquals($data['result'][2]['points'] , 30);
        $this->assertEquals(count($data['result'][3]) , 5);
        $this->assertEquals($data['result'][3]['pg_id'] , 7);
        $this->assertEquals($data['result'][3]['user_id'] , 4);
        $this->assertEquals($data['result'][3]['criteria_id'] , 2);
        $this->assertEquals($data['result'][3]['submission_id'] , 1);
        $this->assertEquals($data['result'][3]['points'] , 40);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['result'];
    }
    
    /**
     * @depends testCreateInit 
     */
    public function testgetPGGrades($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.getPGGrades', ['id' => $params['submission_id']]);
     
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 2); 
        $this->assertEquals(count($data['result'][0]) , 4); 
        $this->assertEquals($data['result'][0]['pg_id'] , 6); 
        $this->assertEquals($data['result'][0]['user_id'] , 4); 
        $this->assertEquals($data['result'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['grade'] , 111); 
        $this->assertEquals(count($data['result'][1]) , 4); 
        $this->assertEquals($data['result'][1]['pg_id'] , 7); 
        $this->assertEquals($data['result'][1]['user_id'] , 4); 
        $this->assertEquals($data['result'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result'][1]['grade'] , 78); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        return $data['result'];
    }
    
     /**
     * @depends testCreateInit 
     */
    public function testgetCriteriasByItem($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('item.getCriterias', ['id' => $params['item_id']]);
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 2); 
        $this->assertEquals(count($data['result'][0]) , 4); 
        $this->assertEquals($data['result'][0]['id'] , 1); 
        $this->assertEquals($data['result'][0]['name'] , "criteria"); 
        $this->assertEquals($data['result'][0]['points'] , 40); 
        $this->assertEquals($data['result'][0]['description'] , "description!!"); 
        $this->assertEquals(count($data['result'][1]) , 4); 
        $this->assertEquals($data['result'][1]['id'] , 2); 
        $this->assertEquals($data['result'][1]['name'] , "criteria2"); 
        $this->assertEquals($data['result'][1]['points'] , 50); 
        $this->assertEquals($data['result'][1]['description'] , "description!!"); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 



        return $data['result'];
    }
    
     /**
     * @depends testCreateInit 
     */
    public function testgetPairGraders($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.getPairGraders', ['id' => $params['submission_id']]);
       
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 2); 
        $this->assertEquals(count($data['result'][0]) , 5); 
        $this->assertEquals($data['result'][0]['id'] , 6); 
        $this->assertEquals($data['result'][0]['firstname'] , "Guillaume"); 
        $this->assertEquals($data['result'][0]['lastname'] , "Masmejean"); 
        $this->assertEquals($data['result'][0]['nickname'] , null); 
        $this->assertEquals($data['result'][0]['avatar'] , null); 
        $this->assertEquals(count($data['result'][1]) , 5); 
        $this->assertEquals($data['result'][1]['id'] , 7); 
        $this->assertEquals($data['result'][1]['firstname'] , "Arthur"); 
        $this->assertEquals($data['result'][1]['lastname'] , "Flachs"); 
        $this->assertEquals($data['result'][1]['nickname'] , null); 
        $this->assertEquals($data['result'][1]['avatar'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        return $data['result'];
    }
    
     /**
     * @depends testCreateInit 
     */
    public function testgetUserCriterias($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.getUserCriterias', ['id' => $params['submission_id']]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 2); 
        $this->assertEquals(count($data['result'][0]) , 6); 
        $this->assertEquals($data['result'][0]['id'] , 5); 
        $this->assertEquals($data['result'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['user_id'] , 4); 
        $this->assertEquals($data['result'][0]['criteria_id'] , 1); 
        $this->assertEquals($data['result'][0]['points'] , 40); 
        $this->assertEquals($data['result'][0]['overwritten'] , 0); 
        $this->assertEquals(count($data['result'][1]) , 6); 
        $this->assertEquals($data['result'][1]['id'] , 6); 
        $this->assertEquals($data['result'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result'][1]['user_id'] , 4); 
        $this->assertEquals($data['result'][1]['criteria_id'] , 2); 
        $this->assertEquals($data['result'][1]['points'] , 45); 
        $this->assertEquals($data['result'][1]['overwritten'] , 0); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        return $data['result'];
    }
    
     /**
     * @depends testCreateInit 
     */
    public function testaddSubmissionComment($params)
    {
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.addComment', 
            [
                'id' => $params['submission_id'],
                'file_name' => 'FILE NAME',
                'file_token' => 'azerty',
                'text' => 'COMMENT 1memzr!',
                
            ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['submission_id'] , 1);
        $this->assertEquals(count($data['result']['comment']) , 10);
        $this->assertEquals(count($data['result']['comment']['user']) , 4);
        $this->assertEquals($data['result']['comment']['user']['id'] , 1);
        $this->assertEquals($data['result']['comment']['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['comment']['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['comment']['id'] , 1);
        $this->assertEquals($data['result']['comment']['text'] , "COMMENT 1memzr!");
        $this->assertEquals($data['result']['comment']['audio'] , null);
        $this->assertEquals($data['result']['comment']['user_id'] , 1);
        $this->assertEquals($data['result']['comment']['submission_id'] , 1);
        $this->assertEquals($data['result']['comment']['file_token'] , "azerty");
        $this->assertEquals($data['result']['comment']['file_name'] , "FILE NAME");
        $this->assertEquals($data['result']['comment']['created_date'] , null);
        $this->assertEquals($data['result']['comment']['read_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
      /**
     * @depends testCreateInit 
     */
    public function testgetListSubmissionComment($params)
    {
        
        $this->setIdentity(4); 
        $data = $this->jsonRpc('submission.addComment', 
            [
                'id' => $params['submission_id'],
                'file_name' => 'FILE NAME',
                'file_token' => 'azerty',
                'text' => 'COMMENT 2!!!',
                
            ]);
        $this->setIdentity(1); 
        $data = $this->jsonRpc('submission.getComments', 
            [
                'id' => $params['submission_id']                
            ]);
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 2); 
        $this->assertEquals(count($data['result'][0]) , 10); 
        $this->assertEquals(count($data['result'][0]['user']) , 4); 
        $this->assertEquals($data['result'][0]['user']['id'] , 1); 
        $this->assertEquals($data['result'][0]['user']['firstname'] , "Paul"); 
        $this->assertEquals($data['result'][0]['user']['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result'][0]['user']['avatar'] , null); 
        $this->assertEquals($data['result'][0]['id'] , 1); 
        $this->assertEquals($data['result'][0]['text'] , "COMMENT 1memzr!"); 
        $this->assertEquals($data['result'][0]['audio'] , null); 
        $this->assertEquals($data['result'][0]['user_id'] , 1); 
        $this->assertEquals($data['result'][0]['submission_id'] , 1); 
        $this->assertEquals($data['result'][0]['file_token'] , "azerty"); 
        $this->assertEquals($data['result'][0]['file_name'] , "FILE NAME"); 
        $this->assertEquals($data['result'][0]['created_date'] , null); 
        $this->assertEquals($data['result'][0]['read_date'] , null); 
        $this->assertEquals(count($data['result'][1]) , 10); 
        $this->assertEquals(count($data['result'][1]['user']) , 4); 
        $this->assertEquals($data['result'][1]['user']['id'] , 4); 
        $this->assertEquals($data['result'][1]['user']['firstname'] , "Salim"); 
        $this->assertEquals($data['result'][1]['user']['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result'][1]['user']['avatar'] , null); 
        $this->assertEquals($data['result'][1]['id'] , 2); 
        $this->assertEquals($data['result'][1]['text'] , "COMMENT 2!!!"); 
        $this->assertEquals($data['result'][1]['audio'] , null); 
        $this->assertEquals($data['result'][1]['user_id'] , 4); 
        $this->assertEquals($data['result'][1]['submission_id'] , 1); 
        $this->assertEquals($data['result'][1]['file_token'] , "azerty"); 
        $this->assertEquals($data['result'][1]['file_name'] , "FILE NAME"); 
        $this->assertEquals($data['result'][1]['created_date'] , null); 
        $this->assertEquals($data['result'][1]['read_date'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 


        return $data['result'];
    }
    
    
    /**
     * @depends testAddCriteria
    public function testDeleteCriteria($criteria)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('criteria.delete', array('id' => $criteria));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }  */
    
}
