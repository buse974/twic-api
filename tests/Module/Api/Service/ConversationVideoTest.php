<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;
use Application\Model\Role as ModelRole;

class ConversationVideoTest extends AbstractService
{
    public static $session;

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
                'submission' => [
                    [ 'submission_user' => [1] ],
                ],
                'has_all_student' => false,
                'is_grouped' => true,
                'title' => 'title',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'WG',
                'is_complete' => true,
                'parent' => null,
                'order' => null,
            ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['result'];
    }
    
    public function testCanAdd()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('conversation.create', [
            'users' => [1,2,3,4], 
            'text' => 'un text de la mort qui tue',
            'text_editors' => ['name' => 'name'],
            'whiteboards' => ['name' => 'name'],
            'documents' => [
                'name' => 'un document', 
                'link' => 'http://www.cellie.fr/wp-content/uploads/2015/01/Tutoriel_Google_Drive_Cellie.pdf'
            ],
            'submission_id' => 1,
            'has_video' => true,
        ]);
        
    }
    
    public function testCanAdd2()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('conversation.create', [
            'users' => [1,2],
            'text' => 'un text de la mort qui tue',
        ]);
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('conversation.addConversation', [
            'id' => 1,
            'conversation' => 2,
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanAdd3()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('conversation.create', [
            'users' => [3,4],
            'text' => 'un text de la mort qui tue',
        ]);
    
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('conversation.addConversation', [
            'id' => 1,
            'conversation' => 3,
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGetListId()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('conversation.getListId', [
            'id' => 1,
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][0] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
    
    public function testCanGetId()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('conversation.getId', [
            'item_id' => 1,
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGet()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('conversation.get', [
            'id' => 1,
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 14);
        $this->assertEquals(count($data['result']['messages']) , 2);
        $this->assertEquals(count($data['result']['messages']['list']) , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']) , 10);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from']) , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]) , 13);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contacts_count'] , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['school']) , 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['firstname'] , "Paul");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['document']) , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to']) , 3);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]) , 13);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['school']) , 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['id'] , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]) , 13);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['contact_state'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]['school']) , 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['logo'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['background'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['id'] , 3);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['firstname'] , "Christophe");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['lastname'] , "Robert");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['birth_date'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['position'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['interest'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]['roles']) , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['roles'][0] , "academic");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]['program']) , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][2]) , 13);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['contact_state'] , 3);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['contacts_count'] , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][2]['school']) , 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['school']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['school']['logo'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['school']['background'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['id'] , 2);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['lastname'] , "Hoang");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['email'] , "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['birth_date'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['position'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['interest'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][2]['roles']) , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][2]['roles'][0] , "admin");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][2]['program']) , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['title'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['text'] , "un text de la mort qui tue");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['token'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['type'] , 2);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['messages']['list'][0]['user']) , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['conversation_id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['from_id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['user_id'] , 1);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['read_date']) , true);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['messages']['count'] , 1);
        $this->assertEquals(count($data['result']['users']) , 4);
        $this->assertEquals(count($data['result']['users'][1]) , 5);
        $this->assertEquals(count($data['result']['users'][1]['roles']) , 1);
        $this->assertEquals($data['result']['users'][1]['roles'][0] , "student");
        $this->assertEquals($data['result']['users'][1]['id'] , 1);
        $this->assertEquals($data['result']['users'][1]['firstname'] , "Paul");
        $this->assertEquals($data['result']['users'][1]['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['users'][1]['avatar'] , null);
        $this->assertEquals(count($data['result']['users'][2]) , 5);
        $this->assertEquals(count($data['result']['users'][2]['roles']) , 1);
        $this->assertEquals($data['result']['users'][2]['roles'][0] , "admin");
        $this->assertEquals($data['result']['users'][2]['id'] , 2);
        $this->assertEquals($data['result']['users'][2]['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['users'][2]['lastname'] , "Hoang");
        $this->assertEquals($data['result']['users'][2]['avatar'] , null);
        $this->assertEquals(count($data['result']['users'][3]) , 5);
        $this->assertEquals(count($data['result']['users'][3]['roles']) , 1);
        $this->assertEquals($data['result']['users'][3]['roles'][0] , "academic");
        $this->assertEquals($data['result']['users'][3]['id'] , 3);
        $this->assertEquals($data['result']['users'][3]['firstname'] , "Christophe");
        $this->assertEquals($data['result']['users'][3]['lastname'] , "Robert");
        $this->assertEquals($data['result']['users'][3]['avatar'] , null);
        $this->assertEquals(count($data['result']['users'][4]) , 5);
        $this->assertEquals(count($data['result']['users'][4]['roles']) , 1);
        $this->assertEquals($data['result']['users'][4]['roles'][0] , "student");
        $this->assertEquals($data['result']['users'][4]['id'] , 4);
        $this->assertEquals($data['result']['users'][4]['firstname'] , "Salim");
        $this->assertEquals($data['result']['users'][4]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['users'][4]['avatar'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['name'] , "Chat");
        $this->assertEquals($data['result']['type'] , null);
        $this->assertEquals(!empty($data['result']['token']) , true);
        $this->assertEquals($data['result']['conversation_opt_id'] , null);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals(count($data['result']['conversations']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]) , 8);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']) , 2);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']) , 10);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]) , 13);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['contacts_count'] , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']) , 5);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['firstname'] , "Paul");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['document']) , 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]) , 13);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['contact_state'] , 3);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['contacts_count'] , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']) , 5);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['background'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['lastname'] , "Hoang");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['email'] , "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['roles'][0] , "admin");
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['program']) , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['title'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['text'] , "un text de la mort qui tue");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['token'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['type'] , 2);
        $this->assertEquals(!empty($data['result']['conversations'][2]['messages']['list'][0]['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['user']) , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['id'] , 5);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['conversation_id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['from_id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user_id'] , 1);
        $this->assertEquals(!empty($data['result']['conversations'][2]['messages']['list'][0]['read_date']) , true);
        $this->assertEquals(!empty($data['result']['conversations'][2]['messages']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['conversations'][2]['messages']['count'] , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['users']) , 2);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][1]) , 5);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][1]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][2]['users'][1]['roles'][0] , "student");
        $this->assertEquals($data['result']['conversations'][2]['users'][1]['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['users'][1]['firstname'] , "Paul");
        $this->assertEquals($data['result']['conversations'][2]['users'][1]['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['conversations'][2]['users'][1]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][2]) , 5);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][2]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][2]['users'][2]['roles'][0] , "admin");
        $this->assertEquals($data['result']['conversations'][2]['users'][2]['id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['users'][2]['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['conversations'][2]['users'][2]['lastname'] , "Hoang");
        $this->assertEquals($data['result']['conversations'][2]['users'][2]['avatar'] , null);
        $this->assertEquals($data['result']['conversations'][2]['id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['name'] , "Chat");
        $this->assertEquals($data['result']['conversations'][2]['type'] , null);
        $this->assertEquals($data['result']['conversations'][2]['token'] , null);
        $this->assertEquals($data['result']['conversations'][2]['conversation_opt_id'] , null);
        $this->assertEquals(!empty($data['result']['conversations'][2]['created_date']) , true);
        $this->assertEquals(count($data['result']['editors']) , 1);
        $this->assertEquals(count($data['result']['editors'][0]) , 5);
        $this->assertEquals($data['result']['editors'][0]['id'] , 1);
        $this->assertEquals($data['result']['editors'][0]['name'] , "name");
        $this->assertEquals($data['result']['editors'][0]['text'] , "");
        $this->assertEquals($data['result']['editors'][0]['submission_id'] , null);
        $this->assertEquals($data['result']['editors'][0]['submit_date'] , null);
        $this->assertEquals(count($data['result']['whiteboards']) , 1);
        $this->assertEquals(count($data['result']['whiteboards'][0]) , 3);
        $this->assertEquals($data['result']['whiteboards'][0]['id'] , 1);
        $this->assertEquals($data['result']['whiteboards'][0]['name'] , "name");
        $this->assertEquals($data['result']['whiteboards'][0]['owner_id'] , 1);
        $this->assertEquals(count($data['result']['documents']) , 1);
        $this->assertEquals(count($data['result']['documents'][0]) , 11);
        $this->assertEquals($data['result']['documents'][0]['id'] , 3);
        $this->assertEquals($data['result']['documents'][0]['name'] , "un document");
        $this->assertEquals($data['result']['documents'][0]['link'] , "http://www.cellie.fr/wp-content/uploads/2015/01/Tutoriel_Google_Drive_Cellie.pdf");
        $this->assertEquals($data['result']['documents'][0]['token'] , null);
        $this->assertEquals($data['result']['documents'][0]['type'] , null);
        $this->assertEquals(!empty($data['result']['documents'][0]['created_date']) , true);
        $this->assertEquals($data['result']['documents'][0]['deleted_date'] , null);
        $this->assertEquals($data['result']['documents'][0]['updated_date'] , null);
        $this->assertEquals($data['result']['documents'][0]['folder_id'] , null);
        $this->assertEquals($data['result']['documents'][0]['owner_id'] , 1);
        $this->assertEquals($data['result']['documents'][0]['box_id'] , null);
        $this->assertEquals(count($data['result']['conversation_opt']) , 7);
        $this->assertEquals($data['result']['conversation_opt']['id'] , 1);
        $this->assertEquals($data['result']['conversation_opt']['item_id'] , 1);
        $this->assertEquals($data['result']['conversation_opt']['record'] , 1);
        $this->assertEquals($data['result']['conversation_opt']['nb_user_autorecord'] , 2);
        $this->assertEquals($data['result']['conversation_opt']['allow_intructor'] , 1);
        $this->assertEquals($data['result']['conversation_opt']['start_date'] , null);
        $this->assertEquals($data['result']['conversation_opt']['duration'] , null);
        $this->assertEquals(!empty($data['result']['user_token']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
   

    public function testCanAddVideo()
    {
        //MOCK OPENTOK
        $serviceManager = $this->getApplicationServiceLocator();
        $mock = $this->getMockBuilder('opentok')
            ->setMethods(['getArchive'])
            ->getMock();
        $mock->expects($this->any())
            ->method('getArchive')
            ->willReturn(json_encode(['status' => 'available', 'duration' => 1234, 'url' => 'http://google.fr']));
        
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('opentok.service', $mock);
        //MOCK
        
        $this->setIdentity(1);
        $s_va = $serviceManager->get('app_service_video_archive');
        $s_va->add(1,'myToken');
        $s_va->add(1,'myToken2');
        $s_va->add(1,'myToken3');
        $s_va->add(1,'myToken4');
        
        $data = $this->jsonRpc('videoarchive.getListVideoUpload', []);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result'][0]) , 5);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['archive_token'] , "myToken");
        $this->assertEquals($data['result'][0]['archive_link'] , null);
        $this->assertEquals($data['result'][0]['archive_status'] , "started");
        $this->assertEquals($data['result'][0]['url'] , "http://google.fr");
        $this->assertEquals(count($data['result'][1]) , 5);
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['archive_token'] , "myToken2");
        $this->assertEquals($data['result'][1]['archive_link'] , null);
        $this->assertEquals($data['result'][1]['archive_status'] , "started");
        $this->assertEquals($data['result'][1]['url'] , "http://google.fr");
        $this->assertEquals(count($data['result'][2]) , 5);
        $this->assertEquals($data['result'][2]['id'] , 3);
        $this->assertEquals($data['result'][2]['archive_token'] , "myToken3");
        $this->assertEquals($data['result'][2]['archive_link'] , null);
        $this->assertEquals($data['result'][2]['archive_status'] , "started");
        $this->assertEquals($data['result'][2]['url'] , "http://google.fr");
        $this->assertEquals(count($data['result'][3]) , 5);
        $this->assertEquals($data['result'][3]['id'] , 4);
        $this->assertEquals($data['result'][3]['archive_token'] , "myToken4");
        $this->assertEquals($data['result'][3]['archive_link'] , null);
        $this->assertEquals($data['result'][3]['archive_status'] , "started");
        $this->assertEquals($data['result'][3]['url'] , "http://google.fr");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanValid()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('videoarchive.validTransfertVideo', ['video_archive' => 1, 'url' => 'http://ici.fr']);
        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('videoarchive.validTransfertVideo', ['video_archive' => 2, 'url' => 'http://ici.fr']);
        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('videoarchive.validTransfertVideo', ['video_archive' => 3, 'url' => 'http://ici.fr']);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGetList()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('videoarchive.getList', ['submission_id' => 1]);
    
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][1]) , 3);
        $this->assertEquals(count($data['result'][1][0]) , 8);
        $this->assertEquals($data['result'][1][0]['submission_id'] , 1);
        $this->assertEquals($data['result'][1][0]['id'] , 1);
        $this->assertEquals($data['result'][1][0]['archive_token'] , "myToken");
        $this->assertEquals($data['result'][1][0]['archive_link'] , "http://ici.fr");
        $this->assertEquals($data['result'][1][0]['archive_status'] , "available");
        $this->assertEquals($data['result'][1][0]['archive_duration'] , 1234);
        $this->assertEquals($data['result'][1][0]['conversation_id'] , 1);
        $this->assertEquals(!empty($data['result'][1][0]['created_date']) , true);
        $this->assertEquals(count($data['result'][1][1]) , 8);
        $this->assertEquals($data['result'][1][1]['submission_id'] , 1);
        $this->assertEquals($data['result'][1][1]['id'] , 2);
        $this->assertEquals($data['result'][1][1]['archive_token'] , "myToken2");
        $this->assertEquals($data['result'][1][1]['archive_link'] , "http://ici.fr");
        $this->assertEquals($data['result'][1][1]['archive_status'] , "available");
        $this->assertEquals($data['result'][1][1]['archive_duration'] , 1234);
        $this->assertEquals($data['result'][1][1]['conversation_id'] , 1);
        $this->assertEquals(!empty($data['result'][1][1]['created_date']) , true);
        $this->assertEquals(count($data['result'][1][2]) , 8);
        $this->assertEquals($data['result'][1][2]['submission_id'] , 1);
        $this->assertEquals($data['result'][1][2]['id'] , 3);
        $this->assertEquals($data['result'][1][2]['archive_token'] , "myToken3");
        $this->assertEquals($data['result'][1][2]['archive_link'] , "http://ici.fr");
        $this->assertEquals($data['result'][1][2]['archive_status'] , "available");
        $this->assertEquals($data['result'][1][2]['archive_duration'] , 1234);
        $this->assertEquals($data['result'][1][2]['conversation_id'] , 1);
        $this->assertEquals(!empty($data['result'][1][2]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
    
}
