<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

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
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 2,'course' => $course_id));
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 3,'course' => $course_id));
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
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 8);
        $this->assertEquals(count($data['result']['submission_user']) , 1);
        $this->assertEquals(count($data['result']['submission_user'][0]) , 5);
        $this->assertEquals(count($data['result']['submission_user'][0]['user']) , 12);
        $this->assertEquals($data['result']['submission_user'][0]['user']['gender'] , null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['contact_state'] , 0);
        $this->assertEquals($data['result']['submission_user'][0]['user']['id'] , 5);
        $this->assertEquals($data['result']['submission_user'][0]['user']['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['submission_user'][0]['user']['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['submission_user'][0]['user']['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['submission_user'][0]['user']['birth_date'] , null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['position'] , null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['school_id'] , 1);
        $this->assertEquals($data['result']['submission_user'][0]['user']['interest'] , null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['submission_user'][0]['submission_id'] , 1);
        $this->assertEquals($data['result']['submission_user'][0]['user_id'] , 5);
        $this->assertEquals($data['result']['submission_user'][0]['grade'] , null);
        $this->assertEquals($data['result']['submission_user'][0]['submit_date'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['item_id'] , 1);
        $this->assertEquals($data['result']['submit_date'] , null);
        $this->assertEquals(count($data['result']['text_editor']) , 0);
        $this->assertEquals(count($data['result']['document']) , 0);
        $this->assertEquals(count($data['result']['chat']) , 1);
        $this->assertEquals(count($data['result']['chat'][0]) , 6);
        $this->assertEquals(count($data['result']['chat'][0]['users']) , 1);
        $this->assertEquals(count($data['result']['chat'][0]['users'][5]) , 4);
        $this->assertEquals($data['result']['chat'][0]['users'][5]['id'] , 5);
        $this->assertEquals($data['result']['chat'][0]['users'][5]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['chat'][0]['users'][5]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['chat'][0]['users'][5]['avatar'] , null);
        $this->assertEquals(count($data['result']['chat'][0]['messages']) , 2);
        $this->assertEquals(count($data['result']['chat'][0]['messages']['list']) , 0);
        $this->assertEquals($data['result']['chat'][0]['messages']['count'] , 0);
        $this->assertEquals($data['result']['chat'][0]['id'] , 1);
        $this->assertEquals($data['result']['chat'][0]['name'] , "Chat");
        $this->assertEquals($data['result']['chat'][0]['type'] , 5);
        $this->assertEquals(!empty($data['result']['chat'][0]['created_date']) , true);
        $this->assertEquals(count($data['result']['videoconf']) , 16);
        $this->assertEquals(count($data['result']['videoconf']['instructors']) , 0);
        $this->assertEquals(count($data['result']['videoconf']['videoconf_admin']) , 5);
        $this->assertEquals($data['result']['videoconf']['videoconf_admin']['id'] , 1);
        $this->assertEquals($data['result']['videoconf']['videoconf_admin']['videoconf_id'] , 1);
        $this->assertEquals($data['result']['videoconf']['videoconf_admin']['user_id'] , 5);
        $this->assertEquals(!empty($data['result']['videoconf']['videoconf_admin']['token']) , true);
        $this->assertEquals(!empty($data['result']['videoconf']['videoconf_admin']['created_date']) , true);
        $this->assertEquals($data['result']['videoconf']['id'] , 1);
        $this->assertEquals(!empty($data['result']['videoconf']['token']) , true);
        $this->assertEquals($data['result']['videoconf']['start_date'] , null);
        $this->assertEquals($data['result']['videoconf']['duration'] , null);
        $this->assertEquals($data['result']['videoconf']['archive_token'] , null);
        $this->assertEquals($data['result']['videoconf']['archive_link'] , null);
        $this->assertEquals($data['result']['videoconf']['archive_status'] , null);
        $this->assertEquals($data['result']['videoconf']['conversation_id'] , 1);
        $this->assertEquals($data['result']['videoconf']['submission_id'] , 1);
        $this->assertEquals($data['result']['videoconf']['title'] , null);
        $this->assertEquals($data['result']['videoconf']['description'] , null);
        $this->assertEquals(!empty($data['result']['videoconf']['created_date']) , true);
        $this->assertEquals($data['result']['videoconf']['deleted_date'] , null);
        $this->assertEquals(count($data['result']['videoconf']['videoconf_opt']) , 4);
        $this->assertEquals($data['result']['videoconf']['videoconf_opt']['item_id'] , 1);
        $this->assertEquals($data['result']['videoconf']['videoconf_opt']['record'] , 1);
        $this->assertEquals($data['result']['videoconf']['videoconf_opt']['nb_user_autorecord'] , 2);
        $this->assertEquals($data['result']['videoconf']['videoconf_opt']['allow_intructor'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddItem
     */
    public function testVideoconfCreateSubmission()
    {
        $this->setIdentity(5);
    
        $data = $this->jsonRpc('conversation.createSubmission',
            [
                'users' => [1,2,3],
                'text' => 'TOTOTO',
                'submission_id' => 1 
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
