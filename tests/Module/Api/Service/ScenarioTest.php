<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ScenarioTest extends AbstractService
{

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAddSchool()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('school.add', array('name' => 'université de monaco','next_name' => 'buisness school','short_name' => 'IUM','logo' => 'token','describe' => 'une description','website' => 'www.ium.com','programme' => 'super programme','background' => 'background','phone' => '+33480547852','contact' => 'contact@ium.com','contact_id' => 1,'address' => array("street_no" => 12,"street_type" => "rue","street_name" => "du stade","city" => array("name" => "Monaco"),"country" => array("name" => "Monaco"))));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 14);
        $this->assertEquals(count($data['result']['address']) , 14);
        $this->assertEquals(count($data['result']['address']['city']) , 2);
        $this->assertEquals($data['result']['address']['city']['id'] , 19064);
        $this->assertEquals($data['result']['address']['city']['name'] , "Monaco");
        $this->assertEquals($data['result']['address']['division'] , null);
        $this->assertEquals(count($data['result']['address']['country']) , 3);
        $this->assertEquals($data['result']['address']['country']['id'] , 145);
        $this->assertEquals($data['result']['address']['country']['short_name'] , "Monaco");
        $this->assertEquals($data['result']['address']['country']['name'] , "Principality of Monaco");
        $this->assertEquals($data['result']['address']['id'] , 1);
        $this->assertEquals($data['result']['address']['street_no'] , 12);
        $this->assertEquals($data['result']['address']['street_type'] , "rue");
        $this->assertEquals($data['result']['address']['street_name'] , "du stade");
        $this->assertEquals(!empty($data['result']['address']['longitude']) , true);
        $this->assertEquals(!empty($data['result']['address']['latitude']), true);
        $this->assertEquals($data['result']['address']['door'] , null);
        $this->assertEquals($data['result']['address']['building'] , null);
        $this->assertEquals($data['result']['address']['apartment'] , null);
        $this->assertEquals($data['result']['address']['floor'] , null);
        $this->assertEquals($data['result']['address']['timezone'] , "Europe/Monaco");
        $this->assertEquals(count($data['result']['contact_user']) , 9);
        $this->assertEquals($data['result']['contact_user']['id'] , 1);
        $this->assertEquals($data['result']['contact_user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['contact_user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['contact_user']['status'] , null);
        $this->assertEquals($data['result']['contact_user']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['contact_user']['birth_date'] , null);
        $this->assertEquals($data['result']['contact_user']['position'] , null);
        $this->assertEquals($data['result']['contact_user']['interest'] , null);
        $this->assertEquals($data['result']['contact_user']['avatar'] , null);
        $this->assertEquals($data['result']['id'] , 2);
        $this->assertEquals($data['result']['name'] , "université de monaco");
        $this->assertEquals($data['result']['next_name'] , "buisness school");
        $this->assertEquals($data['result']['short_name'] , "IUM");
        $this->assertEquals($data['result']['logo'] , "token");
        $this->assertEquals($data['result']['describe'] , "une description");
        $this->assertEquals($data['result']['website'] , "www.ium.com");
        $this->assertEquals($data['result']['programme'] , "super programme");
        $this->assertEquals($data['result']['background'] , "background");
        $this->assertEquals($data['result']['phone'] , +33480547852);
        $this->assertEquals($data['result']['contact'] , "contact@ium.com");
        $this->assertEquals($data['result']['address_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['id'];
    }

    /**
     * @depends testCanAddSchool
     */
    public function testCanUpdateUserWihtSchool($school_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.update', array('school_id' => $school_id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddSchool
     *
     * @param integer $school_id            
     */
    public function testCanAddProgram($school_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('program.add', array('name' => 'program name','school_id' => $school_id,'level' => 'emba','sis' => 'sis'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddSchool
     */
    public function testCanGetListSchool()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('school.getList', array());
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 2);
        $this->assertEquals(count($data['result']['list']) , 2);
        $this->assertEquals(count($data['result']['list'][0]) , 12);
        $this->assertEquals(count($data['result']['list'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['list'][0]['address']) , 0);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['next_name'] , "Dolor Dolor Foundation");
        $this->assertEquals($data['result']['list'][0]['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['describe'] , "vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non,");
        $this->assertEquals($data['result']['list'][0]['website'] , "http://");
        $this->assertEquals($data['result']['list'][0]['programme'] , "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur");
        $this->assertEquals($data['result']['list'][0]['background'] , null);
        $this->assertEquals($data['result']['list'][0]['phone'] , "04 17 21 41 32");
        $this->assertEquals(count($data['result']['list'][1]) , 12);
        $this->assertEquals(count($data['result']['list'][1]['program']) , 1);
        $this->assertEquals(count($data['result']['list'][1]['program'][0]) , 8);
        $this->assertEquals($data['result']['list'][1]['program'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['program'][0]['name'] , "program name");
        $this->assertEquals($data['result']['list'][1]['program'][0]['school_id'] , 2);
        $this->assertEquals($data['result']['list'][1]['program'][0]['level'] , "emba");
        $this->assertEquals($data['result']['list'][1]['program'][0]['sis'] , "sis");
        $this->assertEquals($data['result']['list'][1]['program'][0]['year'] , null);
        $this->assertEquals($data['result']['list'][1]['program'][0]['deleted_date'] , null);
        $this->assertEquals($data['result']['list'][1]['program'][0]['created_date'] , null);
        $this->assertEquals(count($data['result']['list'][1]['address']) , 14);
        $this->assertEquals(count($data['result']['list'][1]['address']['city']) , 2);
        $this->assertEquals($data['result']['list'][1]['address']['city']['id'] , 19064);
        $this->assertEquals($data['result']['list'][1]['address']['city']['name'] , "Monaco");
        $this->assertEquals(count($data['result']['list'][1]['address']['division']) , 0);
        $this->assertEquals(count($data['result']['list'][1]['address']['country']) , 3);
        $this->assertEquals($data['result']['list'][1]['address']['country']['id'] , 145);
        $this->assertEquals($data['result']['list'][1]['address']['country']['short_name'] , "Monaco");
        $this->assertEquals($data['result']['list'][1]['address']['country']['name'] , "Principality of Monaco");
        $this->assertEquals($data['result']['list'][1]['address']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['address']['street_no'] , 12);
        $this->assertEquals($data['result']['list'][1]['address']['street_type'] , "rue");
        $this->assertEquals($data['result']['list'][1]['address']['street_name'] , "du stade");
        $this->assertEquals(!empty($data['result']['list'][1]['address']['longitude']) , true);
        $this->assertEquals(!empty($data['result']['list'][1]['address']['latitude']) , true);
        $this->assertEquals($data['result']['list'][1]['address']['door'] , null);
        $this->assertEquals($data['result']['list'][1]['address']['building'] , null);
        $this->assertEquals($data['result']['list'][1]['address']['apartment'] , null);
        $this->assertEquals($data['result']['list'][1]['address']['floor'] , null);
        $this->assertEquals($data['result']['list'][1]['address']['timezone'] , 'Europe/Monaco');
        $this->assertEquals($data['result']['list'][1]['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['name'] , "université de monaco");
        $this->assertEquals($data['result']['list'][1]['next_name'] , "buisness school");
        $this->assertEquals($data['result']['list'][1]['short_name'] , "IUM");
        $this->assertEquals($data['result']['list'][1]['logo'] , "token");
        $this->assertEquals($data['result']['list'][1]['describe'] , "une description");
        $this->assertEquals($data['result']['list'][1]['website'] , "www.ium.com");
        $this->assertEquals($data['result']['list'][1]['programme'] , "super programme");
        $this->assertEquals($data['result']['list'][1]['background'] , "background");
        $this->assertEquals($data['result']['list'][1]['phone'] , +33480547852);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddProgram
     *
     * @param integer $program_id            
     */
    public function testCanUpdateProgram($program_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('program.update', array('id' => $program_id,'name' => 'program name upd','level' => 'mba','sis' => 'sisupd'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddSchool
     * @depends testCanAddProgram
     */
    public function testAddCourse($school_id, $program_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.add', array('title' => 'IMERIR','abstract' => 'un_token','description' => 'description','objectives' => 'objectives','teaching' => 'teaching','attendance' => 'attendance','duration' => 18,'notes' => 'notes','learning_outcomes' => 'learning_outcomes','video_link' => 'http://google.fr','video_token' => 'video_token','material_document' => array(array('type' => 'link','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token','date' => '2011-01-01')),'program_id' => $program_id));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 17); 
        $this->assertEquals(count($data['result']['program']) , 2); 
        $this->assertEquals($data['result']['program']['id'] , 1); 
        $this->assertEquals($data['result']['program']['name'] , "program name upd"); 
        $this->assertEquals(count($data['result']['school']) , 3); 
        $this->assertEquals($data['result']['school']['id'] , 2); 
        $this->assertEquals($data['result']['school']['name'] , "université de monaco"); 
        $this->assertEquals($data['result']['school']['logo'] , "token"); 
        $this->assertEquals(count($data['result']['creator']) , 5); 
        $this->assertEquals(count($data['result']['creator']['school']) , 3); 
        $this->assertEquals($data['result']['creator']['school']['id'] , 2); 
        $this->assertEquals($data['result']['creator']['school']['name'] , "université de monaco"); 
        $this->assertEquals($data['result']['creator']['school']['logo'] , "token"); 
        $this->assertEquals($data['result']['creator']['id'] , 1); 
        $this->assertEquals($data['result']['creator']['firstname'] , "Paul"); 
        $this->assertEquals($data['result']['creator']['lastname'] , "Boussekey"); 
        $this->assertEquals($data['result']['creator']['email'] , "pboussekey@thestudnet.com"); 
        $this->assertEquals(count($data['result']['grading_policy']) , 5); 
        $this->assertEquals(count($data['result']['grading_policy'][0]) , 7); 
        $this->assertEquals($data['result']['grading_policy'][0]['id'] , 6); 
        $this->assertEquals($data['result']['grading_policy'][0]['name'] , "Individual assignment"); 
        $this->assertEquals($data['result']['grading_policy'][0]['grade'] , 20); 
        $this->assertEquals($data['result']['grading_policy'][0]['type'] , "IA"); 
        $this->assertEquals($data['result']['grading_policy'][0]['tpl'] , 0); 
        $this->assertEquals($data['result']['grading_policy'][0]['course_id'] , 1); 
        $this->assertEquals($data['result']['grading_policy'][0]['mandatory'] , 1); 
        $this->assertEquals(count($data['result']['grading_policy'][1]) , 7); 
        $this->assertEquals($data['result']['grading_policy'][1]['id'] , 7); 
        $this->assertEquals($data['result']['grading_policy'][1]['name'] , "Group work"); 
        $this->assertEquals($data['result']['grading_policy'][1]['grade'] , 20); 
        $this->assertEquals($data['result']['grading_policy'][1]['type'] , "WG"); 
        $this->assertEquals($data['result']['grading_policy'][1]['tpl'] , 0); 
        $this->assertEquals($data['result']['grading_policy'][1]['course_id'] , 1); 
        $this->assertEquals($data['result']['grading_policy'][1]['mandatory'] , 1); 
        $this->assertEquals(count($data['result']['grading_policy'][2]) , 7); 
        $this->assertEquals($data['result']['grading_policy'][2]['id'] , 8); 
        $this->assertEquals($data['result']['grading_policy'][2]['name'] , "Live class"); 
        $this->assertEquals($data['result']['grading_policy'][2]['grade'] , 20); 
        $this->assertEquals($data['result']['grading_policy'][2]['type'] , "LC"); 
        $this->assertEquals($data['result']['grading_policy'][2]['tpl'] , 0); 
        $this->assertEquals($data['result']['grading_policy'][2]['course_id'] , 1); 
        $this->assertEquals($data['result']['grading_policy'][2]['mandatory'] , 1); 
        $this->assertEquals(count($data['result']['grading_policy'][3]) , 7); 
        $this->assertEquals($data['result']['grading_policy'][3]['id'] , 9); 
        $this->assertEquals($data['result']['grading_policy'][3]['name'] , "Capstone project"); 
        $this->assertEquals($data['result']['grading_policy'][3]['grade'] , 20); 
        $this->assertEquals($data['result']['grading_policy'][3]['type'] , "CP"); 
        $this->assertEquals($data['result']['grading_policy'][3]['tpl'] , 0); 
        $this->assertEquals($data['result']['grading_policy'][3]['course_id'] , 1); 
        $this->assertEquals($data['result']['grading_policy'][3]['mandatory'] , 1); 
        $this->assertEquals(count($data['result']['grading_policy'][4]) , 7); 
        $this->assertEquals($data['result']['grading_policy'][4]['id'] , 10); 
        $this->assertEquals($data['result']['grading_policy'][4]['name'] , "Attendance and participation"); 
        $this->assertEquals($data['result']['grading_policy'][4]['grade'] , 20); 
        $this->assertEquals($data['result']['grading_policy'][4]['type'] , null); 
        $this->assertEquals($data['result']['grading_policy'][4]['tpl'] , 0); 
        $this->assertEquals($data['result']['grading_policy'][4]['course_id'] , 1); 
        $this->assertEquals($data['result']['grading_policy'][4]['mandatory'] , 1); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['title'] , "IMERIR"); 
        $this->assertEquals($data['result']['abstract'] , "un_token"); 
        $this->assertEquals($data['result']['description'] , "description"); 
        $this->assertEquals($data['result']['objectives'] , "objectives"); 
        $this->assertEquals($data['result']['teaching'] , "teaching"); 
        $this->assertEquals($data['result']['attendance'] , "attendance"); 
        $this->assertEquals($data['result']['duration'] , 18); 
        $this->assertEquals($data['result']['notes'] , "notes"); 
        $this->assertEquals($data['result']['learning_outcomes'] , "learning_outcomes"); 
        $this->assertEquals($data['result']['picture'] , null); 
        $this->assertEquals($data['result']['video_link'] , "http://google.fr"); 
        $this->assertEquals($data['result']['video_token'] , "video_token"); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        
        return $data['result']['id'];
    }

    /**
     * @depends testAddCourse
     */
    public function testCanAddUserCourse($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.addCourse', array('user' => 1,'course' => $course));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][1]), 1);
        $this->assertEquals($data['result'][1][1], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testUpdateCourse($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.update', array('id' => $id,'title' => 'IMERIR','abstract' => 'un_token','description' => 'description','objectives' => 'objectives','teaching' => 'teaching','attendance' => 'attendance','duration' => 18,'notes' => 'notes','learning_outcomes' => 'learning_outcomes','video_link' => 'http://google.fr','video_token' => 'video_token'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testAddSet($course)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.add', [
            'course' => $course,
            'name' => 'nameset',
            'uid' => 'suid',
            'groups' => [
                [
                    'name' => 'namegroup',
                    'uid'  => 'guid' ,
                    'users' => [
                        3,4
                    ],
                ],
            ],
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 5);
        $this->assertEquals(count($data['result']['groups']) , 1);
        $this->assertEquals(count($data['result']['groups'][0]) , 4);
        $this->assertEquals(count($data['result']['groups'][0]['users']) , 2);
        $this->assertEquals($data['result']['groups'][0]['users'][0] , 3);
        $this->assertEquals($data['result']['groups'][0]['users'][1] , 4);
        $this->assertEquals($data['result']['groups'][0]['id'] , 1);
        $this->assertEquals($data['result']['groups'][0]['uid'] , "guid");
        $this->assertEquals($data['result']['groups'][0]['name'] , "namegroup");
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['uid'] , "suid");
        $this->assertEquals($data['result']['name'] , "nameset");
        $this->assertEquals($data['result']['course_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['id'];
    }
    
    /**
     * @depends testAddCourse
     */
    public function testReplaceGroup()
    {
    	$this->setIdentity(4);
    	$data = $this->jsonRpc('group.replaceUser', [
    		'id' => 1,
    		'users' =>  [
    			3,4
    		],
    	]);
    
    	$this->assertEquals(count($data) , 3);
    	$this->assertEquals(count($data['result']) , 2);
    	$this->assertEquals($data['result'][3] , true);
    	$this->assertEquals($data['result'][4] , true);
    	$this->assertEquals($data['id'] , 1);
    	$this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testSetGetList($course)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.getList', [ 'course' => $course ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 5);
        $this->assertEquals(count($data['result'][0]['groups']) , 1);
        $this->assertEquals(count($data['result'][0]['groups'][0]) , 4);
        $this->assertEquals(count($data['result'][0]['groups'][0]['users']) , 2);
        $this->assertEquals($data['result'][0]['groups'][0]['users'][0] , 3);
        $this->assertEquals($data['result'][0]['groups'][0]['users'][1] , 4);
        $this->assertEquals($data['result'][0]['groups'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['groups'][0]['uid'] , "guid");
        $this->assertEquals($data['result'][0]['groups'][0]['name'] , "namegroup");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['uid'] , "suid");
        $this->assertEquals($data['result'][0]['name'] , "nameset");
        $this->assertEquals($data['result'][0]['course_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
    /**
     * @depends testAddSet
     */
    public function testUpdateSet($set)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.update', [
            'id' => $set,
            'name' => 'namesetupt',
            'uid' => 'suidupt'
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testAddItemONEONE($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('item.add', array(
            'course' => $id,
            'grading_policy' => 7,
            'duration' => 234,
            'title' => 'titl2e',
            'set' => 1,
            'describe' => 'desone',
            'type' => 'TXT'));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }
    
    /**
     * @depends testAddCourse
     */
    public function testGetListUsers($item)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('item.getListUsers', [
            'item_id' => $item
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][0]) , 12);
        $this->assertEquals($data['result'][0]['gender'] , null);
        $this->assertEquals($data['result'][0]['contact_state'] , 0);
        $this->assertEquals($data['result'][0]['id'] , 3);
        $this->assertEquals($data['result'][0]['firstname'] , "Christophe");
        $this->assertEquals($data['result'][0]['lastname'] , "Robert");
        $this->assertEquals($data['result'][0]['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result'][0]['birth_date'] , null);
        $this->assertEquals($data['result'][0]['position'] , null);
        $this->assertEquals($data['result'][0]['school_id'] , 1);
        $this->assertEquals($data['result'][0]['interest'] , null);
        $this->assertEquals($data['result'][0]['avatar'] , null);
        $this->assertEquals($data['result'][0]['has_email_notifier'] , 1);
        $this->assertEquals(count($data['result'][1]) , 12);
        $this->assertEquals($data['result'][1]['gender'] , null);
        $this->assertEquals($data['result'][1]['contact_state'] , 0);
        $this->assertEquals($data['result'][1]['id'] , 4);
        $this->assertEquals($data['result'][1]['firstname'] , "Salim");
        $this->assertEquals($data['result'][1]['lastname'] , "Bendacha");
        $this->assertEquals($data['result'][1]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result'][1]['birth_date'] , null);
        $this->assertEquals($data['result'][1]['position'] , null);
        $this->assertEquals($data['result'][1]['school_id'] , 1);
        $this->assertEquals($data['result'][1]['interest'] , null);
        $this->assertEquals($data['result'][1]['avatar'] , null);
        $this->assertEquals($data['result'][1]['has_email_notifier'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    /**
     * @depends testAddCourse
     * @depends testAddSet
     * @depends testAddItemONEONE
     */
    public function testAddItem($id, $set, $item)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', 
            [
                'course' => (int)$id,
                'grading_policy' => 6,
                'title' => 'title',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'WG',
                'set' => $set,
                'ct' => [
                    'done'  => [
                        ['target' => $item, 'all' => true],
                    ],
                    'date'  => [
                        ['date' => '2016-01-01', 'after' => true],
                    ],
                    'rate'  => [
                        ['inf' => '2016-01-01', 'sup' => true, 'target' => $item],
                    ],
                    'group' => [
                        ['group' => 1, 'belongs' => true]
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
            ]);
       
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddItem
     */
    public function testGetItem($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.get', array('id' => $id));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 21);
        $this->assertEquals(count($data['result']['program']) , 2);
        $this->assertEquals($data['result']['program']['id'] , 1);
        $this->assertEquals($data['result']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['course']) , 2);
        $this->assertEquals($data['result']['course']['id'] , 1);
        $this->assertEquals($data['result']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['ct_group']) , 1);
        $this->assertEquals(count($data['result']['ct_group'][0]) , 5);
        $this->assertEquals($data['result']['ct_group'][0]['id'] , 1);
        $this->assertEquals($data['result']['ct_group'][0]['item_id'] , 2);
        $this->assertEquals($data['result']['ct_group'][0]['group_id'] , 1);
        $this->assertEquals($data['result']['ct_group'][0]['set_id'] , null);
        $this->assertEquals($data['result']['ct_group'][0]['belongs'] , 1);
        $this->assertEquals(count($data['result']['ct_date']) , 1);
        $this->assertEquals(count($data['result']['ct_date'][0]) , 4);
        $this->assertEquals($data['result']['ct_date'][0]['id'] , 1);
        $this->assertEquals($data['result']['ct_date'][0]['item_id'] , 2);
        $this->assertEquals(!empty($data['result']['ct_date'][0]['date']) , true);
        $this->assertEquals($data['result']['ct_date'][0]['after'] , 1);
        $this->assertEquals(count($data['result']['ct_rate']) , 1);
        $this->assertEquals(count($data['result']['ct_rate'][0]) , 5);
        $this->assertEquals($data['result']['ct_rate'][0]['id'] , 1);
        $this->assertEquals($data['result']['ct_rate'][0]['item_id'] , 2);
        $this->assertEquals($data['result']['ct_rate'][0]['inf'] , 2016);
        $this->assertEquals($data['result']['ct_rate'][0]['sup'] , 1);
        $this->assertEquals($data['result']['ct_rate'][0]['target_id'] , 1);
        $this->assertEquals(count($data['result']['ct_done']) , 1);
        $this->assertEquals(count($data['result']['ct_done'][0]) , 4);
        $this->assertEquals($data['result']['ct_done'][0]['id'] , 1);
        $this->assertEquals($data['result']['ct_done'][0]['item_id'] , 2);
        $this->assertEquals($data['result']['ct_done'][0]['target_id'] , 1);
        $this->assertEquals($data['result']['ct_done'][0]['all'] , 1);
        $this->assertEquals(count($data['result']['opt_videoconf']) , 4);
        $this->assertEquals($data['result']['opt_videoconf']['item_id'] , 2);
        $this->assertEquals($data['result']['opt_videoconf']['record'] , 1);
        $this->assertEquals($data['result']['opt_videoconf']['nb_user_autorecord'] , 2);
        $this->assertEquals($data['result']['opt_videoconf']['allow_intructor'] , 1);
        $this->assertEquals(count($data['result']['opt_grading']) , 8);
        $this->assertEquals($data['result']['opt_grading']['mode'] , "average");
        $this->assertEquals($data['result']['opt_grading']['has_pg'] , 1);
        $this->assertEquals($data['result']['opt_grading']['pg_nb'] , 2);
        $this->assertEquals($data['result']['opt_grading']['pg_auto'] , 1);
        $this->assertEquals(!empty($data['result']['opt_grading']['pg_due_date']) , true);
        $this->assertEquals($data['result']['opt_grading']['pg_can_view'] , 1);
        $this->assertEquals($data['result']['opt_grading']['user_can_view'] , 1);
        $this->assertEquals($data['result']['opt_grading']['pg_stars'] , 1);
        $this->assertEquals($data['result']['id'] , 2);
        $this->assertEquals($data['result']['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy_id'] , 6);
        $this->assertEquals($data['result']['title'] , "title");
        $this->assertEquals($data['result']['describe'] , "description");
        $this->assertEquals($data['result']['duration'] , 234);
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
     * @depends testAddCourse
     * @depends testAddItem
     */
    public function testAddItemTwo($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', array(
            'course' => $id,
            'grading_policy' => 7,
            'duration' => 234,
            'title' => 'titl2e',
            'describe' => 'description2',
            'type' => 'CP'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testUpdateItem($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.update', [
            'id' => (int)$id,
            'grading_policy' => 8,
            'duration' => 123,
            'title' => 'titl2e',
            'describe' => 'description2'
        ]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testAddCourse
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testCanGetListItem($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.getList', array('course' => $course));

        //"\n3:2:1\n";
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 3); 
        $this->assertEquals(count($data['result'][0]) , 13); 
        $this->assertEquals($data['result'][0]['id'] , 3); 
        $this->assertEquals($data['result'][0]['course_id'] , 1); 
        $this->assertEquals($data['result'][0]['grading_policy_id'] , 7); 
        $this->assertEquals($data['result'][0]['title'] , "titl2e"); 
        $this->assertEquals($data['result'][0]['describe'] , "description2"); 
        $this->assertEquals($data['result'][0]['duration'] , 234); 
        $this->assertEquals($data['result'][0]['type'] , "CP"); 
        $this->assertEquals($data['result'][0]['set_id'] , null); 
        $this->assertEquals($data['result'][0]['parent_id'] , null); 
        $this->assertEquals($data['result'][0]['order_id'] , null); 
        $this->assertEquals($data['result'][0]['start'] , null); 
        $this->assertEquals($data['result'][0]['end'] , null); 
        $this->assertEquals($data['result'][0]['cut_off'] , null); 
        $this->assertEquals(count($data['result'][1]) , 13); 
        $this->assertEquals($data['result'][1]['id'] , 2); 
        $this->assertEquals($data['result'][1]['course_id'] , 1); 
        $this->assertEquals($data['result'][1]['grading_policy_id'] , 8); 
        $this->assertEquals($data['result'][1]['title'] , "titl2e"); 
        $this->assertEquals($data['result'][1]['describe'] , "description2"); 
        $this->assertEquals($data['result'][1]['duration'] , 123); 
        $this->assertEquals($data['result'][1]['type'] , "WG"); 
        $this->assertEquals($data['result'][1]['set_id'] , 1); 
        $this->assertEquals($data['result'][1]['parent_id'] , null); 
        $this->assertEquals($data['result'][1]['order_id'] , 3); 
        $this->assertEquals($data['result'][1]['start'] , null); 
        $this->assertEquals($data['result'][1]['end'] , null); 
        $this->assertEquals($data['result'][1]['cut_off'] , null); 
        $this->assertEquals(count($data['result'][2]) , 13); 
        $this->assertEquals($data['result'][2]['id'] , 1); 
        $this->assertEquals($data['result'][2]['course_id'] , 1); 
        $this->assertEquals($data['result'][2]['grading_policy_id'] , 7); 
        $this->assertEquals($data['result'][2]['title'] , "titl2e"); 
        $this->assertEquals($data['result'][2]['describe'] , "desone"); 
        $this->assertEquals($data['result'][2]['duration'] , 234); 
        $this->assertEquals($data['result'][2]['type'] , "TXT"); 
        $this->assertEquals($data['result'][2]['set_id'] , 1); 
        $this->assertEquals($data['result'][2]['parent_id'] , null); 
        $this->assertEquals($data['result'][2]['order_id'] , 2); 
        $this->assertEquals($data['result'][2]['start'] , null); 
        $this->assertEquals($data['result'][2]['end'] , null); 
        $this->assertEquals($data['result'][2]['cut_off'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
    }

    /**
     * @depends testAddCourse
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testCanGetListItemuu($course)
    {
    	$this->setIdentity(1);
    	
    	$this->jsonRpc('item.update', [
    			'id' => 3,
    			'grading_policy' => 8,
    			'duration' => 123,
    			'title' => 'titl2e',
    			'describe' => 'description2',
    			'order_id' => 2
    	]);
    	
    	$this->reset();
    	
    	$this->setIdentity(1);
    
    	$data = $this->jsonRpc('item.getList', array('course' => $course));
    	
    	$this->assertEquals(count($data) , 3);
    	$this->assertEquals(count($data['result']) , 3);
    	$this->assertEquals(count($data['result'][0]) , 13);
    	$this->assertEquals($data['result'][0]['id'] , 2);
    	$this->assertEquals($data['result'][0]['course_id'] , 1);
    	$this->assertEquals($data['result'][0]['grading_policy_id'] , 8);
    	$this->assertEquals($data['result'][0]['title'] , "titl2e");
    	$this->assertEquals($data['result'][0]['describe'] , "description2");
    	$this->assertEquals($data['result'][0]['duration'] , 123);
    	$this->assertEquals($data['result'][0]['type'] , "WG");
    	$this->assertEquals($data['result'][0]['set_id'] , 1);
    	$this->assertEquals($data['result'][0]['parent_id'] , null);
    	$this->assertEquals($data['result'][0]['order_id'] , null);
    	$this->assertEquals($data['result'][0]['start'] , null);
    	$this->assertEquals($data['result'][0]['end'] , null);
    	$this->assertEquals($data['result'][0]['cut_off'] , null);
    	$this->assertEquals(count($data['result'][1]) , 13);
    	$this->assertEquals($data['result'][1]['id'] , 3);
    	$this->assertEquals($data['result'][1]['course_id'] , 1);
    	$this->assertEquals($data['result'][1]['grading_policy_id'] , 8);
    	$this->assertEquals($data['result'][1]['title'] , "titl2e");
    	$this->assertEquals($data['result'][1]['describe'] , "description2");
    	$this->assertEquals($data['result'][1]['duration'] , 123);
    	$this->assertEquals($data['result'][1]['type'] , "CP");
    	$this->assertEquals($data['result'][1]['set_id'] , null);
    	$this->assertEquals($data['result'][1]['parent_id'] , null);
    	$this->assertEquals($data['result'][1]['order_id'] , 2);
    	$this->assertEquals($data['result'][1]['start'] , null);
    	$this->assertEquals($data['result'][1]['end'] , null);
    	$this->assertEquals($data['result'][1]['cut_off'] , null);
    	$this->assertEquals(count($data['result'][2]) , 13);
    	$this->assertEquals($data['result'][2]['id'] , 1);
    	$this->assertEquals($data['result'][2]['course_id'] , 1);
    	$this->assertEquals($data['result'][2]['grading_policy_id'] , 7);
    	$this->assertEquals($data['result'][2]['title'] , "titl2e");
    	$this->assertEquals($data['result'][2]['describe'] , "desone");
    	$this->assertEquals($data['result'][2]['duration'] , 234);
    	$this->assertEquals($data['result'][2]['type'] , "TXT");
    	$this->assertEquals($data['result'][2]['set_id'] , 1);
    	$this->assertEquals($data['result'][2]['parent_id'] , null);
    	$this->assertEquals($data['result'][2]['order_id'] , 3);
    	$this->assertEquals($data['result'][2]['start'] , null);
    	$this->assertEquals($data['result'][2]['end'] , null);
    	$this->assertEquals($data['result'][2]['cut_off'] , null);
    	$this->assertEquals($data['id'] , 1);
    	$this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCourse
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testCanGetListItemuuo($course)
    {
    	$this->setIdentity(1);
    	
    	$data = $this->jsonRpc('item.update', [
    			'id' => 3,
    			'order_id' => 0,
    			'parent_id' => 0
    	]);
    	
    	$this->reset();
    	
    	$this->setIdentity(1);
    
    	$data = $this->jsonRpc('item.getList', array('course' => $course));
    	// "\n3:2:1\n";
    	
    	$this->assertEquals(count($data) , 3);
    	$this->assertEquals(count($data['result']) , 3);
    	$this->assertEquals(count($data['result'][0]) , 13);
    	$this->assertEquals($data['result'][0]['id'] , 3);
    	$this->assertEquals($data['result'][0]['course_id'] , 1);
    	$this->assertEquals($data['result'][0]['grading_policy_id'] , 8);
    	$this->assertEquals($data['result'][0]['title'] , "titl2e");
    	$this->assertEquals($data['result'][0]['describe'] , "description2");
    	$this->assertEquals($data['result'][0]['duration'] , 123);
    	$this->assertEquals($data['result'][0]['type'] , "CP");
    	$this->assertEquals($data['result'][0]['set_id'] , null);
    	$this->assertEquals($data['result'][0]['parent_id'] , null);
    	$this->assertEquals($data['result'][0]['order_id'] , null);
    	$this->assertEquals($data['result'][0]['start'] , null);
    	$this->assertEquals($data['result'][0]['end'] , null);
    	$this->assertEquals($data['result'][0]['cut_off'] , null);
    	$this->assertEquals(count($data['result'][1]) , 13);
    	$this->assertEquals($data['result'][1]['id'] , 2);
    	$this->assertEquals($data['result'][1]['course_id'] , 1);
    	$this->assertEquals($data['result'][1]['grading_policy_id'] , 8);
    	$this->assertEquals($data['result'][1]['title'] , "titl2e");
    	$this->assertEquals($data['result'][1]['describe'] , "description2");
    	$this->assertEquals($data['result'][1]['duration'] , 123);
    	$this->assertEquals($data['result'][1]['type'] , "WG");
    	$this->assertEquals($data['result'][1]['set_id'] , 1);
    	$this->assertEquals($data['result'][1]['parent_id'] , null);
    	$this->assertEquals($data['result'][1]['order_id'] , 3);
    	$this->assertEquals($data['result'][1]['start'] , null);
    	$this->assertEquals($data['result'][1]['end'] , null);
    	$this->assertEquals($data['result'][1]['cut_off'] , null);
    	$this->assertEquals(count($data['result'][2]) , 13);
    	$this->assertEquals($data['result'][2]['id'] , 1);
    	$this->assertEquals($data['result'][2]['course_id'] , 1);
    	$this->assertEquals($data['result'][2]['grading_policy_id'] , 7);
    	$this->assertEquals($data['result'][2]['title'] , "titl2e");
    	$this->assertEquals($data['result'][2]['describe'] , "desone");
    	$this->assertEquals($data['result'][2]['duration'] , 234);
    	$this->assertEquals($data['result'][2]['type'] , "TXT");
    	$this->assertEquals($data['result'][2]['set_id'] , 1);
    	$this->assertEquals($data['result'][2]['parent_id'] , null);
    	$this->assertEquals($data['result'][2]['order_id'] , 2);
    	$this->assertEquals($data['result'][2]['start'] , null);
    	$this->assertEquals($data['result'][2]['end'] , null);
    	$this->assertEquals($data['result'][2]['cut_off'] , null);
    	$this->assertEquals($data['id'] , 1);
    	$this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCourse
     * @depends testAddItem
     */
    public function testAddItemu($id)
    {
    	$this->setIdentity(1);
    
    	$data = $this->jsonRpc('item.add', array(
    			'course' => $id,
    			'grading_policy' => 7,
    			'duration' => 234,
    			'title' => 'titl2e',
    			'describe' => 'description2',
    			'type' => 'CP',
    			'parent_id' => 3
    			
    	));
    
    	$this->assertEquals(count($data), 3);
    	$this->assertEquals($data['result'], 4);
    	$this->assertEquals($data['id'], 1);
    	$this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testAddCourse
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testCanGetListItem2($course)
    {
    	$this->setIdentity(1);
    
    	$data = $this->jsonRpc('item.getList', array('course' => $course, 'parent_id' => 3));
    	
    	$this->assertEquals(count($data) , 3);
    	$this->assertEquals(count($data['result']) , 1);
    	$this->assertEquals(count($data['result'][0]) , 13);
    	$this->assertEquals($data['result'][0]['id'] , 4);
    	$this->assertEquals($data['result'][0]['course_id'] , 1);
    	$this->assertEquals($data['result'][0]['grading_policy_id'] , 7);
    	$this->assertEquals($data['result'][0]['title'] , "titl2e");
    	$this->assertEquals($data['result'][0]['describe'] , "description2");
    	$this->assertEquals($data['result'][0]['duration'] , 234);
    	$this->assertEquals($data['result'][0]['type'] , "CP");
    	$this->assertEquals($data['result'][0]['set_id'] , null);
    	$this->assertEquals($data['result'][0]['parent_id'] , 3);
    	$this->assertEquals($data['result'][0]['order_id'] , null);
    	$this->assertEquals($data['result'][0]['start'] , null);
    	$this->assertEquals($data['result'][0]['end'] , null);
    	$this->assertEquals($data['result'][0]['cut_off'] , null);
    	$this->assertEquals($data['id'] , 1);
    	$this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testCanAddUserInstructorCourse($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.addCourse', array('user' => 5,'course' => $course));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][5]), 1);
        $this->assertEquals($data['result'][5][1], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testUpdateCourse
     */
    public function testGetCourse($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.get', array('id' => $id));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 18);
        $this->assertEquals(count($data['result']['program']) , 2);
        $this->assertEquals($data['result']['program']['id'] , 1);
        $this->assertEquals($data['result']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['school']) , 3);
        $this->assertEquals($data['result']['school']['id'] , 2);
        $this->assertEquals($data['result']['school']['name'] , "université de monaco");
        $this->assertEquals($data['result']['school']['logo'] , "token");
        $this->assertEquals(count($data['result']['instructor']) , 1);
        $this->assertEquals(count($data['result']['instructor'][0]) , 12);
        $this->assertEquals($data['result']['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals($data['result']['instructor'][0]['contact_state'] , 0);
        $this->assertEquals(count($data['result']['instructor'][0]['school']) , 5);
        $this->assertEquals($data['result']['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['instructor'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['instructor'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['instructor'][0]['id'] , 5);
        $this->assertEquals($data['result']['instructor'][0]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['instructor'][0]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['instructor'][0]['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['instructor'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['instructor'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['instructor'][0]['position'] , null);
        $this->assertEquals($data['result']['instructor'][0]['interest'] , null);
        $this->assertEquals($data['result']['instructor'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['creator']) , 5);
        $this->assertEquals(count($data['result']['creator']['school']) , 3);
        $this->assertEquals($data['result']['creator']['school']['id'] , 2);
        $this->assertEquals($data['result']['creator']['school']['name'] , "université de monaco");
        $this->assertEquals($data['result']['creator']['school']['logo'] , "token");
        $this->assertEquals($data['result']['creator']['id'] , 1);
        $this->assertEquals($data['result']['creator']['firstname'] , "Paul");
        $this->assertEquals($data['result']['creator']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['creator']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals(count($data['result']['grading_policy']) , 5);
        $this->assertEquals(count($data['result']['grading_policy'][0]) , 7);
        $this->assertEquals($data['result']['grading_policy'][0]['id'] , 6);
        $this->assertEquals($data['result']['grading_policy'][0]['name'] , "Individual assignment");
        $this->assertEquals($data['result']['grading_policy'][0]['grade'] , 20);
        $this->assertEquals($data['result']['grading_policy'][0]['type'] , "IA");
        $this->assertEquals($data['result']['grading_policy'][0]['tpl'] , 0);
        $this->assertEquals($data['result']['grading_policy'][0]['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy'][0]['mandatory'] , 1);
        $this->assertEquals(count($data['result']['grading_policy'][1]) , 7);
        $this->assertEquals($data['result']['grading_policy'][1]['id'] , 7);
        $this->assertEquals($data['result']['grading_policy'][1]['name'] , "Group work");
        $this->assertEquals($data['result']['grading_policy'][1]['grade'] , 20);
        $this->assertEquals($data['result']['grading_policy'][1]['type'] , "WG");
        $this->assertEquals($data['result']['grading_policy'][1]['tpl'] , 0);
        $this->assertEquals($data['result']['grading_policy'][1]['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy'][1]['mandatory'] , 1);
        $this->assertEquals(count($data['result']['grading_policy'][2]) , 7);
        $this->assertEquals($data['result']['grading_policy'][2]['id'] , 8);
        $this->assertEquals($data['result']['grading_policy'][2]['name'] , "Live class");
        $this->assertEquals($data['result']['grading_policy'][2]['grade'] , 20);
        $this->assertEquals($data['result']['grading_policy'][2]['type'] , "LC");
        $this->assertEquals($data['result']['grading_policy'][2]['tpl'] , 0);
        $this->assertEquals($data['result']['grading_policy'][2]['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy'][2]['mandatory'] , 1);
        $this->assertEquals(count($data['result']['grading_policy'][3]) , 7);
        $this->assertEquals($data['result']['grading_policy'][3]['id'] , 9);
        $this->assertEquals($data['result']['grading_policy'][3]['name'] , "Capstone project");
        $this->assertEquals($data['result']['grading_policy'][3]['grade'] , 20);
        $this->assertEquals($data['result']['grading_policy'][3]['type'] , "CP");
        $this->assertEquals($data['result']['grading_policy'][3]['tpl'] , 0);
        $this->assertEquals($data['result']['grading_policy'][3]['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy'][3]['mandatory'] , 1);
        $this->assertEquals(count($data['result']['grading_policy'][4]) , 7);
        $this->assertEquals($data['result']['grading_policy'][4]['id'] , 10);
        $this->assertEquals($data['result']['grading_policy'][4]['name'] , "Attendance and participation");
        $this->assertEquals($data['result']['grading_policy'][4]['grade'] , 20);
        $this->assertEquals($data['result']['grading_policy'][4]['type'] , null);
        $this->assertEquals($data['result']['grading_policy'][4]['tpl'] , 0);
        $this->assertEquals($data['result']['grading_policy'][4]['course_id'] , 1);
        $this->assertEquals($data['result']['grading_policy'][4]['mandatory'] , 1);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "IMERIR");
        $this->assertEquals($data['result']['abstract'] , "un_token");
        $this->assertEquals($data['result']['description'] , "description");
        $this->assertEquals($data['result']['objectives'] , "objectives");
        $this->assertEquals($data['result']['teaching'] , "teaching");
        $this->assertEquals($data['result']['attendance'] , "attendance");
        $this->assertEquals($data['result']['duration'] , 18);
        $this->assertEquals($data['result']['notes'] , "notes");
        $this->assertEquals($data['result']['learning_outcomes'] , "learning_outcomes");
        $this->assertEquals($data['result']['picture'] , null);
        $this->assertEquals($data['result']['video_link'] , "http://google.fr");
        $this->assertEquals($data['result']['video_token'] , "video_token");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddProgram
     */
    public function testCourseGetListOne($program)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('course.getList', array('program' => $program));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 15);
        $this->assertEquals(count($data['result']['list'][0]['instructor']) , 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]) , 12);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contact_state'] , 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']) , 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'] , 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "IMERIR");
        $this->assertEquals($data['result']['list'][0]['abstract'] , "un_token");
        $this->assertEquals($data['result']['list'][0]['description'] , "description");
        $this->assertEquals($data['result']['list'][0]['objectives'] , "objectives");
        $this->assertEquals($data['result']['list'][0]['teaching'] , "teaching");
        $this->assertEquals($data['result']['list'][0]['attendance'] , "attendance");
        $this->assertEquals($data['result']['list'][0]['duration'] , 18);
        $this->assertEquals($data['result']['list'][0]['notes'] , "notes");
        $this->assertEquals($data['result']['list'][0]['learning_outcomes'] , "learning_outcomes");
        $this->assertEquals($data['result']['list'][0]['picture'] , null);
        $this->assertEquals($data['result']['list'][0]['video_link'] , "http://google.fr");
        $this->assertEquals($data['result']['list'][0]['video_token'] , "video_token");
        $this->assertEquals($data['result']['list'][0]['program_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
   /* public function testgetGrading($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('grading.getByCourse', array('course' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 12);
        $this->assertEquals(count($data['result'][0]), 8);
        $this->assertEquals($data['result'][0]['id'], 13);
        $this->assertEquals($data['result'][0]['letter'], "A");
        $this->assertEquals($data['result'][0]['min'], 95);
        $this->assertEquals($data['result'][0]['max'], 100);
        $this->assertEquals($data['result'][0]['grade'], 4);
        $this->assertEquals($data['result'][0]['description'], "Outstanding performance, works shows superior command of the subject.");
        $this->assertEquals($data['result'][0]['tpl'], 0);
        $this->assertEquals($data['result'][0]['school_id'], 2);
        $this->assertEquals(count($data['result'][1]), 8);
        $this->assertEquals($data['result'][1]['id'], 14);
        $this->assertEquals($data['result'][1]['letter'], "A-");
        $this->assertEquals($data['result'][1]['min'], 90);
        $this->assertEquals($data['result'][1]['max'], 94);
        $this->assertEquals($data['result'][1]['grade'], 3.7);
        $this->assertEquals($data['result'][1]['description'], "Very good work showing understanding and mastery of all concepts.");
        $this->assertEquals($data['result'][1]['tpl'], 0);
        $this->assertEquals($data['result'][1]['school_id'], 2);
        $this->assertEquals(count($data['result'][2]), 8);
        $this->assertEquals($data['result'][2]['id'], 15);
        $this->assertEquals($data['result'][2]['letter'], "B+");
        $this->assertEquals($data['result'][2]['min'], 87);
        $this->assertEquals($data['result'][2]['max'], 89);
        $this->assertEquals($data['result'][2]['grade'], 3.3);
        $this->assertEquals($data['result'][2]['description'], "Good work showing understanding and mastery of most concepts.");
        $this->assertEquals($data['result'][2]['tpl'], 0);
        $this->assertEquals($data['result'][2]['school_id'], 2);
        $this->assertEquals(count($data['result'][3]), 8);
        $this->assertEquals($data['result'][3]['id'], 16);
        $this->assertEquals($data['result'][3]['letter'], "B");
        $this->assertEquals($data['result'][3]['min'], 83);
        $this->assertEquals($data['result'][3]['max'], 86);
        $this->assertEquals($data['result'][3]['grade'], 3);
        $this->assertEquals($data['result'][3]['description'], "Fairly good work that shows an understanding of the main concepts.");
        $this->assertEquals($data['result'][3]['tpl'], 0);
        $this->assertEquals($data['result'][3]['school_id'], 2);
        $this->assertEquals(count($data['result'][4]), 8);
        $this->assertEquals($data['result'][4]['id'], 17);
        $this->assertEquals($data['result'][4]['letter'], "B-");
        $this->assertEquals($data['result'][4]['min'], 80);
        $this->assertEquals($data['result'][4]['max'], 82);
        $this->assertEquals($data['result'][4]['grade'], 2.7);
        $this->assertEquals($data['result'][4]['description'], "Fairly good work showing understanding of several important concepts.");
        $this->assertEquals($data['result'][4]['tpl'], 0);
        $this->assertEquals($data['result'][4]['school_id'], 2);
        $this->assertEquals(count($data['result'][5]), 8);
        $this->assertEquals($data['result'][5]['id'], 18);
        $this->assertEquals($data['result'][5]['letter'], "C+");
        $this->assertEquals($data['result'][5]['min'], 77);
        $this->assertEquals($data['result'][5]['max'], 79);
        $this->assertEquals($data['result'][5]['grade'], 2.3);
        $this->assertEquals($data['result'][5]['description'], "Uneven understanding of the concepts with occasional lack of clarity");
        $this->assertEquals($data['result'][5]['tpl'], 0);
        $this->assertEquals($data['result'][5]['school_id'], 2);
        $this->assertEquals(count($data['result'][6]), 8);
        $this->assertEquals($data['result'][6]['id'], 19);
        $this->assertEquals($data['result'][6]['letter'], "C");
        $this->assertEquals($data['result'][6]['min'], 73);
        $this->assertEquals($data['result'][6]['max'], 76);
        $this->assertEquals($data['result'][6]['grade'], 2);
        $this->assertEquals($data['result'][6]['description'], "Work that barely meets modest expectations for the class");
        $this->assertEquals($data['result'][6]['tpl'], 0);
        $this->assertEquals($data['result'][6]['school_id'], 2);
        $this->assertEquals(count($data['result'][7]), 8);
        $this->assertEquals($data['result'][7]['id'], 20);
        $this->assertEquals($data['result'][7]['letter'], "C-");
        $this->assertEquals($data['result'][7]['min'], 70);
        $this->assertEquals($data['result'][7]['max'], 72);
        $this->assertEquals($data['result'][7]['grade'], 1.7);
        $this->assertEquals($data['result'][7]['description'], "Work that is below modest expectations for the class");
        $this->assertEquals($data['result'][7]['tpl'], 0);
        $this->assertEquals($data['result'][7]['school_id'], 2);
        $this->assertEquals(count($data['result'][8]), 8);
        $this->assertEquals($data['result'][8]['id'], 21);
        $this->assertEquals($data['result'][8]['letter'], "D+");
        $this->assertEquals($data['result'][8]['min'], 67);
        $this->assertEquals($data['result'][8]['max'], 69);
        $this->assertEquals($data['result'][8]['grade'], 1.3);
        $this->assertEquals($data['result'][8]['description'], "Poor performance with lack of understanding of several important concepts");
        $this->assertEquals($data['result'][8]['tpl'], 0);
        $this->assertEquals($data['result'][8]['school_id'], 2);
        $this->assertEquals(count($data['result'][9]), 8);
        $this->assertEquals($data['result'][9]['id'], 22);
        $this->assertEquals($data['result'][9]['letter'], "D");
        $this->assertEquals($data['result'][9]['min'], 63);
        $this->assertEquals($data['result'][9]['max'], 66);
        $this->assertEquals($data['result'][9]['grade'], 1);
        $this->assertEquals($data['result'][9]['description'], "Work that is marginally above the minimum expectations for the class");
        $this->assertEquals($data['result'][9]['tpl'], 0);
        $this->assertEquals($data['result'][9]['school_id'], 2);
        $this->assertEquals(count($data['result'][10]), 8);
        $this->assertEquals($data['result'][10]['id'], 23);
        $this->assertEquals($data['result'][10]['letter'], "D-");
        $this->assertEquals($data['result'][10]['min'], 60);
        $this->assertEquals($data['result'][10]['max'], 62);
        $this->assertEquals($data['result'][10]['grade'], 0.7);
        $this->assertEquals($data['result'][10]['description'], "Work that barely meets the minimum expectations for the class");
        $this->assertEquals($data['result'][10]['tpl'], 0);
        $this->assertEquals($data['result'][10]['school_id'], 2);
        $this->assertEquals(count($data['result'][11]), 8);
        $this->assertEquals($data['result'][11]['id'], 24);
        $this->assertEquals($data['result'][11]['letter'], "F");
        $this->assertEquals($data['result'][11]['min'], 0);
        $this->assertEquals($data['result'][11]['max'], 59);
        $this->assertEquals($data['result'][11]['grade'], 0);
        $this->assertEquals($data['result'][11]['description'], "Work does not meet the minimum expectations for the class");
        $this->assertEquals($data['result'][11]['tpl'], 0);
        $this->assertEquals($data['result'][11]['school_id'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
*/
    /**
     * @depends testCanAddSchool
     */
    /*
    public function testAddGrading($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('grading.update', array('school' => $id,'datas' => array(array('letter' => 'A','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'),array('letter' => 'B','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'),array('letter' => 'C','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'),array('letter' => 'D','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'))));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }*/

    /**
     * @depends testAddCourse
     */
    /*
    public function testgetGradingAfter($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('grading.getByCourse', array('course' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals(count($data['result'][0]), 8);
        $this->assertEquals($data['result'][0]['id'], 25);
        $this->assertEquals($data['result'][0]['letter'], "A");
        $this->assertEquals($data['result'][0]['min'], 5);
        $this->assertEquals($data['result'][0]['max'], 50);
        $this->assertEquals($data['result'][0]['grade'], 6.5);
        $this->assertEquals($data['result'][0]['description'], "description");
        $this->assertEquals($data['result'][0]['tpl'], 0);
        $this->assertEquals($data['result'][0]['school_id'], 2);
        $this->assertEquals(count($data['result'][1]), 8);
        $this->assertEquals($data['result'][1]['id'], 26);
        $this->assertEquals($data['result'][1]['letter'], "B");
        $this->assertEquals($data['result'][1]['min'], 5);
        $this->assertEquals($data['result'][1]['max'], 50);
        $this->assertEquals($data['result'][1]['grade'], 6.5);
        $this->assertEquals($data['result'][1]['description'], "description");
        $this->assertEquals($data['result'][1]['tpl'], 0);
        $this->assertEquals($data['result'][1]['school_id'], 2);
        $this->assertEquals(count($data['result'][2]), 8);
        $this->assertEquals($data['result'][2]['id'], 27);
        $this->assertEquals($data['result'][2]['letter'], "C");
        $this->assertEquals($data['result'][2]['min'], 5);
        $this->assertEquals($data['result'][2]['max'], 50);
        $this->assertEquals($data['result'][2]['grade'], 6.5);
        $this->assertEquals($data['result'][2]['description'], "description");
        $this->assertEquals($data['result'][2]['tpl'], 0);
        $this->assertEquals($data['result'][2]['school_id'], 2);
        $this->assertEquals(count($data['result'][3]), 8);
        $this->assertEquals($data['result'][3]['id'], 28);
        $this->assertEquals($data['result'][3]['letter'], "D");
        $this->assertEquals($data['result'][3]['min'], 5);
        $this->assertEquals($data['result'][3]['max'], 50);
        $this->assertEquals($data['result'][3]['grade'], 6.5);
        $this->assertEquals($data['result'][3]['description'], "description");
        $this->assertEquals($data['result'][3]['tpl'], 0);
        $this->assertEquals($data['result'][3]['school_id'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    */

    /**
     * @depends testAddCourse
     */
    public function testAddThread($course)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('thread.add', array('title' => 'un titre','course' => $course,'message' => 'super messge'));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    
        return $data['result'];
    }
    
    /**
     * @depends testAddThread
     */
    public function testUpdateThread($thread)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('thread.update', array('title' => 'un titre update','id' => $thread));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testGetThread($course)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.getList', array('course' => $course, 'name' => 'un'));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 8);
        $this->assertEquals(count($data['result']['list'][0]['course']), 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['message']), 4);
        $this->assertEquals(count($data['result']['list'][0]['message']['user']), 4);
        $this->assertEquals($data['result']['list'][0]['message']['user']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['message']['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['message']['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['message'], "super messge");
        $this->assertEquals(! empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['nb_message'], 1);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0], "super_admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "un titre update");
        $this->assertEquals(! empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testGetGradingPolicy($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('gradingpolicy.get', array('course' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 5);
        $this->assertEquals(count($data['result'][0]), 7);
        $this->assertEquals($data['result'][0]['id'], 6);
        $this->assertEquals($data['result'][0]['name'], "Individual assignment");
        $this->assertEquals($data['result'][0]['grade'], 20);
        $this->assertEquals($data['result'][0]['type'], "IA");
        $this->assertEquals($data['result'][0]['tpl'], 0);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['mandatory'], 1);
        $this->assertEquals(count($data['result'][1]), 7);
        $this->assertEquals($data['result'][1]['id'], 7);
        $this->assertEquals($data['result'][1]['name'], "Group work");
        $this->assertEquals($data['result'][1]['grade'], 20);
        $this->assertEquals($data['result'][1]['type'], "WG");
        $this->assertEquals($data['result'][1]['tpl'], 0);
        $this->assertEquals($data['result'][1]['course_id'], 1);
        $this->assertEquals($data['result'][1]['mandatory'], 1);
        $this->assertEquals(count($data['result'][2]), 7);
        $this->assertEquals($data['result'][2]['id'], 8);
        $this->assertEquals($data['result'][2]['name'], "Live class");
        $this->assertEquals($data['result'][2]['grade'], 20);
        $this->assertEquals($data['result'][2]['type'], "LC");
        $this->assertEquals($data['result'][2]['tpl'], 0);
        $this->assertEquals($data['result'][2]['course_id'], 1);
        $this->assertEquals($data['result'][2]['mandatory'], 1);
        $this->assertEquals(count($data['result'][3]), 7);
        $this->assertEquals($data['result'][3]['id'], 9);
        $this->assertEquals($data['result'][3]['name'], "Capstone project");
        $this->assertEquals($data['result'][3]['grade'], 20);
        $this->assertEquals($data['result'][3]['type'], "CP");
        $this->assertEquals($data['result'][3]['tpl'], 0);
        $this->assertEquals($data['result'][3]['course_id'], 1);
        $this->assertEquals($data['result'][3]['mandatory'], 1);
        $this->assertEquals(count($data['result'][4]), 7);
        $this->assertEquals($data['result'][4]['id'], 10);
        $this->assertEquals($data['result'][4]['name'], "Attendance and participation");
        $this->assertEquals($data['result'][4]['grade'], 20);
        $this->assertEquals($data['result'][4]['type'], null);
        $this->assertEquals($data['result'][4]['tpl'], 0);
        $this->assertEquals($data['result'][4]['course_id'], 1);
        $this->assertEquals($data['result'][4]['mandatory'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddGradingPolicy($course)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('gradingpolicy.add', array('course_id' => $course,'name' => 'tata','grade' => 50));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 11);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddGradingPolicy
     */
    public function testUpdateGradingPolicy($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('gradingpolicy.update', array('datas' => array(array('id' => $id,'name' => 'toto','grade' => 60))));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals($data['result'][11], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetGradingPolicyAfter($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('gradingpolicy.get', array('course' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 6);
        $this->assertEquals(count($data['result'][0]), 7);
        $this->assertEquals($data['result'][0]['id'], 6);
        $this->assertEquals($data['result'][0]['name'], "Individual assignment");
        $this->assertEquals($data['result'][0]['grade'], 20);
        $this->assertEquals($data['result'][0]['type'], "IA");
        $this->assertEquals($data['result'][0]['tpl'], 0);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['mandatory'], 1);
        $this->assertEquals(count($data['result'][1]), 7);
        $this->assertEquals($data['result'][1]['id'], 7);
        $this->assertEquals($data['result'][1]['name'], "Group work");
        $this->assertEquals($data['result'][1]['grade'], 20);
        $this->assertEquals($data['result'][1]['type'], "WG");
        $this->assertEquals($data['result'][1]['tpl'], 0);
        $this->assertEquals($data['result'][1]['course_id'], 1);
        $this->assertEquals($data['result'][1]['mandatory'], 1);
        $this->assertEquals(count($data['result'][2]), 7);
        $this->assertEquals($data['result'][2]['id'], 8);
        $this->assertEquals($data['result'][2]['name'], "Live class");
        $this->assertEquals($data['result'][2]['grade'], 20);
        $this->assertEquals($data['result'][2]['type'], "LC");
        $this->assertEquals($data['result'][2]['tpl'], 0);
        $this->assertEquals($data['result'][2]['course_id'], 1);
        $this->assertEquals($data['result'][2]['mandatory'], 1);
        $this->assertEquals(count($data['result'][3]), 7);
        $this->assertEquals($data['result'][3]['id'], 9);
        $this->assertEquals($data['result'][3]['name'], "Capstone project");
        $this->assertEquals($data['result'][3]['grade'], 20);
        $this->assertEquals($data['result'][3]['type'], "CP");
        $this->assertEquals($data['result'][3]['tpl'], 0);
        $this->assertEquals($data['result'][3]['course_id'], 1);
        $this->assertEquals($data['result'][3]['mandatory'], 1);
        $this->assertEquals(count($data['result'][4]), 7);
        $this->assertEquals($data['result'][4]['id'], 10);
        $this->assertEquals($data['result'][4]['name'], "Attendance and participation");
        $this->assertEquals($data['result'][4]['grade'], 20);
        $this->assertEquals($data['result'][4]['type'], null);
        $this->assertEquals($data['result'][4]['tpl'], 0);
        $this->assertEquals($data['result'][4]['course_id'], 1);
        $this->assertEquals($data['result'][4]['mandatory'], 1);
        $this->assertEquals(count($data['result'][5]), 7);
        $this->assertEquals($data['result'][5]['id'], 11);
        $this->assertEquals($data['result'][5]['name'], "toto");
        $this->assertEquals($data['result'][5]['grade'], 60);
        $this->assertEquals($data['result'][5]['type'], null);
        $this->assertEquals($data['result'][5]['tpl'], 0);
        $this->assertEquals($data['result'][5]['course_id'], 1);
        $this->assertEquals($data['result'][5]['mandatory'], 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     */
    /*public function testCanAddProgrmItem($item)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.add', array('item' => $item,'start_date' => '2017-01-01 10:10','due_date' => '2018-01-01 10:10','users' => array(1,2,3,4)));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }*/

    /**
     * @depends testCanAddProgrmItem
     */
    /*public function testCanUpdateProgrmItem($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.update', array('id' => $item_prog,'start_date' => '2017-06-01 12:10','users' => array(6)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }/

    /**
     * @depends testCanAddProgrmItem
     */
    /*public function testCanAddItemsProgUSer($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.addUser', array('item_prog' => $item_prog,'user' => array(1,2,6,4)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result'][0], 4);
        $this->assertEquals($data['result'][1], 6);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }*/
    
    
    public function testGetQuestionaire($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('questionnaire.getByItem', array('item' => $item));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 6);
        $this->assertEquals(count($data['result']['questions']) , 9);
        $this->assertEquals(count($data['result']['questions'][0]) , 3);
        $this->assertEquals(count($data['result']['questions'][0]['component']) , 2);
        $this->assertEquals($data['result']['questions'][0]['component']['id'] , 1);
        $this->assertEquals($data['result']['questions'][0]['component']['name'] , "Multicultural awareness");
        $this->assertEquals(is_numeric($data['result']['questions'][0]['id']) , true);
        $this->assertEquals(!empty($data['result']['questions'][0]['text']) , true);
        $this->assertEquals(count($data['result']['questions'][1]) , 3);
        $this->assertEquals(count($data['result']['questions'][1]['component']) , 2);
        $this->assertEquals($data['result']['questions'][1]['component']['id'] , 2);
        $this->assertEquals($data['result']['questions'][1]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals(is_numeric($data['result']['questions'][1]['id']) , true);
        $this->assertEquals(!empty($data['result']['questions'][1]['text']) , true);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['item_id'] , 1);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['max_duration'] , 30);
        $this->assertEquals($data['result']['max_time'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
         
        return $data['result']['questions'];
    }

    /**
     * @depends testGetQuestionaire
     */
    public function testGetQuestionaireAnswer($item_prog, $questions)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('questionnaire.answer', array('item_prog' => $item_prog,'user' => 6,'question' => $questions[1]['id'],'scale' => 3));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testGetAnswer($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('questionnaire.getAnswer', array('item' => $item));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 8);
        $this->assertEquals(count($data['result']['answers']) , 1);
        $this->assertEquals(count($data['result']['answers'][0]) , 8);
        $this->assertEquals($data['result']['answers'][0]['id'] , 1);
        $this->assertEquals($data['result']['answers'][0]['questionnaire_user_id'] , 1);
        $this->assertEquals($data['result']['answers'][0]['questionnaire_question_id'] , 2);
        $this->assertEquals(is_numeric($data['result']['answers'][0]['question_id']) , true);
        $this->assertEquals($data['result']['answers'][0]['peer_id'] , 6);
        $this->assertEquals($data['result']['answers'][0]['scale_id'] , 3);
        $this->assertEquals($data['result']['answers'][0]['type'] , "PEER");
        $this->assertEquals(!empty($data['result']['answers'][0]['created_date']) , true);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['user_id'] , 4);
        $this->assertEquals($data['result']['questionnaire_id'] , 1);
        $this->assertEquals($data['result']['item_prog_user_id'] , 3);
        $this->assertEquals($data['result']['state'] , null);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['end_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testGetListAnswerItemprog($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('answer.getList', array('item_prog' => $item_prog));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 14);
        $this->assertEquals($data['result'][0]['course'], 1);
        $this->assertEquals($data['result'][0]['item'], 1);
        $this->assertEquals($data['result'][0]['origin_name'], null);
        $this->assertEquals($data['result'][0]['origin'], null);
        $this->assertEquals($data['result'][0]['nationality_name'], null);
        $this->assertEquals($data['result'][0]['nationality'], null);
        $this->assertEquals($data['result'][0]['gender'], null);
        $this->assertEquals($data['result'][0]['dimension'], 1);
        $this->assertEquals($data['result'][0]['component'], 2);
        $this->assertEquals($data['result'][0]['scale'], 3);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['peer_id'], 6);
        $this->assertEquals($data['result'][0]['type'], "PEER");
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testGetListAnswerPeer($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('answer.getList', array('peer' => 6));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 14);
        $this->assertEquals($data['result'][0]['course'], 1);
        $this->assertEquals($data['result'][0]['item'], 1);
        $this->assertEquals($data['result'][0]['origin_name'], null);
        $this->assertEquals($data['result'][0]['origin'], null);
        $this->assertEquals($data['result'][0]['nationality_name'], null);
        $this->assertEquals($data['result'][0]['nationality'], null);
        $this->assertEquals($data['result'][0]['gender'], null);
        $this->assertEquals($data['result'][0]['dimension'], 1);
        $this->assertEquals($data['result'][0]['component'], 2);
        $this->assertEquals($data['result'][0]['scale'], 3);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['peer_id'], 6);
        $this->assertEquals($data['result'][0]['type'], "PEER");
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testDimension()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('dimension.getEqCq', array('school' => 2));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 3);
        $this->assertEquals($data['result'][0]['scale'], 3.000000000000);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['name'], "CQ");
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    public function testComponent()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('component.getEqCq', array('school' => 2));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['stats']) , 1);
        $this->assertEquals(count($data['result']['stats'][0]) , 4);
        $this->assertEquals($data['result']['stats'][0]['average'] , 60.00000000);
        $this->assertEquals($data['result']['stats'][0]['id'] , 2);
        $this->assertEquals($data['result']['stats'][0]['dimension'] , 1);
        $this->assertEquals($data['result']['stats'][0]['label'] , "Multicultural sensitivity");
        $this->assertEquals(count($data['result']['description']) , 7);
        $this->assertEquals($data['result']['description']['avgage'] , null);
        $this->assertEquals($data['result']['description']['maxage'] , null);
        $this->assertEquals($data['result']['description']['minage'] , null);
        $this->assertEquals($data['result']['description']['total'] , 1);
        $this->assertEquals(count($data['result']['description']['genre']) , 0);
        $this->assertEquals(count($data['result']['description']['nationality']) , 0);
        $this->assertEquals(count($data['result']['description']['origin']) , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testSchoolEqCq()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('component.getListEqCq', array('schools' => [1]));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][1]) , 2);
        $this->assertEquals(count($data['result'][1]['eqcq']) , 2);
        $this->assertEquals(count($data['result'][1]['eqcq']['stats']) , 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']) , 7);
        $this->assertEquals($data['result'][1]['eqcq']['description']['avgage'] , null);
        $this->assertEquals($data['result'][1]['eqcq']['description']['maxage'] , null);
        $this->assertEquals($data['result'][1]['eqcq']['description']['minage'] , null);
        $this->assertEquals($data['result'][1]['eqcq']['description']['total'] , 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']['genre']) , 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']['nationality']) , 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']['origin']) , 0);
        $this->assertEquals(count($data['result'][1]['nbr']) , 4);
        $this->assertEquals(count($data['result'][1]['nbr'][0]) , 3);
        $this->assertEquals($data['result'][1]['nbr'][0]['role_id'] , 1);
        $this->assertEquals($data['result'][1]['nbr'][0]['nb_user'] , 1);
        $this->assertEquals($data['result'][1]['nbr'][0]['school_id'] , 1);
        $this->assertEquals(count($data['result'][1]['nbr'][1]) , 3);
        $this->assertEquals($data['result'][1]['nbr'][1]['role_id'] , 3);
        $this->assertEquals($data['result'][1]['nbr'][1]['nb_user'] , 1);
        $this->assertEquals($data['result'][1]['nbr'][1]['school_id'] , 1);
        $this->assertEquals(count($data['result'][1]['nbr'][2]) , 3);
        $this->assertEquals($data['result'][1]['nbr'][2]['role_id'] , 4);
        $this->assertEquals($data['result'][1]['nbr'][2]['nb_user'] , 2);
        $this->assertEquals($data['result'][1]['nbr'][2]['school_id'] , 1);
        $this->assertEquals(count($data['result'][1]['nbr'][3]) , 3);
        $this->assertEquals($data['result'][1]['nbr'][3]['role_id'] , 5);
        $this->assertEquals($data['result'][1]['nbr'][3]['nb_user'] , 1);
        $this->assertEquals($data['result'][1]['nbr'][3]['school_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testSchoolEqCq2()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('component.getListEqCq', array('schools' => [2]));
    
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 1); 
        $this->assertEquals(count($data['result'][2]) , 2); 
        $this->assertEquals(count($data['result'][2]['eqcq']) , 2); 
        $this->assertEquals(count($data['result'][2]['eqcq']['stats']) , 1); 
        $this->assertEquals(count($data['result'][2]['eqcq']['stats'][0]) , 4); 
        $this->assertEquals($data['result'][2]['eqcq']['stats'][0]['average'] , 60.00000000); 
        $this->assertEquals($data['result'][2]['eqcq']['stats'][0]['id'] , 2); 
        $this->assertEquals($data['result'][2]['eqcq']['stats'][0]['dimension'] , 1); 
        $this->assertEquals($data['result'][2]['eqcq']['stats'][0]['label'] , "Multicultural sensitivity"); 
        $this->assertEquals(count($data['result'][2]['eqcq']['description']) , 7); 
        $this->assertEquals($data['result'][2]['eqcq']['description']['avgage'] , null); 
        $this->assertEquals($data['result'][2]['eqcq']['description']['maxage'] , null); 
        $this->assertEquals($data['result'][2]['eqcq']['description']['minage'] , null); 
        $this->assertEquals($data['result'][2]['eqcq']['description']['total'] , 1); 
        $this->assertEquals(count($data['result'][2]['eqcq']['description']['genre']) , 0); 
        $this->assertEquals(count($data['result'][2]['eqcq']['description']['nationality']) , 0); 
        $this->assertEquals(count($data['result'][2]['eqcq']['description']['origin']) , 0); 
        $this->assertEquals(count($data['result'][2]['nbr']) , 1); 
        $this->assertEquals(count($data['result'][2]['nbr'][0]) , 3); 
        $this->assertEquals($data['result'][2]['nbr'][0]['role_id'] , 2); 
        $this->assertEquals($data['result'][2]['nbr'][0]['nb_user'] , 1); 
        $this->assertEquals($data['result'][2]['nbr'][0]['school_id'] , 2); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
    }
        
    public function testCanGetValidTransfertVideo()
    {
        system('phing -q test-videoconf');

        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.validTransfertVideo', array(
            'videoconf_archive' => 1,
            'url' => 'urlvideo'
        ));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanGetValidTransfertVideo
     */
    public function testCanGetValidTransfertVideoTwo()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.validTransfertVideo', array('videoconf_archive' => 2,'url' => 'urlvideo'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddItemsProgUSer
     */
    public function testCanGetListRecord()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('course.getListRecord', array('is_student' => true));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 8);
        $this->assertEquals(count($data['result'][0]['program']) , 2);
        $this->assertEquals($data['result'][0]['program']['id'] , 1);
        $this->assertEquals($data['result'][0]['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result'][0]['school']) , 3);
        $this->assertEquals($data['result'][0]['school']['id'] , 2);
        $this->assertEquals($data['result'][0]['school']['name'] , "université de monaco");
        $this->assertEquals($data['result'][0]['school']['logo'] , "token");
        $this->assertEquals(count($data['result'][0]['items']) , 1);
        $this->assertEquals(count($data['result'][0]['items'][0]) , 4);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog']) , 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]) , 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives']) , 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]) , 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['archive_token'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['archive_link'] , "urlvideo");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['archive_duration'] , null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]) , 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['id'] , 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['archive_token'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['archive_link'] , "urlvideo");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['archive_duration'] , null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users']) , 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users'][0]) , 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['item_prog_user']) , 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['item_prog_user']['started_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['item_prog_user']['finished_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['id'] , 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users'][1]) , 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['item_prog_user']) , 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['item_prog_user']['started_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['item_prog_user']['finished_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['id'] , 6);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['firstname'] , "Guillaume");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['lastname'] , "Masmejean");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['avatar'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['items'][0]['item_prog'][0]['start_date']) , true);
        $this->assertEquals(!empty($data['result'][0]['items'][0]['item_prog'][0]['due_date']) , true);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]) , 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives']) , 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][0]) , 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][0]['archive_token'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][0]['archive_link'] , "urlvideo");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][0]['archive_duration'] , null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][1]) , 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][1]['id'] , 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][1]['archive_token'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][1]['archive_link'] , "urlvideo");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['videoconf_archives'][1]['archive_duration'] , null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['users']) , 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['users'][0]) , 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['item_prog_user']) , 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['item_prog_user']['started_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['item_prog_user']['finished_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['id'] , 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][0]['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['users'][1]) , 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['item_prog_user']) , 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['item_prog_user']['started_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['item_prog_user']['finished_date'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['id'] , 6);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['firstname'] , "Guillaume");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['lastname'] , "Masmejean");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['users'][1]['avatar'] , null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][1]['id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['items'][0]['item_prog'][1]['start_date']) , true);
        $this->assertEquals(!empty($data['result'][0]['items'][0]['item_prog'][1]['due_date']) , true);
        $this->assertEquals($data['result'][0]['items'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['items'][0]['title'] , "titl2e");
        $this->assertEquals($data['result'][0]['items'][0]['type'] , "WG");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['title'] , "IMERIR");
        $this->assertEquals($data['result'][0]['abstract'] , "un_token");
        $this->assertEquals($data['result'][0]['description'] , "description");
        $this->assertEquals($data['result'][0]['picture'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanGetByItemProg($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('videoconf.getByItemProg', array('item_prog' => $item_prog));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 14);
        $this->assertEquals(count($data['result']['videoconf_archives']), 2);
        $this->assertEquals(count($data['result']['videoconf_archives'][0]), 4);
        $this->assertEquals($data['result']['videoconf_archives'][0]['id'], 1);
        $this->assertEquals($data['result']['videoconf_archives'][0]['archive_token'], null);
        $this->assertEquals($data['result']['videoconf_archives'][0]['archive_link'], "urlvideo");
        $this->assertEquals($data['result']['videoconf_archives'][0]['archive_duration'], null);
        $this->assertEquals(count($data['result']['videoconf_archives'][1]), 4);
        $this->assertEquals($data['result']['videoconf_archives'][1]['id'], 2);
        $this->assertEquals($data['result']['videoconf_archives'][1]['archive_token'], null);
        $this->assertEquals($data['result']['videoconf_archives'][1]['archive_link'], "urlvideo");
        $this->assertEquals($data['result']['videoconf_archives'][1]['archive_duration'], null);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals(! empty($data['result']['token']), true);
        $this->assertEquals(! empty($data['result']['start_date']), true);
        $this->assertEquals($data['result']['duration'], null);
        $this->assertEquals($data['result']['archive_token'], null);
        $this->assertEquals($data['result']['archive_link'], null);
        $this->assertEquals($data['result']['archive_status'], null);
        $this->assertEquals($data['result']['conversation_id'], 1);
        $this->assertEquals($data['result']['item_prog_id'], 1);
        $this->assertEquals($data['result']['title'], null);
        $this->assertEquals($data['result']['description'], null);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['id'];
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanAddDocVideoconf($vc)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('videoconfdoc.add', array('name' => 'nomdufichier','token' => '1234567890','videoconf' => $vc));
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanGetListByVideoconf($vc)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('videoconfdoc.getListByVideoconf', array('videoconf' => $vc));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 5);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['videoconf_id'], 1);
        $this->assertEquals($data['result'][0]['token'], 1234567890);
        $this->assertEquals($data['result'][0]['name'], "nomdufichier");
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanGetByItemProg
     */
    public function testCanSendMessageConversation()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('message.sendVideoConf', array('text' => 'Salut les copains','conversation' => 1));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 8);
        $this->assertEquals(count($data['result']['message']), 10);
        $this->assertEquals(count($data['result']['message']['from']), 1);
        $this->assertEquals(count($data['result']['message']['from'][0]), 13);
        $this->assertEquals($data['result']['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['from'][0]['id'], 4);
        $this->assertEquals($data['result']['message']['from'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['message']['from'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['message']['from'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['message']['from'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['from'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['from'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['message']['document']), 0);
        $this->assertEquals(count($data['result']['message']['to']), 1);
        $this->assertEquals(count($data['result']['message']['to'][0]), 13);
        $this->assertEquals($data['result']['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['to'][0]['id'], 6);
        $this->assertEquals($data['result']['message']['to'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['message']['to'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['message']['to'][0]['email'], "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['to'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['to'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['message']['id'], 1);
        $this->assertEquals($data['result']['message']['title'], null);
        $this->assertEquals($data['result']['message']['text'], "Salut les copains");
        $this->assertEquals($data['result']['message']['token'], null);
        $this->assertEquals($data['result']['message']['is_draft'], 0);
        $this->assertEquals($data['result']['message']['type'], 3);
        $this->assertEquals(! empty($data['result']['message']['created_date']), true);
        $this->assertEquals(count($data['result']['user']), 4);
        $this->assertEquals($data['result']['user']['id'], 4);
        $this->assertEquals($data['result']['user']['firstname'], "Salim");
        $this->assertEquals($data['result']['user']['lastname'], "Bendacha");
        $this->assertEquals($data['result']['user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['conversation_id'], 1);
        $this->assertEquals($data['result']['from_id'], 4);
        $this->assertEquals($data['result']['user_id'], 4);
        $this->assertEquals(! empty($data['result']['read_date']), true);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanGetByItemProg
     */
    public function testCanAddConversationInVideoconf($videoconf)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.addConversation', array('videoconf' => $videoconf,'users' => array(6),'text' => 'super text qui tue'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 8);
        $this->assertEquals(count($data['result']['message']), 10);
        $this->assertEquals(count($data['result']['message']['from']), 1);
        $this->assertEquals(count($data['result']['message']['from'][0]), 13);
        $this->assertEquals($data['result']['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['from'][0]['id'], 4);
        $this->assertEquals($data['result']['message']['from'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['message']['from'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['message']['from'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['message']['from'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['from'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['from'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['message']['document']), 0);
        $this->assertEquals(count($data['result']['message']['to']), 1);
        $this->assertEquals(count($data['result']['message']['to'][0]), 13);
        $this->assertEquals($data['result']['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['to'][0]['id'], 6);
        $this->assertEquals($data['result']['message']['to'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['message']['to'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['message']['to'][0]['email'], "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['to'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['to'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['message']['id'], 2);
        $this->assertEquals($data['result']['message']['title'], null);
        $this->assertEquals($data['result']['message']['text'], "super text qui tue");
        $this->assertEquals($data['result']['message']['token'], null);
        $this->assertEquals($data['result']['message']['is_draft'], 0);
        $this->assertEquals($data['result']['message']['type'], 3);
        $this->assertEquals(! empty($data['result']['message']['created_date']), true);
        $this->assertEquals(count($data['result']['user']), 4);
        $this->assertEquals($data['result']['user']['id'], 4);
        $this->assertEquals($data['result']['user']['firstname'], "Salim");
        $this->assertEquals($data['result']['user']['lastname'], "Bendacha");
        $this->assertEquals($data['result']['user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 3);
        $this->assertEquals($data['result']['conversation_id'], 2);
        $this->assertEquals($data['result']['from_id'], 4);
        $this->assertEquals($data['result']['user_id'], 4);
        $this->assertEquals(! empty($data['result']['read_date']), true);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['conversation_id'];
    }

    /**
     * @depends testCanAddConversationInVideoconf
     */
    public function testCanGetConversation($conv)
    {
        $this->setIdentity(6);
        $data = $this->jsonRpc('conversation.getConversation', array('conversation' => $conv));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals(count($data['result']['users']), 2);
        $this->assertEquals(count($data['result']['users'][4]), 4);
        $this->assertEquals($data['result']['users'][4]['id'], 4);
        $this->assertEquals($data['result']['users'][4]['firstname'], "Salim");
        $this->assertEquals($data['result']['users'][4]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['users'][4]['avatar'], null);
        $this->assertEquals(count($data['result']['users'][6]), 4);
        $this->assertEquals($data['result']['users'][6]['id'], 6);
        $this->assertEquals($data['result']['users'][6]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['users'][6]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['users'][6]['avatar'], null);
        $this->assertEquals(count($data['result']['messages']), 2);
        $this->assertEquals(count($data['result']['messages']['list']), 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]), 8);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]), 13);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['id'], 4);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]), 13);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['id'], 6);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['email'], "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['id'], 2);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['text'], "super text qui tue");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['type'], 3);
        $this->assertEquals(! empty($data['result']['messages']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['messages']['list'][0]['user']), 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['id'], 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['firstname'], "Salim");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['lastname'], "Bendacha");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['id'], 4);
        $this->assertEquals($data['result']['messages']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['messages']['list'][0]['from_id'], 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user_id'], 6);
        $this->assertEquals($data['result']['messages']['list'][0]['read_date'], null);
        $this->assertEquals(! empty($data['result']['messages']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['messages']['count'], 1);
        $this->assertEquals($data['result']['id'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanGetByItemProg
     */
    public function testCanJoinUser($videoconf)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.joinUser', array('id' => $videoconf));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 18);
        $this->assertEquals($data['result']['item_assignment_id'], 1);
        $this->assertEquals(count($data['result']['docs']), 1);
        $this->assertEquals(count($data['result']['docs'][0]), 5);
        $this->assertEquals($data['result']['docs'][0]['id'], 1);
        $this->assertEquals($data['result']['docs'][0]['videoconf_id'], 1);
        $this->assertEquals($data['result']['docs'][0]['token'], 1234567890);
        $this->assertEquals($data['result']['docs'][0]['name'], "nomdufichier");
        $this->assertEquals(! empty($data['result']['docs'][0]['created_date']), true);
        $this->assertEquals(count($data['result']['users']), 2);
        $this->assertEquals(count($data['result']['users'][4]), 5);
        $this->assertEquals(count($data['result']['users'][4]['item_prog_user']), 2);
        $this->assertEquals($data['result']['users'][4]['item_prog_user']['started_date'], null);
        $this->assertEquals($data['result']['users'][4]['item_prog_user']['finished_date'], null);
        $this->assertEquals($data['result']['users'][4]['id'], 4);
        $this->assertEquals($data['result']['users'][4]['firstname'], "Salim");
        $this->assertEquals($data['result']['users'][4]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['users'][4]['avatar'], null);
        $this->assertEquals(count($data['result']['users'][6]), 5);
        $this->assertEquals(count($data['result']['users'][6]['item_prog_user']), 2);
        $this->assertEquals($data['result']['users'][6]['item_prog_user']['started_date'], null);
        $this->assertEquals($data['result']['users'][6]['item_prog_user']['finished_date'], null);
        $this->assertEquals($data['result']['users'][6]['id'], 6);
        $this->assertEquals($data['result']['users'][6]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['users'][6]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['users'][6]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations']), 2);
        $this->assertEquals(count($data['result']['conversations'][1]), 3);
        $this->assertEquals(count($data['result']['conversations'][1]['users']), 2);
        $this->assertEquals(count($data['result']['conversations'][1]['users'][4]), 4);
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['id'], 4);
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['firstname'], "Salim");
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][1]['users'][6]), 4);
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['id'], 6);
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']), 2);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list']), 1);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]), 8);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]), 13);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['id'], 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]), 13);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['id'], 6);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['email'], "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['id'], 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['text'], "Salut les copains");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['type'], 3);
        $this->assertEquals(! empty($data['result']['conversations'][1]['messages']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['user']), 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['id'], 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['firstname'], "Salim");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['lastname'], "Bendacha");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['conversation_id'], 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['from_id'], 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user_id'], 4);
        $this->assertEquals(! empty($data['result']['conversations'][1]['messages']['list'][0]['read_date']), true);
        $this->assertEquals(! empty($data['result']['conversations'][1]['messages']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['conversations'][1]['messages']['count'], 1);
        $this->assertEquals($data['result']['conversations'][1]['id'], 1);
        $this->assertEquals(count($data['result']['conversations'][2]), 3);
        $this->assertEquals(count($data['result']['conversations'][2]['users']), 2);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][4]), 4);
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['id'], 4);
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['firstname'], "Salim");
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][6]), 4);
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['id'], 6);
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']), 2);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list']), 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]), 8);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]), 13);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['id'], 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]), 13);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['id'], 6);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['email'], "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['roles'][0], "student");
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['id'], 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['text'], "super text qui tue");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['type'], 3);
        $this->assertEquals(! empty($data['result']['conversations'][2]['messages']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['user']), 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['id'], 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['firstname'], "Salim");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['lastname'], "Bendacha");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['id'], 3);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['from_id'], 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user_id'], 4);
        $this->assertEquals(! empty($data['result']['conversations'][2]['messages']['list'][0]['read_date']), true);
        $this->assertEquals(! empty($data['result']['conversations'][2]['messages']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['conversations'][2]['messages']['count'], 1);
        $this->assertEquals($data['result']['conversations'][2]['id'], 2);
        $this->assertEquals(count($data['result']['videoconf_admin']), 5);
        $this->assertEquals($data['result']['videoconf_admin']['id'], 1);
        $this->assertEquals($data['result']['videoconf_admin']['videoconf_id'], 1);
        $this->assertEquals($data['result']['videoconf_admin']['user_id'], 4);
        $this->assertEquals(! empty($data['result']['videoconf_admin']['token']), true);
        $this->assertEquals(! empty($data['result']['videoconf_admin']['created_date']), true);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals(! empty($data['result']['token']), true);
        $this->assertEquals(! empty($data['result']['start_date']), true);
        $this->assertEquals($data['result']['duration'], null);
        $this->assertEquals($data['result']['archive_token'], null);
        $this->assertEquals($data['result']['archive_link'], null);
        $this->assertEquals($data['result']['archive_status'], null);
        $this->assertEquals($data['result']['conversation_id'], 1);
        $this->assertEquals($data['result']['item_prog_id'], 1);
        $this->assertEquals($data['result']['title'], null);
        $this->assertEquals($data['result']['description'], null);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddItemsProgUSer
     */
    public function testCanItemProgForCalendar($item_prog_user)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('itemprog.getList', array('start' => '2013-06-01 00:00','end' => '2018-07-01 00:00'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 7);
        $this->assertEquals(count($data['result'][0]['item_prog_user']), 5);
        $this->assertEquals($data['result'][0]['item_prog_user']['id'], 3);
        $this->assertEquals($data['result'][0]['item_prog_user']['user_id'], 4);
        $this->assertEquals($data['result'][0]['item_prog_user']['item_prog_id'], 1);
        $this->assertEquals($data['result'][0]['item_prog_user']['started_date'], null);
        $this->assertEquals($data['result'][0]['item_prog_user']['finished_date'], null);
        $this->assertEquals(count($data['result'][0]['users']), 2);
        $this->assertEquals(count($data['result'][0]['users'][0]), 5);
        $this->assertEquals(count($data['result'][0]['users'][0]['item_prog_user']), 2);
        $this->assertEquals($data['result'][0]['users'][0]['item_prog_user']['started_date'], null);
        $this->assertEquals($data['result'][0]['users'][0]['item_prog_user']['finished_date'], null);
        $this->assertEquals($data['result'][0]['users'][0]['id'], 4);
        $this->assertEquals($data['result'][0]['users'][0]['firstname'], "Salim");
        $this->assertEquals($data['result'][0]['users'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result'][0]['users'][0]['avatar'], null);
        $this->assertEquals(count($data['result'][0]['users'][1]), 5);
        $this->assertEquals(count($data['result'][0]['users'][1]['item_prog_user']), 2);
        $this->assertEquals($data['result'][0]['users'][1]['item_prog_user']['started_date'], null);
        $this->assertEquals($data['result'][0]['users'][1]['item_prog_user']['finished_date'], null);
        $this->assertEquals($data['result'][0]['users'][1]['id'], 6);
        $this->assertEquals($data['result'][0]['users'][1]['firstname'], "Guillaume");
        $this->assertEquals($data['result'][0]['users'][1]['lastname'], "Masmejean");
        $this->assertEquals($data['result'][0]['users'][1]['avatar'], null);
        $this->assertEquals(count($data['result'][0]['item']), 5);
        $this->assertEquals(count($data['result'][0]['item']['program']), 2);
        $this->assertEquals($data['result'][0]['item']['program']['id'], 1);
        $this->assertEquals($data['result'][0]['item']['program']['name'], "program name upd");
        $this->assertEquals(count($data['result'][0]['item']['course']), 4);
        $this->assertEquals(count($data['result'][0]['item']['course']['grading_policy']), 2);
        $this->assertEquals($data['result'][0]['item']['course']['grading_policy']['name'], "Live class");
        $this->assertEquals($data['result'][0]['item']['course']['grading_policy']['type'], "LC");
        $this->assertEquals($data['result'][0]['item']['course']['id'], 1);
        $this->assertEquals($data['result'][0]['item']['course']['picture'], null);
        $this->assertEquals($data['result'][0]['item']['course']['title'], "IMERIR");
        $this->assertEquals($data['result'][0]['item']['id'], 1);
        $this->assertEquals($data['result'][0]['item']['title'], "titl2e");
        $this->assertEquals($data['result'][0]['item']['type'], "WG");
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['item_id'], 1);
        $this->assertEquals(! empty($data['result'][0]['start_date']), true);
        $this->assertEquals(! empty($data['result'][0]['due_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testCanAddTask()
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('task.add', array('title' => 'TEST','start' => '2015-06-01 12:00','end' => '2015-06-01 13:30','task_share' => array(1,2)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddTask
     */
    public function testCanUpdateTask($task)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('task.update', array('id' => $task,'title' => 'TEST 2','start' => '2015-06-02 12:00','end' => '2015-06-02 13:30','task_share' => array(1,2,3,4)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanNbrView()
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('materialdocument.nbrView', array('school' => 1));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result']['d']) , 2);
        $this->assertEquals($data['result']['d']['total'] , 0);
        $this->assertEquals($data['result']['d']['view'] , 0);
        $this->assertEquals(count($data['result']['w']) , 2);
        $this->assertEquals($data['result']['w']['total'] , 0);
        $this->assertEquals($data['result']['w']['view'] , 0);
        $this->assertEquals(count($data['result']['m']) , 2);
        $this->assertEquals($data['result']['m']['total'] , 0);
        $this->assertEquals($data['result']['m']['view'] , 0);
        $this->assertEquals(count($data['result']['a']) , 2);
        $this->assertEquals($data['result']['a']['total'] , 0);
        $this->assertEquals($data['result']['a']['view'] , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanUpdateTask
     */
    public function testCanGetTask()
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('task.getList', array('start' => '2015-06-01 00:00','end' => '2015-07-01 00:00'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 8);
        $this->assertEquals($data['result'][0]['editable'], 1);
        $this->assertEquals(count($data['result'][0]['user']), 3);
        $this->assertEquals($data['result'][0]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result'][0]['user']['lastname'], "Robert");
        $this->assertEquals($data['result'][0]['user']['avatar'], null);
        $this->assertEquals(count($data['result'][0]['task_share']), 4);
        $this->assertEquals(count($data['result'][0]['task_share'][0]), 2);
        $this->assertEquals($data['result'][0]['task_share'][0]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][0]['user_id'], 1);
        $this->assertEquals(count($data['result'][0]['task_share'][1]), 2);
        $this->assertEquals($data['result'][0]['task_share'][1]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][1]['user_id'], 2);
        $this->assertEquals(count($data['result'][0]['task_share'][2]), 2);
        $this->assertEquals($data['result'][0]['task_share'][2]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][2]['user_id'], 3);
        $this->assertEquals(count($data['result'][0]['task_share'][3]), 2);
        $this->assertEquals($data['result'][0]['task_share'][3]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][3]['user_id'], 4);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['title'], "TEST 2");
        $this->assertEquals($data['result'][0]['content'], null);
        $this->assertEquals(! empty($data['result'][0]['start']), true);
        $this->assertEquals(! empty($data['result'][0]['end']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanVideoconfStart($item_prog)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('itemproguser.start', array('item_prog' => $item_prog));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanVideoconfStart
     */
    public function testCanGetStartedConference()
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('itemproguser.getStartedConference', array());
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 4);
        $this->assertEquals(count($data['result'][0]['questionnaire_user']) , 1);
        $this->assertEquals($data['result'][0]['questionnaire_user']['id'] , 1);
        $this->assertEquals(count($data['result'][0]['questionnaire']) , 2);
        $this->assertEquals($data['result'][0]['questionnaire']['id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['questionnaire']['created_date']) , true);
        $this->assertEquals(count($data['result'][0]['item_prog']) , 4);
        $this->assertEquals(count($data['result'][0]['item_prog']['item']) , 2);
        $this->assertEquals($data['result'][0]['item_prog']['item']['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog']['item']['type'] , "WG");
        $this->assertEquals($data['result'][0]['item_prog']['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog']['item_id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['item_prog']['start_date']) , true);
        $this->assertEquals(!empty($data['result'][0]['started_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanVideoconfEnd($item_prog)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('itemproguser.end', array('item_prog' => $item_prog));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanUpdateTask
     */
    public function testCanGetSharedTask($task)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('task.getList', array('start' => '2015-06-01 00:00','end' => '2015-07-01 00:00'));
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 8);
        $this->assertEquals($data['result'][0]['editable'], 1);
        $this->assertEquals(count($data['result'][0]['user']), 3);
        $this->assertEquals($data['result'][0]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result'][0]['user']['lastname'], "Robert");
        $this->assertEquals($data['result'][0]['user']['avatar'], null);
        $this->assertEquals(count($data['result'][0]['task_share']), 4);
        $this->assertEquals(count($data['result'][0]['task_share'][0]), 2);
        $this->assertEquals($data['result'][0]['task_share'][0]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][0]['user_id'], 1);
        $this->assertEquals(count($data['result'][0]['task_share'][1]), 2);
        $this->assertEquals($data['result'][0]['task_share'][1]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][1]['user_id'], 2);
        $this->assertEquals(count($data['result'][0]['task_share'][2]), 2);
        $this->assertEquals($data['result'][0]['task_share'][2]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][2]['user_id'], 3);
        $this->assertEquals(count($data['result'][0]['task_share'][3]), 2);
        $this->assertEquals($data['result'][0]['task_share'][3]['task_id'], 1);
        $this->assertEquals($data['result'][0]['task_share'][3]['user_id'], 4);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['title'], "TEST 2");
        $this->assertEquals($data['result'][0]['content'], null);
        $this->assertEquals(! empty($data['result'][0]['start']), true);
        $this->assertEquals(! empty($data['result'][0]['end']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testCanItemprogNbStart()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('itemprog.nbStart', array('school' => 2));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result']['d']) , 4);
        $this->assertEquals(count($data['result']['d']['LC']) , 2);
        $this->assertEquals($data['result']['d']['LC']['started'] , null);
        $this->assertEquals($data['result']['d']['LC']['total'] , 0);
        $this->assertEquals(count($data['result']['d']['GW']) , 2);
        $this->assertEquals($data['result']['d']['GW']['started'] , null);
        $this->assertEquals($data['result']['d']['GW']['total'] , 0);
        $this->assertEquals(count($data['result']['d']['CP']) , 2);
        $this->assertEquals($data['result']['d']['CP']['submit'] , null);
        $this->assertEquals($data['result']['d']['CP']['total'] , 0);
        $this->assertEquals(count($data['result']['d']['IA']) , 2);
        $this->assertEquals($data['result']['d']['IA']['submit'] , null);
        $this->assertEquals($data['result']['d']['IA']['total'] , 0);
        $this->assertEquals(count($data['result']['w']) , 4);
        $this->assertEquals(count($data['result']['w']['LC']) , 2);
        $this->assertEquals($data['result']['w']['LC']['started'] , null);
        $this->assertEquals($data['result']['w']['LC']['total'] , 0);
        $this->assertEquals(count($data['result']['w']['GW']) , 2);
        $this->assertEquals($data['result']['w']['GW']['started'] , null);
        $this->assertEquals($data['result']['w']['GW']['total'] , 0);
        $this->assertEquals(count($data['result']['w']['CP']) , 2);
        $this->assertEquals($data['result']['w']['CP']['submit'] , null);
        $this->assertEquals($data['result']['w']['CP']['total'] , 0);
        $this->assertEquals(count($data['result']['w']['IA']) , 2);
        $this->assertEquals($data['result']['w']['IA']['submit'] , null);
        $this->assertEquals($data['result']['w']['IA']['total'] , 0);
        $this->assertEquals(count($data['result']['m']) , 4);
        $this->assertEquals(count($data['result']['m']['LC']) , 2);
        $this->assertEquals($data['result']['m']['LC']['started'] , null);
        $this->assertEquals($data['result']['m']['LC']['total'] , 0);
        $this->assertEquals(count($data['result']['m']['GW']) , 2);
        $this->assertEquals($data['result']['m']['GW']['started'] , null);
        $this->assertEquals($data['result']['m']['GW']['total'] , 0);
        $this->assertEquals(count($data['result']['m']['CP']) , 2);
        $this->assertEquals($data['result']['m']['CP']['submit'] , null);
        $this->assertEquals($data['result']['m']['CP']['total'] , 0);
        $this->assertEquals(count($data['result']['m']['IA']) , 2);
        $this->assertEquals($data['result']['m']['IA']['submit'] , null);
        $this->assertEquals($data['result']['m']['IA']['total'] , 0);
        $this->assertEquals(count($data['result']['a']) , 4);
        $this->assertEquals(count($data['result']['a']['LC']) , 2);
        $this->assertEquals($data['result']['a']['LC']['started'] , null);
        $this->assertEquals($data['result']['a']['LC']['total'] , 0);
        $this->assertEquals(count($data['result']['a']['GW']) , 2);
        $this->assertEquals($data['result']['a']['GW']['started'] , null);
        $this->assertEquals($data['result']['a']['GW']['total'] , 0);
        $this->assertEquals(count($data['result']['a']['CP']) , 2);
        $this->assertEquals($data['result']['a']['CP']['submit'] , null);
        $this->assertEquals($data['result']['a']['CP']['total'] , 0);
        $this->assertEquals(count($data['result']['a']['IA']) , 2);
        $this->assertEquals($data['result']['a']['IA']['submit'] , null);
        $this->assertEquals($data['result']['a']['IA']['total'] , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
   
    /**
     * @depends testCanAddProgrmItem
     * @depends testCanAddUserCourse
     */
    public function testCanAddItemAssigment($item_prog)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('itemassignment.add', array('item_prog' => $item_prog,'response' => 'response','documents' => array(array('type' => 'type','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token','date' => 'date'))));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddItemAssigment
     */
    public function testCanAddItemAssigmentAddDocument($itemassignment)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('itemassignment.addDocument', array('id' => $itemassignment,'document' => array('type' => 'type','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token','date' => 'date')));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddItemAssigmentAddDocument
     */
    public function testCanRemoveDocument($document)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('itemassignment.removeDocument', array('document' => $document));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddItemAssigment
     */
    public function testCanAddCommentItemAssigment($item_assignment)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemassignment.addComment', array('text' => 'text text text','item_assignment' => $item_assignment));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanIsGroupWorkSubmittedFalse($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemassignment.isGroupWorkSubmitted', array('item_prog' => $item_prog));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , false);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddItemAssigment
     */
    public function testCanAddSubmitItemAssigment($item_assignment)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemassignment.submit', array('id' => $item_assignment));
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }
    
    public function testCanAddSubmitItemAssigmentUn()
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemassignment.submit', array('id' => 1));
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    
        return $data['result'];
    }
    

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanGetSubmissionTrue($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('itemassignment.getSubmission', array(
            'item_prog' => $item_prog
        ));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 7);
        $this->assertEquals(count($data['result']['item_prog']) , 4);
        $this->assertEquals(count($data['result']['item_prog']['item']) , 8);
        $this->assertEquals(count($data['result']['item_prog']['item']['materials']) , 1);
        $this->assertEquals(count($data['result']['item_prog']['item']['materials'][0]) , 9);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['id'] , 3);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['course_id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['type'] , "type2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['title'] , "title2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['author'] , "author2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['link'] , "link2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['source'] , "src2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['token'] , "token2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['description'] , null);
        $this->assertEquals(count($data['result']['item_prog']['item']['program']) , 2);
        $this->assertEquals($data['result']['item_prog']['item']['program']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['item_prog']['item']['course']) , 3);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor']) , 1);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor'][0]) , 11);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor'][0]['school']) , 5);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['id'] , 5);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['position'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['interest'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['avatar'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['item_prog']['item']['item_grade']) , 2);
        $this->assertEquals($data['result']['item_prog']['item']['item_grade']['grade'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['item_grade']['created_date'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['title'] , "titl2e");
        $this->assertEquals($data['result']['item_prog']['item']['describe'] , "description2");
        $this->assertEquals($data['result']['item_prog']['item']['type'] , "WG");
        $this->assertEquals($data['result']['item_prog']['id'] , 1);
        $this->assertEquals(!empty($data['result']['item_prog']['start_date']) , true);
        $this->assertEquals(!empty($data['result']['item_prog']['due_date']) , true);
        $this->assertEquals(count($data['result']['students']) , 2);
        $this->assertEquals(count($data['result']['students'][0]) , 6);
        $this->assertEquals(count($data['result']['students'][0]['school']) , 5);
        $this->assertEquals($data['result']['students'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['students'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['students'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['students'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['students'][0]['school']['background'] , null);
        $this->assertEquals(count($data['result']['students'][0]['roles']) , 1);
        $this->assertEquals($data['result']['students'][0]['roles'][0] , "student");
        $this->assertEquals($data['result']['students'][0]['id'] , 6);
        $this->assertEquals($data['result']['students'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['students'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['students'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['students'][1]) , 6);
        $this->assertEquals(count($data['result']['students'][1]['school']) , 5);
        $this->assertEquals($data['result']['students'][1]['school']['id'] , 1);
        $this->assertEquals($data['result']['students'][1]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['students'][1]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['students'][1]['school']['logo'] , null);
        $this->assertEquals($data['result']['students'][1]['school']['background'] , null);
        $this->assertEquals(count($data['result']['students'][1]['roles']) , 1);
        $this->assertEquals($data['result']['students'][1]['roles'][0] , "student");
        $this->assertEquals($data['result']['students'][1]['id'] , 4);
        $this->assertEquals($data['result']['students'][1]['firstname'] , "Salim");
        $this->assertEquals($data['result']['students'][1]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['students'][1]['avatar'] , null);
        $this->assertEquals(count($data['result']['documents']) , 1);
        $this->assertEquals(count($data['result']['documents'][0]) , 8);
        $this->assertEquals($data['result']['documents'][0]['id'] , 1);
        $this->assertEquals($data['result']['documents'][0]['item_assigment_id'] , 2);
        $this->assertEquals($data['result']['documents'][0]['type'] , "type");
        $this->assertEquals($data['result']['documents'][0]['title'] , "title");
        $this->assertEquals($data['result']['documents'][0]['author'] , "author");
        $this->assertEquals($data['result']['documents'][0]['link'] , "link");
        $this->assertEquals($data['result']['documents'][0]['source'] , "source");
        $this->assertEquals($data['result']['documents'][0]['token'] , "token");
        $this->assertEquals(count($data['result']['comments']) , 1);
        $this->assertEquals(count($data['result']['comments'][0]) , 9);
        $this->assertEquals(count($data['result']['comments'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['comments'][0]['user']['roles']) , 1);
        $this->assertEquals(count($data['result']['comments'][0]['user']['roles'][0]) , 2);
        $this->assertEquals($data['result']['comments'][0]['user']['roles'][0]['id'] , 3);
        $this->assertEquals($data['result']['comments'][0]['user']['roles'][0]['name'] , "academic");
        $this->assertEquals($data['result']['comments'][0]['user']['id'] , 3);
        $this->assertEquals($data['result']['comments'][0]['user']['firstname'] , "Christophe");
        $this->assertEquals($data['result']['comments'][0]['user']['lastname'] , "Robert");
        $this->assertEquals($data['result']['comments'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['comments'][0]['id'] , 1);
        $this->assertEquals($data['result']['comments'][0]['text'] , "text text text");
        $this->assertEquals($data['result']['comments'][0]['audio'] , null);
        $this->assertEquals($data['result']['comments'][0]['item_assignment_id'] , 2);
        $this->assertEquals($data['result']['comments'][0]['file'] , null);
        $this->assertEquals($data['result']['comments'][0]['file_name'] , null);
        $this->assertEquals(!empty($data['result']['comments'][0]['created_date']) , true);
        $this->assertEquals($data['result']['comments'][0]['read_date'] , null);
        $this->assertEquals($data['result']['id'] , 2);
        $this->assertEquals($data['result']['response'] , "response");
        $this->assertEquals(!empty($data['result']['submit_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanIsGroupWorkSubmitted($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemassignment.isGroupWorkSubmitted', array('item_prog' => $item_prog));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    
    /**
     * @depends testCanAddProgram
     * @depends testAddCourse
     */
    public function testCangetListGrade($program, $course)
    {
        system('phing -q test-dateitemprog');
        
        $this->setIdentity(4);
        $data = $this->jsonRpc('item.getListGrade', array('program' => $program,'course' => $course,'type' => array("IA","CP","WG","LC"),"new_message" => true,"filter" => array("n" => 10,"p" => 1)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 7);
        $this->assertEquals(count($data['result']['list'][0]['program']), 2);
        $this->assertEquals($data['result']['list'][0]['program']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['list'][0]['course']), 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['item_prog']), 5);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_assignment']), 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_assignment']['id'], 2);
        $this->assertEquals(! empty($data['result']['list'][0]['item_prog']['item_assignment']['submit_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_grade']), 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['letter'], null);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['grade'], null);
        $this->assertEquals($data['result']['list'][0]['item_prog']['id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['item_prog']['start_date']), true);
        $this->assertEquals(! empty($data['result']['list'][0]['item_prog']['due_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['users']), 2);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]), 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['logo'], null);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['roles'][0], "student");
        $this->assertEquals($data['result']['list'][0]['users'][0]['id'], 6);
        $this->assertEquals($data['result']['list'][0]['users'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['list'][0]['users'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['list'][0]['users'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]), 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['logo'], null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['roles'][0], "student");
        $this->assertEquals($data['result']['list'][0]['users'][1]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['firstname'], "Salim");
        $this->assertEquals($data['result']['list'][0]['users'][1]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['list'][0]['users'][1]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['new_message'], 1);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "titl2e");
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddItemAssigment
     */
    public function testCanGraded($item_assignment)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('itemassignment.setGrade', array('item_assignment' => $item_assignment,'score' => 60));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddItemAssigment
     */
    public function testCanGradedTwo($item_assignment)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('itemassignment.setGrade', array('item_assignment' => $item_assignment,'score' => 60));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgram
     */
    public function testCangetListGradeTwo($program)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('item.getListGrade', array('program' => $program));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 7);
        $this->assertEquals(count($data['result']['list'][0]['program']), 2);
        $this->assertEquals($data['result']['list'][0]['program']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['list'][0]['course']), 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['item_prog']), 5);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_assignment']), 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_assignment']['id'], 2);
        $this->assertEquals(! empty($data['result']['list'][0]['item_prog']['item_assignment']['submit_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_grade']), 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['letter'], "D-");
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['grade'], 60);
        $this->assertEquals($data['result']['list'][0]['item_prog']['id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['item_prog']['start_date']), true);
        $this->assertEquals(! empty($data['result']['list'][0]['item_prog']['due_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['users']), 2);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]), 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['logo'], null);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['roles'][0], "student");
        $this->assertEquals($data['result']['list'][0]['users'][0]['id'], 6);
        $this->assertEquals($data['result']['list'][0]['users'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['list'][0]['users'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['list'][0]['users'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]), 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['logo'], null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['roles'][0], "student");
        $this->assertEquals($data['result']['list'][0]['users'][1]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['firstname'], "Salim");
        $this->assertEquals($data['result']['list'][0]['users'][1]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['list'][0]['users'][1]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['new_message'], 1);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "titl2e");
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['list'][0]['item_prog']['id'];
    }

    /**
     * @depends testCangetListGradeTwo
     */
    public function testCanItemGetByItemProg($itemprog)
    {
        $this->setIdentity(4);
    
        $data = $this->jsonRpc('item.getByItemProg', array('item_prog' => $itemprog));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 3);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "titl2e");
        $this->assertEquals($data['result']['type'] , "WG");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
   
    /**
     * @depends testAddCourse
     * @depends testCanGradedTwo
     */
    public function testCanGetListGradeDetail($course)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('item.getListGradeDetail', array('course' => $course,'user' => 4));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 6);
        $this->assertEquals(count($data['result'][0]) , 5);
        $this->assertEquals($data['result'][0]['nbr_comment'] , 0);
        $this->assertEquals(count($data['result'][0]['grading_policy_grade']) , 1);
        $this->assertEquals($data['result'][0]['grading_policy_grade']['grade'] , null);
        $this->assertEquals($data['result'][0]['id'] , 6);
        $this->assertEquals($data['result'][0]['name'] , "Individual assignment");
        $this->assertEquals($data['result'][0]['grade'] , 20);
        $this->assertEquals(count($data['result'][1]) , 5);
        $this->assertEquals($data['result'][1]['nbr_comment'] , 0);
        $this->assertEquals(count($data['result'][1]['grading_policy_grade']) , 1);
        $this->assertEquals($data['result'][1]['grading_policy_grade']['grade'] , null);
        $this->assertEquals($data['result'][1]['id'] , 7);
        $this->assertEquals($data['result'][1]['name'] , "Group work");
        $this->assertEquals($data['result'][1]['grade'] , 20);
        $this->assertEquals(count($data['result'][2]) , 6);
        $this->assertEquals(count($data['result'][2]['items']) , 1);
        $this->assertEquals(count($data['result'][2]['items'][0]) , 6);
        $this->assertEquals(count($data['result'][2]['items'][0]['item_prog']) , 3);
        $this->assertEquals(count($data['result'][2]['items'][0]['item_prog']['item_prog_user']) , 1);
        $this->assertEquals($data['result'][2]['items'][0]['item_prog']['item_prog_user']['user_id'] , 4);
        $this->assertEquals(count($data['result'][2]['items'][0]['item_prog']['item']) , 1);
        $this->assertEquals(count($data['result'][2]['items'][0]['item_prog']['item']['item_assignment']) , 2);
        $this->assertEquals($data['result'][2]['items'][0]['item_prog']['item']['item_assignment']['id'] , 2);
        $this->assertEquals(!empty($data['result'][2]['items'][0]['item_prog']['item']['item_assignment']['submit_date']) , true);
        $this->assertEquals($data['result'][2]['items'][0]['item_prog']['id'] , 1);
        $this->assertEquals(count($data['result'][2]['items'][0]['item_grade']) , 3);
        $this->assertEquals($data['result'][2]['items'][0]['item_grade']['assignment_id'] , 2);
        $this->assertEquals($data['result'][2]['items'][0]['item_grade']['grade'] , 60);
        $this->assertEquals(!empty($data['result'][2]['items'][0]['item_grade']['created_date']) , true);
        $this->assertEquals($data['result'][2]['items'][0]['nbr_comment'] , 1);
        $this->assertEquals($data['result'][2]['items'][0]['id'] , 1);
        $this->assertEquals($data['result'][2]['items'][0]['title'] , "titl2e");
        $this->assertEquals($data['result'][2]['items'][0]['grading_policy_id'] , 8);
        $this->assertEquals($data['result'][2]['nbr_comment'] , 0);
        $this->assertEquals(count($data['result'][2]['grading_policy_grade']) , 1);
        $this->assertEquals($data['result'][2]['grading_policy_grade']['grade'] , 60);
        $this->assertEquals($data['result'][2]['id'] , 8);
        $this->assertEquals($data['result'][2]['name'] , "Live class");
        $this->assertEquals($data['result'][2]['grade'] , 20);
        $this->assertEquals(count($data['result'][3]) , 5);
        $this->assertEquals($data['result'][3]['nbr_comment'] , 0);
        $this->assertEquals(count($data['result'][3]['grading_policy_grade']) , 1);
        $this->assertEquals($data['result'][3]['grading_policy_grade']['grade'] , null);
        $this->assertEquals($data['result'][3]['id'] , 9);
        $this->assertEquals($data['result'][3]['name'] , "Capstone project");
        $this->assertEquals($data['result'][3]['grade'] , 20);
        $this->assertEquals(count($data['result'][4]) , 5);
        $this->assertEquals($data['result'][4]['nbr_comment'] , 0);
        $this->assertEquals(count($data['result'][4]['grading_policy_grade']) , 1);
        $this->assertEquals($data['result'][4]['grading_policy_grade']['grade'] , null);
        $this->assertEquals($data['result'][4]['id'] , 10);
        $this->assertEquals($data['result'][4]['name'] , "Attendance and participation");
        $this->assertEquals($data['result'][4]['grade'] , 20);
        $this->assertEquals(count($data['result'][5]) , 5);
        $this->assertEquals($data['result'][5]['nbr_comment'] , 0);
        $this->assertEquals(count($data['result'][5]['grading_policy_grade']) , 1);
        $this->assertEquals($data['result'][5]['grading_policy_grade']['grade'] , null);
        $this->assertEquals($data['result'][5]['id'] , 11);
        $this->assertEquals($data['result'][5]['name'] , "toto");
        $this->assertEquals($data['result'][5]['grade'] , 60);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testCanGetListDetail()
    {
        $this->setIdentity(5);
        $data = $this->jsonRpc('course.getListDetail', array('user' => 4));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 9);
        $this->assertEquals($data['result'][0]['avg'], 60);
        $this->assertEquals(count($data['result'][0]['program']), 2);
        $this->assertEquals($data['result'][0]['program']['id'], 1);
        $this->assertEquals($data['result'][0]['program']['name'], "program name upd");
        $this->assertEquals(count($data['result'][0]['material_document']), 3);
        $this->assertEquals(count($data['result'][0]['material_document'][1]), 13);
        $this->assertEquals($data['result'][0]['material_document'][1]['id'], 1);
        $this->assertEquals($data['result'][0]['material_document'][1]['description'] , null);
        $this->assertEquals($data['result'][0]['material_document'][1]['course_id'], 1);
        $this->assertEquals($data['result'][0]['material_document'][1]['type'], "link");
        $this->assertEquals($data['result'][0]['material_document'][1]['title'], "title");
        $this->assertEquals($data['result'][0]['material_document'][1]['author'], "author");
        $this->assertEquals($data['result'][0]['material_document'][1]['link'], "link");
        $this->assertEquals($data['result'][0]['material_document'][1]['source'], "source");
        $this->assertEquals($data['result'][0]['material_document'][1]['token'], "token");
        $this->assertEquals($data['result'][0]['material_document'][1]['date'], "2011-01-01");
        $this->assertEquals(! empty($data['result'][0]['material_document'][1]['created_date']), true);
        $this->assertEquals($data['result'][0]['material_document'][1]['deleted_date'], null);
        $this->assertEquals($data['result'][0]['material_document'][1]['updated_date'], null);
        $this->assertEquals(count($data['result'][0]['material_document'][2]), 13);
        $this->assertEquals($data['result'][0]['material_document'][2]['id'], 2);
        $this->assertEquals($data['result'][0]['material_document'][2]['description'] , null);
        $this->assertEquals($data['result'][0]['material_document'][2]['course_id'], 1);
        $this->assertEquals($data['result'][0]['material_document'][2]['type'], "updatetype");
        $this->assertEquals($data['result'][0]['material_document'][2]['title'], "updatetitle");
        $this->assertEquals($data['result'][0]['material_document'][2]['author'], "updateauthor");
        $this->assertEquals($data['result'][0]['material_document'][2]['link'], "updatelink");
        $this->assertEquals($data['result'][0]['material_document'][2]['source'], "updatesrc");
        $this->assertEquals($data['result'][0]['material_document'][2]['token'], "updatetoken");
        $this->assertEquals($data['result'][0]['material_document'][2]['date'], "2015-01-10");
        $this->assertEquals(! empty($data['result'][0]['material_document'][2]['created_date']), true);
        $this->assertEquals($data['result'][0]['material_document'][2]['deleted_date'], null);
        $this->assertEquals(! empty($data['result'][0]['material_document'][2]['updated_date']), true);
        $this->assertEquals(count($data['result'][0]['material_document'][3]), 13);
        $this->assertEquals($data['result'][0]['material_document'][3]['id'], 3);
        $this->assertEquals($data['result'][0]['material_document'][3]['description'] , null);
        $this->assertEquals($data['result'][0]['material_document'][3]['course_id'], 1);
        $this->assertEquals($data['result'][0]['material_document'][3]['type'], "type2");
        $this->assertEquals($data['result'][0]['material_document'][3]['title'], "title2");
        $this->assertEquals($data['result'][0]['material_document'][3]['author'], "author2");
        $this->assertEquals($data['result'][0]['material_document'][3]['link'], "link2");
        $this->assertEquals($data['result'][0]['material_document'][3]['source'], "src2");
        $this->assertEquals($data['result'][0]['material_document'][3]['token'], "token2");
        $this->assertEquals($data['result'][0]['material_document'][3]['date'], "2015-01-02");
        $this->assertEquals(! empty($data['result'][0]['material_document'][3]['created_date']), true);
        $this->assertEquals($data['result'][0]['material_document'][3]['deleted_date'], null);
        $this->assertEquals($data['result'][0]['material_document'][3]['updated_date'], null);
        $this->assertEquals(count($data['result'][0]['item_prog']), 1);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]), 5);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']), 6);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['materials']), 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['materials'][0], 3);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['item_assignment']), 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['item_assignment']['id'], 2);
        $this->assertEquals(! empty($data['result'][0]['item_prog'][0]['item']['item_assignment']['submit_date']), true);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['item_grade']), 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['item_grade']['id'], 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['item_grade']['grade'], 60);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['id'], 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['title'], "titl2e");
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['type'], "WG");
        $this->assertEquals($data['result'][0]['item_prog'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_id'], 1);
        $this->assertEquals(! empty($data['result'][0]['item_prog'][0]['start_date']), true);
        $this->assertEquals(! empty($data['result'][0]['item_prog'][0]['due_date']), true);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['title'], "IMERIR");
        $this->assertEquals($data['result'][0]['abstract'], "un_token");
        $this->assertEquals($data['result'][0]['description'], "description");
        $this->assertEquals($data['result'][0]['picture'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    // FAQ
    /**
     * @depends testAddCourse
     */
    public function testAddFaqAsk($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('faq.add', array('ask' => 'une question','answer' => 'une reponse','course' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddFaqAsk
     */
    public function testUpdateFaqAsk($faq)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('faq.update', array('id' => $faq,'ask' => 'une question update','answer' => 'une reponse update'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetList($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('faq.getList', array('course' => $course));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 5);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['ask'], "une question update");
        $this->assertEquals($data['result'][0]['answer'], "une reponse update");
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddFaqAsk
     */
    public function testDelete($faq)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('faq.delete', array('id' => $faq));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgram
     */
    public function testCanAddUserToProgram($program)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.addProgram', array('user' => 1,'program' => $program));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][1]), 1);
        $this->assertEquals($data['result'][1][1], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testProgramGetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('program.getList', array());

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 8);
        $this->assertEquals($data['result']['list'][0]['student'], 0);
        $this->assertEquals($data['result']['list'][0]['instructor'], 0);
        $this->assertEquals($data['result']['list'][0]['course'], 1);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['name'], "program name upd");
        $this->assertEquals($data['result']['list'][0]['level'], "mba");
        $this->assertEquals($data['result']['list'][0]['sis'], "sisupd");
        $this->assertEquals($data['result']['list'][0]['year'], null);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testUserGetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.getList', array('type' => array(),'search' => 'Bo'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 13);
        $this->assertEquals($data['result']['list'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['school']['background'], 'background');
        $this->assertEquals($data['result']['list'][0]['school']['id'], 2);
        $this->assertEquals($data['result']['list'][0]['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['list'][0]['school']['short_name'], "IUM");
        $this->assertEquals($data['result']['list'][0]['school']['logo'], "token");
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['program']), 1);
        $this->assertEquals(count($data['result']['list'][0]['program'][0]), 5);
        $this->assertEquals($data['result']['list'][0]['program'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['program'][0]['name'], "program name upd");
        $this->assertEquals($data['result']['list'][0]['program'][0]['level'], "mba");
        $this->assertEquals($data['result']['list'][0]['program'][0]['sis'], "sisupd");
        $this->assertEquals($data['result']['list'][0]['program'][0]['year'], null);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgram
     * @depends testCanAddUserCourse
     */
    public function testCanGetCoursesListByUser($program)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.getList', array('filter' => ['user' => 1]));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 18);
        $this->assertEquals(count($data['result']['list'][0]['instructor']), 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]), 11);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['material_document']), 3);
        $this->assertEquals(count($data['result']['list'][0]['material_document'][0]), 13);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['description'] , null);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['course_id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['type'], "link");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['title'], "title");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['author'], "author");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['link'], "link");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['source'], "source");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['token'], "token");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['date'], "2011-01-01");
        $this->assertEquals(! empty($data['result']['list'][0]['material_document'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['updated_date'], null);
        $this->assertEquals(count($data['result']['list'][0]['material_document'][1]), 13);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['description'] , null);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['course_id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['type'], "updatetype");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['title'], "updatetitle");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['author'], "updateauthor");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['link'], "updatelink");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['source'], "updatesrc");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['token'], "updatetoken");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['date'], "2015-01-10");
        $this->assertEquals(! empty($data['result']['list'][0]['material_document'][1]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['deleted_date'], null);
        $this->assertEquals(! empty($data['result']['list'][0]['material_document'][1]['updated_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['material_document'][2]), 13);
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['description'] , null);
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['course_id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['type'], "type2");
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['title'], "title2");
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['author'], "author2");
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['link'], "link2");
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['source'], "src2");
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['token'], "token2");
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['date'], "2015-01-02");
        $this->assertEquals(! empty($data['result']['list'][0]['material_document'][2]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][0]['material_document'][2]['updated_date'], null);
        $this->assertEquals(! empty($data['result']['list'][0]['start_date']), true);
        $this->assertEquals(! empty($data['result']['list'][0]['end_date']), true);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "IMERIR");
        $this->assertEquals($data['result']['list'][0]['abstract'], "un_token");
        $this->assertEquals($data['result']['list'][0]['description'], "description");
        $this->assertEquals($data['result']['list'][0]['objectives'], "objectives");
        $this->assertEquals($data['result']['list'][0]['teaching'], "teaching");
        $this->assertEquals($data['result']['list'][0]['attendance'], "attendance");
        $this->assertEquals($data['result']['list'][0]['duration'], 18);
        $this->assertEquals($data['result']['list'][0]['notes'], "notes");
        $this->assertEquals($data['result']['list'][0]['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['list'][0]['picture'], null);
        $this->assertEquals($data['result']['list'][0]['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['list'][0]['video_token'], "video_token");
        $this->assertEquals($data['result']['list'][0]['program_id'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    

    /**
     * @depends testAddThread
     */
    public function testAddThreadMessage($thread)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('threadmessage.add', array('message' => 'un super message','thread' => $thread));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddThreadMessage
     */
    public function testUpdateThreadMessage($threadmessage)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('threadmessage.update', array('message' => 'un super message update','id' => $threadmessage));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testCanConnectionGetAvg()
    {
        $this->setIdentity(2);
    
        $data = $this->jsonRpc('connection.getAvg', array('school' => 1));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result']['d']) , 2);
        $this->assertEquals($data['result']['d']['nbr_session'] , null);
        $this->assertEquals($data['result']['d']['avg'] , null);
        $this->assertEquals(count($data['result']['w']) , 2);
        $this->assertEquals($data['result']['w']['nbr_session'] , null);
        $this->assertEquals($data['result']['w']['avg'] , null);
        $this->assertEquals(count($data['result']['m']) , 2);
        $this->assertEquals($data['result']['m']['nbr_session'] , null);
        $this->assertEquals($data['result']['m']['avg'] , null);
        $this->assertEquals(count($data['result']['a']) , 2);
        $this->assertEquals($data['result']['a']['nbr_session'] , null);
        $this->assertEquals($data['result']['a']['avg'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddThread
     */
    public function testGetListThreadMessage($thread)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('threadmessage.getList', array('thread' => $thread));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 2);
        $this->assertEquals(count($data['result']['list']), 2);
        $this->assertEquals(count($data['result']['list'][0]), 5);
        $this->assertEquals(count($data['result']['list'][0]['thread']), 2);
        $this->assertEquals($data['result']['list'][0]['thread']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['thread']['course_id'], 1);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0], "admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message'], "super messge");
        $this->assertEquals(! empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]), 5);
        $this->assertEquals(count($data['result']['list'][1]['thread']), 2);
        $this->assertEquals($data['result']['list'][1]['thread']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['thread']['course_id'], 1);
        $this->assertEquals(count($data['result']['list'][1]['user']), 5);
        $this->assertEquals(count($data['result']['list'][1]['user']['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['user']['roles'][0], "super_admin");
        $this->assertEquals($data['result']['list'][1]['user']['id'], 2);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][1]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['message'], "un super message update");
        $this->assertEquals(! empty($data['result']['list'][1]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetThreadTwo($course)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.getList', array('course' => $course));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 8);
        $this->assertEquals(count($data['result']['list'][0]['course']), 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['message']), 4);
        $this->assertEquals(count($data['result']['list'][0]['message']['user']), 4);
        $this->assertEquals($data['result']['list'][0]['message']['user']['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['message'], "un super message update");
        $this->assertEquals(! empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['nb_message'], 2);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0], "admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "un titre update");
        $this->assertEquals(! empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['list'][0]['id'];
    }

    public function testGetNbrMessageThread()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.getNbrMessage', array('school' => 2, 'day' => 30));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals($data['result']['d'] , 2);
        $this->assertEquals($data['result']['w'] , 2);
        $this->assertEquals($data['result']['m'] , 2);
        $this->assertEquals($data['result']['a'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testGetThreadTwo
     */
    public function testGetThreadOnlyOne($thread)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.get', array('id' => $thread));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 8);
        $this->assertEquals(count($data['result']['course']), 2);
        $this->assertEquals($data['result']['course']['id'], 1);
        $this->assertEquals($data['result']['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['message']), 4);
        $this->assertEquals(count($data['result']['message']['user']), 4);
        $this->assertEquals($data['result']['message']['user']['id'], 2);
        $this->assertEquals($data['result']['message']['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['message']['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['message']['user']['avatar'], null);
        $this->assertEquals($data['result']['message']['id'], 2);
        $this->assertEquals($data['result']['message']['message'], "un super message update");
        $this->assertEquals(! empty($data['result']['message']['created_date']), true);
        $this->assertEquals($data['result']['nb_message'], 2);
        $this->assertEquals(count($data['result']['user']), 5);
        $this->assertEquals(count($data['result']['user']['roles']), 1);
        $this->assertEquals($data['result']['user']['roles'][0], "admin");
        $this->assertEquals($data['result']['user']['id'], 1);
        $this->assertEquals($data['result']['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['title'], "un titre update");
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddItemAssigment
     * @depends testCanAddSubmitItemAssigment
     * @depends testCanGradedTwo
     * @depends testCanAddUserCourse
     */
    public function testGetItemAssignment($assignment)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('itemassignment.get', array('id' => $assignment));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 7);
        $this->assertEquals(count($data['result']['item_prog']), 4);
        $this->assertEquals(count($data['result']['item_prog']['item']), 8);
        $this->assertEquals(count($data['result']['item_prog']['item']['materials']), 1);
        $this->assertEquals(count($data['result']['item_prog']['item']['materials'][0]), 9);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['id'], 3);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['description'] , null);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['course_id'], 1);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['type'], "type2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['title'], "title2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['author'], "author2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['link'], "link2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['source'], "src2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['token'], "token2");
        $this->assertEquals(count($data['result']['item_prog']['item']['program']), 2);
        $this->assertEquals($data['result']['item_prog']['item']['program']['id'], 1);
        $this->assertEquals($data['result']['item_prog']['item']['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['item_prog']['item']['course']), 3);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor']), 1);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor'][0]), 11);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['avatar'], null);
        $this->assertEquals($data['result']['item_prog']['item']['course']['id'], 1);
        $this->assertEquals($data['result']['item_prog']['item']['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['item_prog']['item']['item_grade']), 2);
        $this->assertEquals($data['result']['item_prog']['item']['item_grade']['grade'], 60);
        $this->assertEquals(! empty($data['result']['item_prog']['item']['item_grade']['created_date']), true);
        $this->assertEquals($data['result']['item_prog']['item']['id'], 1);
        $this->assertEquals($data['result']['item_prog']['item']['title'], "titl2e");
        $this->assertEquals($data['result']['item_prog']['item']['describe'], "description2");
        $this->assertEquals($data['result']['item_prog']['item']['type'], "WG");
        $this->assertEquals($data['result']['item_prog']['id'], 1);
        $this->assertEquals(! empty($data['result']['item_prog']['start_date']), true);
        $this->assertEquals(! empty($data['result']['item_prog']['due_date']), true);
        $this->assertEquals(count($data['result']['students']), 2);
        $this->assertEquals(count($data['result']['students'][0]), 6);
        $this->assertEquals(count($data['result']['students'][0]['school']), 5);
        $this->assertEquals($data['result']['students'][0]['school']['background'], null);
        $this->assertEquals($data['result']['students'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['students'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['students'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['students'][0]['school']['logo'], null);
        $this->assertEquals(count($data['result']['students'][0]['roles']), 1);
        $this->assertEquals($data['result']['students'][0]['roles'][0], "student");
        $this->assertEquals($data['result']['students'][0]['id'], 6);
        $this->assertEquals($data['result']['students'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result']['students'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result']['students'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['students'][1]), 6);
        $this->assertEquals(count($data['result']['students'][1]['school']), 5);
        $this->assertEquals($data['result']['students'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['students'][1]['school']['background'], null);
        $this->assertEquals($data['result']['students'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['students'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['students'][1]['school']['logo'], null);
        $this->assertEquals(count($data['result']['students'][1]['roles']), 1);
        $this->assertEquals($data['result']['students'][1]['roles'][0], "student");
        $this->assertEquals($data['result']['students'][1]['id'], 4);
        $this->assertEquals($data['result']['students'][1]['firstname'], "Salim");
        $this->assertEquals($data['result']['students'][1]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['students'][1]['avatar'], null);
        $this->assertEquals(count($data['result']['documents']), 1);
        $this->assertEquals(count($data['result']['documents'][0]), 8);
        $this->assertEquals($data['result']['documents'][0]['id'], 1);
        $this->assertEquals($data['result']['documents'][0]['item_assigment_id'], 2);
        $this->assertEquals($data['result']['documents'][0]['type'], "type");
        $this->assertEquals($data['result']['documents'][0]['title'], "title");
        $this->assertEquals($data['result']['documents'][0]['author'], "author");
        $this->assertEquals($data['result']['documents'][0]['link'], "link");
        $this->assertEquals($data['result']['documents'][0]['source'], "source");
        $this->assertEquals($data['result']['documents'][0]['token'], "token");
        $this->assertEquals(count($data['result']['comments']), 1);
        $this->assertEquals(count($data['result']['comments'][0]), 9);
        $this->assertEquals(count($data['result']['comments'][0]['user']), 5);
        $this->assertEquals(count($data['result']['comments'][0]['user']['roles']), 1);
        $this->assertEquals(count($data['result']['comments'][0]['user']['roles'][0]), 2);
        $this->assertEquals($data['result']['comments'][0]['user']['roles'][0]['id'], 3);
        $this->assertEquals($data['result']['comments'][0]['user']['roles'][0]['name'], "academic");
        $this->assertEquals($data['result']['comments'][0]['user']['id'], 3);
        $this->assertEquals($data['result']['comments'][0]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['comments'][0]['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['comments'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['comments'][0]['id'], 1);
        $this->assertEquals($data['result']['comments'][0]['text'], "text text text");
        $this->assertEquals($data['result']['comments'][0]['audio'] , null);
        $this->assertEquals($data['result']['comments'][0]['file'], null);
        $this->assertEquals($data['result']['comments'][0]['file_name'], null);
        $this->assertEquals($data['result']['comments'][0]['item_assignment_id'], 2);
        $this->assertEquals(! empty($data['result']['comments'][0]['created_date']), true);
        $this->assertEquals($data['result']['comments'][0]['read_date'], null);
        $this->assertEquals($data['result']['id'], 2);
        $this->assertEquals($data['result']['response'], "response");
        $this->assertEquals(! empty($data['result']['submit_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testAddCourse
     * @depends testAddMaterialDocument
     */
    public function testAddItemDocument($id, $material)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('item.add', array(
            'course' => $id,
            'duration' => 234,
            'title' => 'un document',
            'describe' => 'super description',
            'type' => 'DOC',
            'data' => [
                'link' => 'link',
                'token' => 'token',
                'title' => 'title'
            ]
        ));
    
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 3); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
    }
    
    /**
     * @depends testAddItemDocument
     */
    public function testUpdateItemDocument($item)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('document.update', array(
            'item' => $item,
            'link' => 'linkupt',
            'token' => 'tokenupt',
            'title' => 'titleupt'));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    
    
    // DELETE
    
    /**
     * @depends testAddCourse
     * @depends testGetItemAssignment
     */
    public function testCanAddDeleteCourse($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.deleteCourse', array('user' => 1,'course' => $course));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][1]), 1);
        $this->assertEquals($data['result'][1][1], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanDeleteItemProg($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.delete', array('id' => $item_prog));
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddTask
     */
    public function testCanDeleteTaskOfOther($task)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('task.delete', array('id' => $task));
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddTask
     * @depends testCanDeleteTaskOfOther
     */
    public function testCanDeleteTask($task)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('task.delete', array('id' => $task));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddThreadMessage
     */
    public function testDeletehreadMessage($threadmessage)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('threadmessage.delete', array('id' => $threadmessage));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddThread
     */
    public function testDeletehread($thread)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('thread.delete', array('id' => $thread));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddMaterialDocument
     */
    public function testDeleteMaterialDocument($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('materialdocument.delete', array('id' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgram
     */
    public function testCourseGetList($program)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.getList', array('program' => $program,'search' => 'ME'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 18);
        $this->assertEquals(count($data['result']['list'][0]['instructor']), 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]), 11);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['material_document']), 2);
        $this->assertEquals(count($data['result']['list'][0]['material_document'][0]), 13);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['description'] , null);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['course_id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['type'], "link");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['title'], "title");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['author'], "author");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['link'], "link");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['source'], "source");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['token'], "token");
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['date'], "2011-01-01");
        $this->assertEquals(! empty($data['result']['list'][0]['material_document'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][0]['material_document'][0]['updated_date'], null);
        $this->assertEquals(count($data['result']['list'][0]['material_document'][1]), 13);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['description'] , null);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['course_id'], 1);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['type'], "type2");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['title'], "title2");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['author'], "author2");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['link'], "link2");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['source'], "src2");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['token'], "token2");
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['date'], "2015-01-02");
        $this->assertEquals(! empty($data['result']['list'][0]['material_document'][1]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][0]['material_document'][1]['updated_date'], null);
        $this->assertEquals($data['result']['list'][0]['start_date'], null);
        $this->assertEquals($data['result']['list'][0]['end_date'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "IMERIR");
        $this->assertEquals($data['result']['list'][0]['abstract'], "un_token");
        $this->assertEquals($data['result']['list'][0]['description'], "description");
        $this->assertEquals($data['result']['list'][0]['objectives'], "objectives");
        $this->assertEquals($data['result']['list'][0]['teaching'], "teaching");
        $this->assertEquals($data['result']['list'][0]['attendance'], "attendance");
        $this->assertEquals($data['result']['list'][0]['duration'], 18);
        $this->assertEquals($data['result']['list'][0]['notes'], "notes");
        $this->assertEquals($data['result']['list'][0]['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['list'][0]['picture'], null);
        $this->assertEquals($data['result']['list'][0]['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['list'][0]['video_token'], "video_token");
        $this->assertEquals($data['result']['list'][0]['program_id'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testCanAddProgram
     */
    public function testDeleteCourse($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.delete', array('id' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals($data['result'][1], true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testEventGetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('event.getList', array());
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 9);
        $this->assertEquals(count($data['result']['list'][0]) , 10);
        $this->assertEquals($data['result']['list'][0]['view_date'] , null);
        $this->assertEquals($data['result']['list'][0]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][0]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][0]['read_date'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 19);
        $this->assertEquals(count($data['result']['list'][0]['source']) , 3);
        $this->assertEquals($data['result']['list'][0]['source']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['source']['name'] , "user");
        $this->assertEquals(count($data['result']['list'][0]['source']['data']) , 8);
        $this->assertEquals($data['result']['list'][0]['source']['data']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['source']['data']['email'] , "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['source']['data']['lastname'] , "Hoang");
        $this->assertEquals($data['result']['list'][0]['source']['data']['gender'] , null);
        $this->assertEquals($data['result']['list'][0]['source']['data']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['list'][0]['source']['data']['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][0]['source']['data']['school']) , 5);
        $this->assertEquals($data['result']['list'][0]['source']['data']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['source']['data']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['source']['data']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['source']['data']['school']['background'] , null);
        $this->assertEquals($data['result']['list'][0]['source']['data']['school']['name'] , "Morbi Corporation");
        $this->assertEquals(count($data['result']['list'][0]['source']['data']['user_roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['source']['data']['user_roles'][0] , "super_admin");
        $this->assertEquals(!empty($data['result']['list'][0]['date']) , true);
        $this->assertEquals($data['result']['list'][0]['event'] , "thread.message");
        $this->assertEquals(count($data['result']['list'][0]['object']) , 3);
        $this->assertEquals($data['result']['list'][0]['object']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['object']['name'] , "thread.message");
        $this->assertEquals(count($data['result']['list'][0]['object']['data']) , 2);
        $this->assertEquals($data['result']['list'][0]['object']['data']['message'] , "un super message");
        $this->assertEquals(count($data['result']['list'][0]['object']['data']['thread']) , 3);
        $this->assertEquals($data['result']['list'][0]['object']['data']['thread']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['object']['data']['thread']['title'] , "un titre update");
        $this->assertEquals(count($data['result']['list'][0]['object']['data']['thread']['course']) , 2);
        $this->assertEquals($data['result']['list'][0]['object']['data']['thread']['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['object']['data']['thread']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['comment']) , 0);
        $this->assertEquals(count($data['result']['list'][1]) , 10);
        $this->assertEquals($data['result']['list'][1]['view_date'] , null);
        $this->assertEquals($data['result']['list'][1]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][1]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][1]['read_date'] , null);
        $this->assertEquals($data['result']['list'][1]['id'] , 18);
        $this->assertEquals(count($data['result']['list'][1]['source']) , 3);
        $this->assertEquals($data['result']['list'][1]['source']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['source']['name'] , "user");
        $this->assertEquals(count($data['result']['list'][1]['source']['data']) , 8);
        $this->assertEquals($data['result']['list'][1]['source']['data']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][1]['source']['data']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['source']['data']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][1]['source']['data']['gender'] , null);
        $this->assertEquals($data['result']['list'][1]['source']['data']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['list'][1]['source']['data']['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][1]['source']['data']['school']) , 5);
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['short_name'] , "IUM");
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['logo'] , "token");
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['background'] , "background");
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['name'] , "université de monaco");
        $this->assertEquals(count($data['result']['list'][1]['source']['data']['user_roles']) , 1);
        $this->assertEquals($data['result']['list'][1]['source']['data']['user_roles'][0] , "admin");
        $this->assertEquals(!empty($data['result']['list'][1]['date']) , true);
        $this->assertEquals($data['result']['list'][1]['event'] , "thread.new");
        $this->assertEquals(count($data['result']['list'][1]['object']) , 3);
        $this->assertEquals($data['result']['list'][1]['object']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['object']['name'] , "thread");
        $this->assertEquals(count($data['result']['list'][1]['object']['data']) , 3);
        $this->assertEquals($data['result']['list'][1]['object']['data']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['object']['data']['title'] , "un titre");
        $this->assertEquals(count($data['result']['list'][1]['object']['data']['course']) , 2);
        $this->assertEquals($data['result']['list'][1]['object']['data']['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['object']['data']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][1]['comment']) , 0);
        $this->assertEquals(count($data['result']['list'][2]) , 10);
        $this->assertEquals($data['result']['list'][2]['view_date'] , null);
        $this->assertEquals($data['result']['list'][2]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][2]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][2]['read_date'] , null);
        $this->assertEquals($data['result']['list'][2]['id'] , 12);
        $this->assertEquals(count($data['result']['list'][2]['source']) , 3);
        $this->assertEquals($data['result']['list'][2]['source']['id'] , 3);
        $this->assertEquals($data['result']['list'][2]['source']['name'] , "user");
        $this->assertEquals(count($data['result']['list'][2]['source']['data']) , 8);
        $this->assertEquals($data['result']['list'][2]['source']['data']['firstname'] , "Christophe");
        $this->assertEquals($data['result']['list'][2]['source']['data']['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][2]['source']['data']['lastname'] , "Robert");
        $this->assertEquals($data['result']['list'][2]['source']['data']['gender'] , null);
        $this->assertEquals($data['result']['list'][2]['source']['data']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['list'][2]['source']['data']['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][2]['source']['data']['school']) , 5);
        $this->assertEquals($data['result']['list'][2]['source']['data']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][2]['source']['data']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][2]['source']['data']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][2]['source']['data']['school']['background'] , null);
        $this->assertEquals($data['result']['list'][2]['source']['data']['school']['name'] , "Morbi Corporation");
        $this->assertEquals(count($data['result']['list'][2]['source']['data']['user_roles']) , 1);
        $this->assertEquals($data['result']['list'][2]['source']['data']['user_roles'][0] , "academic");
        $this->assertEquals(!empty($data['result']['list'][2]['date']) , true);
        $this->assertEquals($data['result']['list'][2]['event'] , "task.shared");
        $this->assertEquals(count($data['result']['list'][2]['object']) , 3);
        $this->assertEquals($data['result']['list'][2]['object']['id'] , 1);
        $this->assertEquals($data['result']['list'][2]['object']['name'] , "task");
        $this->assertEquals(count($data['result']['list'][2]['object']['data']) , 6);
        $this->assertEquals($data['result']['list'][2]['object']['data']['id'] , 1);
        $this->assertEquals($data['result']['list'][2]['object']['data']['title'] , "TEST 2");
        $this->assertEquals($data['result']['list'][2]['object']['data']['content'] , null);
        $this->assertEquals(!empty($data['result']['list'][2]['object']['data']['start']) , true);
        $this->assertEquals(!empty($data['result']['list'][2]['object']['data']['end']) , true);
        $this->assertEquals($data['result']['list'][2]['object']['data']['creator_id'] , 3);
        $this->assertEquals(count($data['result']['list'][2]['comment']) , 0);
        $this->assertEquals($data['result']['count'] , 9);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testEventRead()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('eventuser.read', array('ids' => [1,2,4,5,6]));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 4);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    public function testEventRead2()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('eventuser.read', array('event' => ['school.new']));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    public function testEventView()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('eventuser.view', array('id' => 4));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
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
