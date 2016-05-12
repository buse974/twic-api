<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;
use Application\Model\Role as ModelRole;

class LiveClassTest extends AbstractService
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
        
        
        // UPDATE COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.update', array('id' => 1, 'roles' => [ModelRole::ROLE_STUDENT_STR]));
        $this->reset();
        
        // UPDATE COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.update', array('id' => 2, 'roles' => [ModelRole::ROLE_STUDENT_STR]));
        $this->reset();
        
        // UPDATE COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.update', array('id' => 3, 'roles' => [ModelRole::ROLE_STUDENT_STR]));
        $this->reset();
        
        // ADD COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 1,'course' => $course_id));
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 2,'course' => $course_id));
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 3,'course' => $course_id));
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 5,'course' => $course_id));
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
                'grading_policy_id' => 6,
                'title' => 'title',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'LC',
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
                'parent' => null,
                'order' => null, 
            ]);

        return $data['result'];
    }
    
    /**
     * @depends testAddItem
     */
    public function testVideoconfGetByItem($item_id)
    {
        $this->setIdentity(5);
    
        $data = $this->jsonRpc('videoconf.getByItem',
            [
                'item_id' => $item_id
            ]);
        
        print_r($data);
        
    }
    
    /**
     * @depends testAddItem
     */
    public function testVideoconfGetByItem2($item_id)
    {
        $this->setIdentity(3);
    
        $data = $this->jsonRpc('videoconf.getByItem',
            ['item_id' => $item_id]);
        
        print_r($data);
        
        exit();
    }
    /**
     * @depends testAddItem
     */
    public function testVideoconfCreateSubmission()
    {
        $this->setIdentity(6);
    
        $data = $this->jsonRpc('conversation.createSubmission',
            [
                'users' => [1,2],
                'text' => 'TOTOTO',
                'submission_id' => 1 
            ]);
    
        print_r($data);
    }
    
    /**
     * @depends testAddItem
     */
    public function testVideoconfSendMessage()
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('message.sendSubmission',
            [
                'text' => 'text 1',
                'conversation' => 2
            ]);
    
        print_r($data);
    }
    
    /**
     * @depends testAddItem
     */
    public function testVideoconfSendMessage3()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('message.sendSubmission',
            [
                'text' => 'text 2',
                'conversation' => 2
            ]);
    
        print_r($data);
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