<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class GroupWorkTest extends AbstractService
{

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCreateInit()
    {
        $school_id = 1;
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
        
        // ADD SET
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.add', 
            ['course' => $course_id,
                'name' => 'nameset',
                'uid' => 'suid',
                'groups'=>[
                    ['name' =>'namegroup','uid'=>'guid','users'=>[1,3,4]],
                    ['name' =>'namegroup2','uid'=>'guid','users'=>[2,5]],
                    ['name' =>'namegroup3','uid'=>'guid','users'=>[6,7]],
                ]]);
        $set_id = $data['result']['id'];
        $this->reset();
        
        return [
            'school_id' => $school_id,
            'set_id' => $set_id,
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
                'grading_policy_id' => 6,
                'title' => 'title',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'WG',
                'set_id' => $data['set_id'],
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
                'data' => [
                    'videoconf' => [
                        'record' => 2,
                    ]
                ],  
                'parent' => null,
                'order' => null, 
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
    public function testCanUpdateVideoconfoptUpdate($item_id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('videoconfopt.update', [
            'record' => 1, 
            'item_id' => $item_id, 
            'nb_user_autorecord' => 10,
            'allow_intructor' => 0,
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddItem
     */
    public function testGetItem($item_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.get', array('id' => $item_id));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 19);
        $this->assertEquals(count($data['result']['videoconf']) , 4);
        $this->assertEquals($data['result']['videoconf']['item_id'] , 1);
        $this->assertEquals($data['result']['videoconf']['record'] , 1);
        $this->assertEquals($data['result']['videoconf']['nb_user_autorecord'] , 10);
        $this->assertEquals($data['result']['videoconf']['allow_intructor'] , 0);
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
        $this->assertEquals($data['result']['grading_policy_id'] , 6);
        $this->assertEquals($data['result']['title'] , "title");
        $this->assertEquals($data['result']['describe'] , "description");
        $this->assertEquals($data['result']['duration'] , 234);
        $this->assertEquals($data['result']['has_submission'] , true);
        $this->assertEquals($data['result']['type'] , "WG");
        $this->assertEquals($data['result']['set_id'] , 1);
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['order_id'] , null);
        $this->assertEquals($data['result']['start'] , null);
        $this->assertEquals($data['result']['end'] , null);
        $this->assertEquals($data['result']['cut_off'] , null);
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
    
        return $data['result']['id'];
    }
    
    /**
     * @depends testAddItem
     */
    public function testCanGetSubmission2($item_id)
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('submission.get', [
            'item_id' => $item_id
        ]);
    
        return $data['result']['id'];
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
        $this->assertEquals(count($data['result']['text_editor']) , 0);
        $this->assertEquals(count($data['result']['document']) , 0);
        $this->assertEquals(count($data['result']['chat']) , 1);
        $this->assertEquals(count($data['result']['chat'][0]) , 5);
        $this->assertEquals($data['result']['chat'][0]['id'] , 1);
        $this->assertEquals($data['result']['chat'][0]['name'] , "Chat");
        $this->assertEquals($data['result']['chat'][0]['type'] , 5);
        $this->assertEquals($data['result']['chat'][0]['submission_id'] , 1);
        $this->assertEquals(!empty($data['result']['chat'][0]['created_date']) , true);
        $this->assertEquals(count($data['result']['videoconf']) , 13);
        $this->assertEquals($data['result']['videoconf']['id'] , 1);
        $this->assertEquals(!empty($data['result']['videoconf']['token']) , true);
        $this->assertEquals(!empty($data['result']['videoconf']['start_date']) , true);
        $this->assertEquals($data['result']['videoconf']['duration'] , null);
        $this->assertEquals($data['result']['videoconf']['archive_token'] , null);
        $this->assertEquals($data['result']['videoconf']['archive_link'] , null);
        $this->assertEquals($data['result']['videoconf']['archive_status'] , null);
        $this->assertEquals($data['result']['videoconf']['conversation_id'] , null);
        $this->assertEquals($data['result']['videoconf']['submission_id'] , 1);
        $this->assertEquals($data['result']['videoconf']['title'] , null);
        $this->assertEquals($data['result']['videoconf']['description'] , null);
        $this->assertEquals(!empty($data['result']['videoconf']['created_date']) , true);
        $this->assertEquals($data['result']['videoconf']['deleted_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGetItemGetListSubmissions()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.getListSubmissions', ['filter' => ['n' => 10, 'p' => 1], 'course' => [1,2], 'program' => [1,2], 'type' => [1,2]]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 11);
        $this->assertEquals($data['result']['list'][0]['due'] , 3);
        $this->assertEquals($data['result']['list'][0]['graded'] , 0);
        $this->assertEquals($data['result']['list'][0]['submitted'] , 0);
        $this->assertEquals(count($data['result']['list'][0]['program']) , 1);
        $this->assertEquals($data['result']['list'][0]['program']['name'] , "program name");
        $this->assertEquals(count($data['result']['list'][0]['course']) , 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'] , "IMERIR");
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "title");
        $this->assertEquals($data['result']['list'][0]['type'] , "WG");
        $this->assertEquals($data['result']['list'][0]['start'] , null);
        $this->assertEquals($data['result']['list'][0]['end'] , null);
        $this->assertEquals($data['result']['list'][0]['cut_off'] , null);
        $this->assertEquals($data['result']['count'] , 1);
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
