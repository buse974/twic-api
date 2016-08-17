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
        
        $data = $this->jsonRpc('school.add', array('circle_id' => 2, 'name' => 'université de monaco','next_name' => 'buisness school','short_name' => 'IUM','logo' => 'token','describe' => 'une description','website' => 'www.ium.com','programme' => 'super programme','background' => 'background','phone' => '+33480547852','contact' => 'contact@ium.com','contact_id' => 1,'address' => array("street_no" => 12,"street_type" => "rue","street_name" => "du stade","city" => array("name" => "Monaco"),"country" => array("name" => "Monaco"))));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 15);
        $this->assertEquals(count($data['result']['address']), 14);
        $this->assertEquals(count($data['result']['address']['city']), 2);
        $this->assertEquals($data['result']['address']['city']['id'], 19064);
        $this->assertEquals($data['result']['address']['city']['name'], "Monaco");
        $this->assertEquals($data['result']['address']['division'], null);
        $this->assertEquals(count($data['result']['address']['country']), 3);
        $this->assertEquals($data['result']['address']['country']['id'], 145);
        $this->assertEquals($data['result']['address']['country']['short_name'], "Monaco");
        $this->assertEquals($data['result']['address']['country']['name'], "Principality of Monaco");
        $this->assertEquals($data['result']['address']['id'], 1);
        $this->assertEquals($data['result']['address']['street_no'], 12);
        $this->assertEquals($data['result']['address']['street_type'], "rue");
        $this->assertEquals($data['result']['address']['street_name'], "du stade");
        $this->assertEquals(! empty($data['result']['address']['longitude']), true);
        $this->assertEquals(! empty($data['result']['address']['latitude']), true);
        $this->assertEquals($data['result']['address']['door'], null);
        $this->assertEquals($data['result']['address']['building'], null);
        $this->assertEquals($data['result']['address']['apartment'], null);
        $this->assertEquals($data['result']['address']['floor'], null);
        $this->assertEquals($data['result']['address']['timezone'], "Europe/Monaco");
        $this->assertEquals(count($data['result']['contact_user']), 9);
        $this->assertEquals($data['result']['contact_user']['id'], 1);
        $this->assertEquals($data['result']['contact_user']['firstname'], "Paul");
        $this->assertEquals($data['result']['contact_user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['contact_user']['status'], null);
        $this->assertEquals($data['result']['contact_user']['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['contact_user']['birth_date'], null);
        $this->assertEquals($data['result']['contact_user']['position'], null);
        $this->assertEquals($data['result']['contact_user']['interest'], null);
        $this->assertEquals($data['result']['contact_user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 3);
        $this->assertEquals($data['result']['name'], "université de monaco");
        $this->assertEquals($data['result']['next_name'], "buisness school");
        $this->assertEquals($data['result']['short_name'], "IUM");
        $this->assertEquals($data['result']['logo'], "token");
        $this->assertEquals($data['result']['libelle'], null);
        $this->assertEquals($data['result']['custom'], null);
        $this->assertEquals($data['result']['describe'], "une description");
        $this->assertEquals($data['result']['website'], "www.ium.com");
        $this->assertEquals($data['result']['background'], "background");
        $this->assertEquals($data['result']['phone'], + 33480547852);
        $this->assertEquals($data['result']['contact'], "contact@ium.com");
        $this->assertEquals($data['result']['address_id'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
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
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddSchool
     */
    public function testCanGetListSchool()
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('school.getList', array());

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 2);
        $this->assertEquals(count($data['result']['list']), 2);
        $this->assertEquals(count($data['result']['list'][0]), 13);
        $this->assertEquals(count($data['result']['list'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['address']), 0);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['next_name'], "Dolor Dolor Foundation");
        $this->assertEquals($data['result']['list'][0]['libelle'], null);
        $this->assertEquals($data['result']['list'][0]['custom'], null);
        $this->assertEquals($data['result']['list'][0]['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['logo'], null);
        $this->assertEquals($data['result']['list'][0]['describe'], "vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non,");
        $this->assertEquals($data['result']['list'][0]['website'], "http://");
        $this->assertEquals($data['result']['list'][0]['background'], null);
        $this->assertEquals($data['result']['list'][0]['phone'], "04 17 21 41 32");
        $this->assertEquals(count($data['result']['list'][1]), 13);
        $this->assertEquals(count($data['result']['list'][1]['program']), 1);
        $this->assertEquals(count($data['result']['list'][1]['program'][0]), 8);
        $this->assertEquals($data['result']['list'][1]['program'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['program'][0]['name'], "program name");
        $this->assertEquals($data['result']['list'][1]['program'][0]['school_id'], 3);
        $this->assertEquals($data['result']['list'][1]['program'][0]['level'], "emba");
        $this->assertEquals($data['result']['list'][1]['program'][0]['sis'], "sis");
        $this->assertEquals($data['result']['list'][1]['program'][0]['year'], null);
        $this->assertEquals($data['result']['list'][1]['program'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][1]['program'][0]['created_date'], null);
        $this->assertEquals(count($data['result']['list'][1]['address']), 14);
        $this->assertEquals(count($data['result']['list'][1]['address']['city']), 2);
        $this->assertEquals($data['result']['list'][1]['address']['city']['id'], 19064);
        $this->assertEquals($data['result']['list'][1]['address']['city']['name'], "Monaco");
        $this->assertEquals(count($data['result']['list'][1]['address']['division']), 0);
        $this->assertEquals(count($data['result']['list'][1]['address']['country']), 3);
        $this->assertEquals($data['result']['list'][1]['address']['country']['id'], 145);
        $this->assertEquals($data['result']['list'][1]['address']['country']['short_name'], "Monaco");
        $this->assertEquals($data['result']['list'][1]['address']['country']['name'], "Principality of Monaco");
        $this->assertEquals($data['result']['list'][1]['address']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['address']['street_no'], 12);
        $this->assertEquals($data['result']['list'][1]['address']['street_type'], "rue");
        $this->assertEquals($data['result']['list'][1]['address']['street_name'], "du stade");
        $this->assertEquals(! empty($data['result']['list'][1]['address']['longitude']), true);
        $this->assertEquals(! empty($data['result']['list'][1]['address']['latitude']), true);
        $this->assertEquals($data['result']['list'][1]['address']['door'], null);
        $this->assertEquals($data['result']['list'][1]['address']['building'], null);
        $this->assertEquals($data['result']['list'][1]['address']['apartment'], null);
        $this->assertEquals($data['result']['list'][1]['address']['floor'], null);
        $this->assertEquals($data['result']['list'][1]['address']['timezone'], 'Europe/Monaco');
        $this->assertEquals($data['result']['list'][1]['id'], 3);
        $this->assertEquals($data['result']['list'][1]['name'], "université de monaco");
        $this->assertEquals($data['result']['list'][1]['next_name'], "buisness school");
        $this->assertEquals($data['result']['list'][1]['short_name'], "IUM");
        $this->assertEquals($data['result']['list'][1]['libelle'], null);
        $this->assertEquals($data['result']['list'][1]['custom'], null);
        $this->assertEquals($data['result']['list'][1]['logo'], "token");
        $this->assertEquals($data['result']['list'][1]['describe'], "une description");
        $this->assertEquals($data['result']['list'][1]['website'], "www.ium.com");
        $this->assertEquals($data['result']['list'][1]['background'], "background");
        $this->assertEquals($data['result']['list'][1]['phone'], + 33480547852);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 16);
        $this->assertEquals(count($data['result']['program']), 2);
        $this->assertEquals($data['result']['program']['id'], 2);
        $this->assertEquals($data['result']['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['school']), 3);
        $this->assertEquals($data['result']['school']['id'], 3);
        $this->assertEquals($data['result']['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['school']['logo'], "token");
        $this->assertEquals(count($data['result']['creator']), 5);
        $this->assertEquals(count($data['result']['creator']['school']), 3);
        $this->assertEquals($data['result']['creator']['school']['id'], 3);
        $this->assertEquals($data['result']['creator']['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['creator']['school']['logo'], "token");
        $this->assertEquals($data['result']['creator']['id'], 1);
        $this->assertEquals($data['result']['creator']['firstname'], "Paul");
        $this->assertEquals($data['result']['creator']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['creator']['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['title'], "IMERIR");
        $this->assertEquals($data['result']['abstract'], "un_token");
        $this->assertEquals($data['result']['description'], "description");
        $this->assertEquals($data['result']['objectives'], "objectives");
        $this->assertEquals($data['result']['teaching'], "teaching");
        $this->assertEquals($data['result']['attendance'], "attendance");
        $this->assertEquals($data['result']['duration'], 18);
        $this->assertEquals($data['result']['notes'], "notes");
        $this->assertEquals($data['result']['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['picture'], null);
        $this->assertEquals($data['result']['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['video_token'], "video_token");
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['id'];
    }

    /**
     * @depends testAddCourse
     */
    public function testCanAddUserCourse($course)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 4,'course' => $course));
        
        $this->reset();
        
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
        $data = $this->jsonRpc('set.add', ['course' => $course,'name' => 'nameset','uid' => 'suid','groups' => [['name' => 'namegroup','uid' => 'guid','users' => [3,4]]]]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 6);
        $this->assertEquals(count($data['result']['groups']), 1);
        $this->assertEquals(count($data['result']['groups'][0]), 4);
        $this->assertEquals(count($data['result']['groups'][0]['users']), 2);
        $this->assertEquals($data['result']['groups'][0]['users'][0], 3);
        $this->assertEquals($data['result']['groups'][0]['users'][1], 4);
        $this->assertEquals($data['result']['groups'][0]['id'], 1);
        $this->assertEquals($data['result']['groups'][0]['uid'], "guid");
        $this->assertEquals($data['result']['groups'][0]['name'], "namegroup");
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['uid'], "suid");
        $this->assertEquals($data['result']['name'], "nameset");
        $this->assertEquals($data['result']['course_id'], 1);
        $this->assertEquals($data['result']['is_used'], 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['id'];
    }

    /**
     * @depends testAddCourse
     */
    public function testReplaceGroup()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('group.replaceUser', ['id' => 1,'users' => [3,4]]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result'][3], true);
        $this->assertEquals($data['result'][4], true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testSetGetList($course)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.getList', ['course' => $course]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 5);
        $this->assertEquals(count($data['result'][0]['groups']), 1);
        $this->assertEquals(count($data['result'][0]['groups'][0]), 4);
        $this->assertEquals(count($data['result'][0]['groups'][0]['users']), 2);
        $this->assertEquals($data['result'][0]['groups'][0]['users'][0], 3);
        $this->assertEquals($data['result'][0]['groups'][0]['users'][1], 4);
        $this->assertEquals($data['result'][0]['groups'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['groups'][0]['uid'], "guid");
        $this->assertEquals($data['result'][0]['groups'][0]['name'], "namegroup");
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['uid'], "suid");
        $this->assertEquals($data['result'][0]['name'], "nameset");
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddSet
     */
    public function testUpdateSet($set)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.update', ['id' => $set,'name' => 'namesetupt','uid' => 'suidupt']);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddItemONEONE($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('item.add', array('course' => $id,'duration' => 234,'title' => 'titl2e','set_id' => 1,'describe' => 'desone','type' => 'TXT'));
        
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
        $data = $this->jsonRpc('item.getListUsers', ['item_id' => $item]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 13);
        $this->assertEquals($data['result'][0]['gender'], null);
        $this->assertEquals($data['result'][0]['contact_state'], 0);
        $this->assertEquals($data['result'][0]['id'], 3);
        $this->assertEquals($data['result'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result'][0]['lastname'], "Robert");
        $this->assertEquals($data['result'][0]['nickname'], null);
        $this->assertEquals($data['result'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result'][0]['birth_date'], null);
        $this->assertEquals($data['result'][0]['position'], null);
        $this->assertEquals($data['result'][0]['school_id'], 1);
        $this->assertEquals($data['result'][0]['interest'], null);
        $this->assertEquals($data['result'][0]['avatar'], null);
        $this->assertEquals($data['result'][0]['has_email_notifier'], 1);
        $this->assertEquals(count($data['result'][1]), 13);
        $this->assertEquals($data['result'][1]['gender'], null);
        $this->assertEquals($data['result'][1]['contact_state'], 0);
        $this->assertEquals($data['result'][1]['id'], 4);
        $this->assertEquals($data['result'][1]['firstname'], "Salim");
        $this->assertEquals($data['result'][1]['lastname'], "Bendacha");
        $this->assertEquals($data['result'][1]['nickname'], null);
        $this->assertEquals($data['result'][1]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result'][1]['birth_date'], null);
        $this->assertEquals($data['result'][1]['position'], null);
        $this->assertEquals($data['result'][1]['school_id'], 1);
        $this->assertEquals($data['result'][1]['interest'], null);
        $this->assertEquals($data['result'][1]['avatar'], null);
        $this->assertEquals($data['result'][1]['has_email_notifier'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testAddSet
     * @depends testAddItemONEONE
     */
    public function testAddItem($id, $set, $item)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', [
            'course' => (int) $id,
            'title' => 'title',
            'submission' => [
                ['submission_user' => [1,4]]
            ],
            'describe' => 'description',
            'duration' => 234,
            'type' => 'HANGOUT',
            'set_id' => $set,
            'ct' => [
                'done' => [['target' => $item,'all' => true]], 
                'date' => [['date' => '2016-01-01','after' => true]],
                'rate' => [['inf' => '2016-01-01','sup' => true,'target' => $item]],
                'group' => [['group' => 1,'belongs' => true]]],
            'opt' => ['grading' => ['mode' => 'average','has_pg' => true,'pg_nb' => 2,'pg_auto' => true,'pg_due_date' => '2016-10-10','pg_can_view' => true,'user_can_view' => true,'pg_stars' => true]],
            'data' => null,
            'parent' => null,
            'order' => null,
            'is_complete' => true]);
        
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
        $this->setIdentity(1, 5);
        
        $data = $this->jsonRpc('item.get', array('id' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 25);
        $this->assertEquals(count($data['result']['videoconf']), 9);
        $this->assertEquals($data['result']['videoconf']['id'], 1);
        $this->assertEquals($data['result']['videoconf']['item_id'], 2);
        $this->assertEquals($data['result']['videoconf']['record'], 1);
        $this->assertEquals($data['result']['videoconf']['nb_user_autorecord'], 2);
        $this->assertEquals($data['result']['videoconf']['allow_intructor'], 1);
        $this->assertEquals($data['result']['videoconf']['has_eqcq'], 0);
        $this->assertEquals($data['result']['videoconf']['start_date'], null);
        $this->assertEquals($data['result']['videoconf']['duration'], null);
        $this->assertEquals($data['result']['videoconf']['rules'], null);
        $this->assertEquals(count($data['result']['program']), 2);
        $this->assertEquals($data['result']['program']['id'], 2);
        $this->assertEquals($data['result']['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['course']), 2);
        $this->assertEquals($data['result']['course']['id'], 1);
        $this->assertEquals($data['result']['course']['title'], "IMERIR");
        $this->assertEquals(count($data['result']['ct_group']), 1);
        $this->assertEquals(count($data['result']['ct_group'][0]), 5);
        $this->assertEquals($data['result']['ct_group'][0]['id'], 1);
        $this->assertEquals($data['result']['ct_group'][0]['item_id'], 2);
        $this->assertEquals($data['result']['ct_group'][0]['group_id'], 1);
        $this->assertEquals($data['result']['ct_group'][0]['set_id'], null);
        $this->assertEquals($data['result']['ct_group'][0]['belongs'], 1);
        $this->assertEquals(count($data['result']['ct_date']), 1);
        $this->assertEquals(count($data['result']['ct_date'][0]), 4);
        $this->assertEquals($data['result']['ct_date'][0]['id'], 1);
        $this->assertEquals($data['result']['ct_date'][0]['item_id'], 2);
        $this->assertEquals(! empty($data['result']['ct_date'][0]['date']), true);
        $this->assertEquals($data['result']['ct_date'][0]['after'], 1);
        $this->assertEquals(count($data['result']['ct_rate']), 1);
        $this->assertEquals(count($data['result']['ct_rate'][0]), 5);
        $this->assertEquals($data['result']['ct_rate'][0]['id'], 1);
        $this->assertEquals($data['result']['ct_rate'][0]['item_id'], 2);
        $this->assertEquals($data['result']['ct_rate'][0]['inf'], 2016);
        $this->assertEquals($data['result']['ct_rate'][0]['sup'], 1);
        $this->assertEquals($data['result']['ct_rate'][0]['target_id'], 1);
        $this->assertEquals(count($data['result']['ct_done']), 1);
        $this->assertEquals(count($data['result']['ct_done'][0]), 4);
        $this->assertEquals($data['result']['ct_done'][0]['id'], 1);
        $this->assertEquals($data['result']['ct_done'][0]['item_id'], 2);
        $this->assertEquals($data['result']['ct_done'][0]['target_id'], 1);
        $this->assertEquals($data['result']['ct_done'][0]['all'], 1);
        $this->assertEquals(count($data['result']['opt_grading']), 8);
        $this->assertEquals($data['result']['opt_grading']['mode'], "average");
        $this->assertEquals($data['result']['opt_grading']['has_pg'], 1);
        $this->assertEquals($data['result']['opt_grading']['pg_nb'], 2);
        $this->assertEquals($data['result']['opt_grading']['pg_auto'], 1);
        $this->assertEquals(! empty($data['result']['opt_grading']['pg_due_date']), true);
        $this->assertEquals($data['result']['opt_grading']['pg_can_view'], 1);
        $this->assertEquals($data['result']['opt_grading']['user_can_view'], 1);
        $this->assertEquals($data['result']['opt_grading']['pg_stars'], 1);
        $this->assertEquals($data['result']['id'], 2);
        $this->assertEquals($data['result']['course_id'], 1);
        $this->assertEquals($data['result']['grading_policy_id'], null);
        $this->assertEquals($data['result']['title'], "title");
        $this->assertEquals($data['result']['describe'], "description");
        $this->assertEquals($data['result']['duration'], 234);
        $this->assertEquals($data['result']['type'], "HANGOUT");
        $this->assertEquals($data['result']['set_id'], 1);
        $this->assertEquals($data['result']['parent_id'], null);
        $this->assertEquals($data['result']['order_id'], null);
        $this->assertEquals($data['result']['has_submission'], 1);
        $this->assertEquals($data['result']['start'], null);
        $this->assertEquals($data['result']['end'], null);
        $this->assertEquals($data['result']['cut_off'], null);
        $this->assertEquals($data['result']['is_grouped'], null);
        $this->assertEquals($data['result']['has_all_student'], 1);
        $this->assertEquals($data['result']['coefficient'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testAddItem
     */
    public function testAddItemTwo($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', array('course' => $id,'duration' => 234,'title' => 'titl2e','describe' => 'description2','type' => 'CP'));
        
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
        
        $data = $this->jsonRpc('item.update', ['id' => (int) $id,'duration' => 123,'title' => 'titl2e','describe' => 'description2']);
        
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
        $this->setIdentity(1, 5);
        
        $data = $this->jsonRpc('item.getList', array('course' => $course));
        
        // "\n3:2:1\n";
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals(count($data['result'][0]), 22);
        $this->assertEquals($data['result'][0]['is_started'], 0);
        $this->assertEquals($data['result'][0]['library'], null);
        $this->assertEquals($data['result'][0]['id'], 3);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['grading_policy_id'], null);
        $this->assertEquals($data['result'][0]['title'], "titl2e");
        $this->assertEquals($data['result'][0]['duration'], 234);
        $this->assertEquals($data['result'][0]['type'], "CP");
        $this->assertEquals($data['result'][0]['set_id'], null);
        $this->assertEquals($data['result'][0]['parent_id'], null);
        $this->assertEquals($data['result'][0]['order_id'], null);
        $this->assertEquals($data['result'][0]['has_submission'], 1);
        $this->assertEquals($data['result'][0]['start'], null);
        $this->assertEquals($data['result'][0]['end'], null);
        $this->assertEquals($data['result'][0]['cut_off'], null);
        $this->assertEquals($data['result'][0]['is_graded'], 0);
        $this->assertEquals($data['result'][0]['is_grouped'], null);
        $this->assertEquals($data['result'][0]['has_all_student'], 1);
        $this->assertEquals($data['result'][0]['is_complete'], 0);
        $this->assertEquals($data['result'][0]['coefficient'], 1);
        $this->assertEquals(count($data['result'][0]['done']), 0);
        $this->assertEquals(count($data['result'][0]['rate']), 0);
        $this->assertEquals(count($data['result'][1]), 22);
        $this->assertEquals($data['result'][1]['is_started'], 0);
        $this->assertEquals($data['result'][1]['library'], null);
        $this->assertEquals($data['result'][1]['id'], 2);
        $this->assertEquals($data['result'][1]['course_id'], 1);
        $this->assertEquals($data['result'][1]['grading_policy_id'], null);
        $this->assertEquals($data['result'][1]['title'], "titl2e");
        $this->assertEquals($data['result'][1]['duration'], 123);
        $this->assertEquals($data['result'][1]['type'], "HANGOUT");
        $this->assertEquals($data['result'][1]['set_id'], 1);
        $this->assertEquals($data['result'][1]['parent_id'], null);
        $this->assertEquals($data['result'][1]['order_id'], 3);
        $this->assertEquals($data['result'][1]['has_submission'], 1);
        $this->assertEquals($data['result'][1]['start'], null);
        $this->assertEquals($data['result'][1]['end'], null);
        $this->assertEquals($data['result'][1]['cut_off'], null);
        $this->assertEquals($data['result'][1]['is_graded'], 0);
        $this->assertEquals($data['result'][1]['is_grouped'], null);
        $this->assertEquals($data['result'][1]['has_all_student'], 1);
        $this->assertEquals($data['result'][1]['is_complete'], 1);
        $this->assertEquals($data['result'][1]['coefficient'], 1);
        $this->assertEquals(count($data['result'][1]['done']), 1);
        $this->assertEquals(count($data['result'][1]['done'][0]), 4);
        $this->assertEquals($data['result'][1]['done'][0]['id'], 1);
        $this->assertEquals($data['result'][1]['done'][0]['item_id'], 2);
        $this->assertEquals($data['result'][1]['done'][0]['target_id'], 1);
        $this->assertEquals($data['result'][1]['done'][0]['all'], 1);
        $this->assertEquals(count($data['result'][1]['rate']), 1);
        $this->assertEquals(count($data['result'][1]['rate'][0]), 5);
        $this->assertEquals($data['result'][1]['rate'][0]['id'], 1);
        $this->assertEquals($data['result'][1]['rate'][0]['item_id'], 2);
        $this->assertEquals($data['result'][1]['rate'][0]['inf'], 2016);
        $this->assertEquals($data['result'][1]['rate'][0]['sup'], 1);
        $this->assertEquals($data['result'][1]['rate'][0]['target_id'], 1);
        $this->assertEquals(count($data['result'][2]), 22);
        $this->assertEquals($data['result'][2]['is_started'], 0);
        $this->assertEquals($data['result'][2]['library'], null);
        $this->assertEquals($data['result'][2]['id'], 1);
        $this->assertEquals($data['result'][2]['course_id'], 1);
        $this->assertEquals($data['result'][2]['grading_policy_id'], null);
        $this->assertEquals($data['result'][2]['title'], "titl2e");
        $this->assertEquals($data['result'][2]['duration'], 234);
        $this->assertEquals($data['result'][2]['type'], "TXT");
        $this->assertEquals($data['result'][2]['set_id'], 1);
        $this->assertEquals($data['result'][2]['parent_id'], null);
        $this->assertEquals($data['result'][2]['order_id'], 2);
        $this->assertEquals($data['result'][2]['has_submission'], 1);
        $this->assertEquals($data['result'][2]['start'], null);
        $this->assertEquals($data['result'][2]['end'], null);
        $this->assertEquals($data['result'][2]['cut_off'], null);
        $this->assertEquals($data['result'][2]['is_graded'], 0);
        $this->assertEquals($data['result'][2]['is_grouped'], null);
        $this->assertEquals($data['result'][2]['has_all_student'], 1);
        $this->assertEquals($data['result'][2]['is_complete'], 0);
        $this->assertEquals($data['result'][2]['coefficient'], 1);
        $this->assertEquals(count($data['result'][2]['done']), 0);
        $this->assertEquals(count($data['result'][2]['rate']), 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testCanGetListItemuu($course)
    {
        $this->setIdentity(1);
        
        $this->jsonRpc('item.update', ['id' => 3,'duration' => 123,'title' => 'titl2e','describe' => 'description2','order_id' => 2]);
        
        $this->reset();
        
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.getList', array('course' => $course));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals(count($data['result'][0]), 22);
        $this->assertEquals($data['result'][0]['is_started'], 0);
        $this->assertEquals($data['result'][0]['library'], null);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['grading_policy_id'], null);
        $this->assertEquals($data['result'][0]['title'], "titl2e");
        $this->assertEquals($data['result'][0]['duration'], 123);
        $this->assertEquals($data['result'][0]['type'], "HANGOUT");
        $this->assertEquals($data['result'][0]['set_id'], 1);
        $this->assertEquals($data['result'][0]['parent_id'], null);
        $this->assertEquals($data['result'][0]['order_id'], null);
        $this->assertEquals($data['result'][0]['has_submission'], 1);
        $this->assertEquals($data['result'][0]['start'], null);
        $this->assertEquals($data['result'][0]['end'], null);
        $this->assertEquals($data['result'][0]['cut_off'], null);
        $this->assertEquals($data['result'][0]['is_graded'], 0);
        $this->assertEquals($data['result'][0]['is_grouped'], null);
        $this->assertEquals($data['result'][0]['has_all_student'], 1);
        $this->assertEquals($data['result'][0]['is_complete'], 1);
        $this->assertEquals($data['result'][0]['coefficient'], 1);
        $this->assertEquals(count($data['result'][0]['done']), 1);
        $this->assertEquals(count($data['result'][0]['done'][0]), 4);
        $this->assertEquals($data['result'][0]['done'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['done'][0]['item_id'], 2);
        $this->assertEquals($data['result'][0]['done'][0]['target_id'], 1);
        $this->assertEquals($data['result'][0]['done'][0]['all'], 1);
        $this->assertEquals(count($data['result'][0]['rate']), 1);
        $this->assertEquals(count($data['result'][0]['rate'][0]), 5);
        $this->assertEquals($data['result'][0]['rate'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['rate'][0]['item_id'], 2);
        $this->assertEquals($data['result'][0]['rate'][0]['inf'], 2016);
        $this->assertEquals($data['result'][0]['rate'][0]['sup'], 1);
        $this->assertEquals($data['result'][0]['rate'][0]['target_id'], 1);
        $this->assertEquals(count($data['result'][1]), 22);
        $this->assertEquals($data['result'][1]['is_started'], 0);
        $this->assertEquals($data['result'][1]['library'], null);
        $this->assertEquals($data['result'][1]['id'], 3);
        $this->assertEquals($data['result'][1]['course_id'], 1);
        $this->assertEquals($data['result'][1]['grading_policy_id'], null);
        $this->assertEquals($data['result'][1]['title'], "titl2e");
        $this->assertEquals($data['result'][1]['duration'], 123);
        $this->assertEquals($data['result'][1]['type'], "CP");
        $this->assertEquals($data['result'][1]['set_id'], null);
        $this->assertEquals($data['result'][1]['parent_id'], null);
        $this->assertEquals($data['result'][1]['order_id'], 2);
        $this->assertEquals($data['result'][1]['has_submission'], 1);
        $this->assertEquals($data['result'][1]['start'], null);
        $this->assertEquals($data['result'][1]['end'], null);
        $this->assertEquals($data['result'][1]['cut_off'], null);
        $this->assertEquals($data['result'][1]['is_graded'], 0);
        $this->assertEquals($data['result'][1]['is_grouped'], null);
        $this->assertEquals($data['result'][1]['has_all_student'], 1);
        $this->assertEquals($data['result'][1]['is_complete'], 0);
        $this->assertEquals($data['result'][1]['coefficient'], 1);
        $this->assertEquals(count($data['result'][1]['done']), 0);
        $this->assertEquals(count($data['result'][1]['rate']), 0);
        $this->assertEquals(count($data['result'][2]), 22);
        $this->assertEquals($data['result'][2]['is_started'], 0);
        $this->assertEquals($data['result'][2]['library'], null);
        $this->assertEquals($data['result'][2]['id'], 1);
        $this->assertEquals($data['result'][2]['course_id'], 1);
        $this->assertEquals($data['result'][2]['grading_policy_id'], null);
        $this->assertEquals($data['result'][2]['title'], "titl2e");
        $this->assertEquals($data['result'][2]['duration'], 234);
        $this->assertEquals($data['result'][2]['type'], "TXT");
        $this->assertEquals($data['result'][2]['set_id'], 1);
        $this->assertEquals($data['result'][2]['parent_id'], null);
        $this->assertEquals($data['result'][2]['order_id'], 3);
        $this->assertEquals($data['result'][2]['has_submission'], 1);
        $this->assertEquals($data['result'][2]['start'], null);
        $this->assertEquals($data['result'][2]['end'], null);
        $this->assertEquals($data['result'][2]['cut_off'], null);
        $this->assertEquals($data['result'][2]['is_graded'], 0);
        $this->assertEquals($data['result'][2]['is_grouped'], null);
        $this->assertEquals($data['result'][2]['has_all_student'], 1);
        $this->assertEquals($data['result'][2]['is_complete'], 0);
        $this->assertEquals($data['result'][2]['coefficient'], 1);
        $this->assertEquals(count($data['result'][2]['done']), 0);
        $this->assertEquals(count($data['result'][2]['rate']), 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testAddItem
     * @depends testAddItemTwo
     */
    public function testCanGetListItemuuo($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.update', ['id' => 3,'order_id' => 0,'parent_id' => 0]);
        
        $this->reset();
        
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.getList', array('course' => $course));
        // "\n3:2:1\n";
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals(count($data['result'][0]), 22);
        $this->assertEquals($data['result'][0]['is_started'], 0);
        $this->assertEquals($data['result'][0]['library'], null);
        $this->assertEquals($data['result'][0]['id'], 3);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['grading_policy_id'], null);
        $this->assertEquals($data['result'][0]['title'], "titl2e");
        $this->assertEquals($data['result'][0]['duration'], 123);
        $this->assertEquals($data['result'][0]['type'], "CP");
        $this->assertEquals($data['result'][0]['set_id'], null);
        $this->assertEquals($data['result'][0]['parent_id'], null);
        $this->assertEquals($data['result'][0]['order_id'], null);
        $this->assertEquals($data['result'][0]['has_submission'], 1);
        $this->assertEquals($data['result'][0]['start'], null);
        $this->assertEquals($data['result'][0]['end'], null);
        $this->assertEquals($data['result'][0]['cut_off'], null);
        $this->assertEquals($data['result'][0]['is_graded'], 0);
        $this->assertEquals($data['result'][0]['is_grouped'], null);
        $this->assertEquals($data['result'][0]['has_all_student'], 1);
        $this->assertEquals($data['result'][0]['is_complete'], 0);
        $this->assertEquals($data['result'][0]['coefficient'], 1);
        $this->assertEquals(count($data['result'][0]['done']), 0);
        $this->assertEquals(count($data['result'][0]['rate']), 0);
        $this->assertEquals(count($data['result'][1]), 22);
        $this->assertEquals($data['result'][1]['is_started'], 0);
        $this->assertEquals($data['result'][1]['library'], null);
        $this->assertEquals($data['result'][1]['id'], 2);
        $this->assertEquals($data['result'][1]['course_id'], 1);
        $this->assertEquals($data['result'][1]['grading_policy_id'], null);
        $this->assertEquals($data['result'][1]['title'], "titl2e");
        $this->assertEquals($data['result'][1]['duration'], 123);
        $this->assertEquals($data['result'][1]['type'], "HANGOUT");
        $this->assertEquals($data['result'][1]['set_id'], 1);
        $this->assertEquals($data['result'][1]['parent_id'], null);
        $this->assertEquals($data['result'][1]['order_id'], 3);
        $this->assertEquals($data['result'][1]['has_submission'], 1);
        $this->assertEquals($data['result'][1]['start'], null);
        $this->assertEquals($data['result'][1]['end'], null);
        $this->assertEquals($data['result'][1]['cut_off'], null);
        $this->assertEquals($data['result'][1]['is_graded'], 0);
        $this->assertEquals($data['result'][1]['is_grouped'], null);
        $this->assertEquals($data['result'][1]['has_all_student'], 1);
        $this->assertEquals($data['result'][1]['is_complete'], 1);
        $this->assertEquals($data['result'][1]['coefficient'], 1);
        $this->assertEquals(count($data['result'][1]['done']), 1);
        $this->assertEquals(count($data['result'][1]['done'][0]), 4);
        $this->assertEquals($data['result'][1]['done'][0]['id'], 1);
        $this->assertEquals($data['result'][1]['done'][0]['item_id'], 2);
        $this->assertEquals($data['result'][1]['done'][0]['target_id'], 1);
        $this->assertEquals($data['result'][1]['done'][0]['all'], 1);
        $this->assertEquals(count($data['result'][1]['rate']), 1);
        $this->assertEquals(count($data['result'][1]['rate'][0]), 5);
        $this->assertEquals($data['result'][1]['rate'][0]['id'], 1);
        $this->assertEquals($data['result'][1]['rate'][0]['item_id'], 2);
        $this->assertEquals($data['result'][1]['rate'][0]['inf'], 2016);
        $this->assertEquals($data['result'][1]['rate'][0]['sup'], 1);
        $this->assertEquals($data['result'][1]['rate'][0]['target_id'], 1);
        $this->assertEquals(count($data['result'][2]), 22);
        $this->assertEquals($data['result'][2]['is_started'], 0);
        $this->assertEquals($data['result'][2]['library'], null);
        $this->assertEquals($data['result'][2]['id'], 1);
        $this->assertEquals($data['result'][2]['course_id'], 1);
        $this->assertEquals($data['result'][2]['grading_policy_id'], null);
        $this->assertEquals($data['result'][2]['title'], "titl2e");
        $this->assertEquals($data['result'][2]['duration'], 234);
        $this->assertEquals($data['result'][2]['type'], "TXT");
        $this->assertEquals($data['result'][2]['set_id'], 1);
        $this->assertEquals($data['result'][2]['parent_id'], null);
        $this->assertEquals($data['result'][2]['order_id'], 2);
        $this->assertEquals($data['result'][2]['has_submission'], 1);
        $this->assertEquals($data['result'][2]['start'], null);
        $this->assertEquals($data['result'][2]['end'], null);
        $this->assertEquals($data['result'][2]['cut_off'], null);
        $this->assertEquals($data['result'][2]['is_graded'], 0);
        $this->assertEquals($data['result'][2]['is_grouped'], null);
        $this->assertEquals($data['result'][2]['has_all_student'], 1);
        $this->assertEquals($data['result'][2]['is_complete'], 0);
        $this->assertEquals($data['result'][2]['coefficient'], 1);
        $this->assertEquals(count($data['result'][2]['done']), 0);
        $this->assertEquals(count($data['result'][2]['rate']), 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testAddItem
     */
    public function testAddItemu($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', array('course' => $id,'duration' => 234,'title' => 'titl2e','describe' => 'description2','type' => 'CP','parent_id' => 3));
        
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
        
        $data = $this->jsonRpc('item.getList', array('course' => $course,'parent_id' => 3));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 22);
        $this->assertEquals($data['result'][0]['is_started'], 0);
        $this->assertEquals($data['result'][0]['library'], null);
        $this->assertEquals($data['result'][0]['id'], 4);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['grading_policy_id'], null);
        $this->assertEquals($data['result'][0]['title'], "titl2e");
        $this->assertEquals($data['result'][0]['duration'], 234);
        $this->assertEquals($data['result'][0]['type'], "CP");
        $this->assertEquals($data['result'][0]['set_id'], null);
        $this->assertEquals($data['result'][0]['parent_id'], 3);
        $this->assertEquals($data['result'][0]['order_id'], null);
        $this->assertEquals($data['result'][0]['has_submission'], 1);
        $this->assertEquals($data['result'][0]['start'], null);
        $this->assertEquals($data['result'][0]['end'], null);
        $this->assertEquals($data['result'][0]['cut_off'], null);
        $this->assertEquals($data['result'][0]['is_graded'], 0);
        $this->assertEquals(count($data['result'][0]['done']), 0);
        $this->assertEquals(count($data['result'][0]['rate']), 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     */
    public function testCanGetSubmission($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.get', ['item_id' => $item_id]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 8);
        $this->assertEquals($data['result']['nbr_comments'], 0);
        $this->assertEquals(count($data['result']['submission_user']), 2);
        $this->assertEquals(count($data['result']['submission_user'][0]), 6);
        $this->assertEquals(count($data['result']['submission_user'][0]['user']), 13);
        $this->assertEquals($data['result']['submission_user'][0]['user']['gender'], null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['contact_state'], 0);
        $this->assertEquals($data['result']['submission_user'][0]['user']['contacts_count'], 1);
        $this->assertEquals($data['result']['submission_user'][0]['user']['id'], 1);
        $this->assertEquals($data['result']['submission_user'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['submission_user'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['submission_user'][0]['user']['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['submission_user'][0]['user']['birth_date'], null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['position'], null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['school_id'], 3);
        $this->assertEquals($data['result']['submission_user'][0]['user']['interest'], null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['submission_user'][0]['user']['has_email_notifier'], 1);
        $this->assertEquals($data['result']['submission_user'][0]['submission_id'], 1);
        $this->assertEquals($data['result']['submission_user'][0]['user_id'], 1);
        $this->assertEquals($data['result']['submission_user'][0]['grade'], null);
        $this->assertEquals($data['result']['submission_user'][0]['submit_date'], null);
        $this->assertEquals($data['result']['submission_user'][0]['start_date'], null);
        $this->assertEquals(count($data['result']['submission_user'][1]), 6);
        $this->assertEquals(count($data['result']['submission_user'][1]['user']), 13);
        $this->assertEquals($data['result']['submission_user'][1]['user']['gender'], null);
        $this->assertEquals($data['result']['submission_user'][1]['user']['contact_state'], 0);
        $this->assertEquals($data['result']['submission_user'][1]['user']['contacts_count'], 0);
        $this->assertEquals($data['result']['submission_user'][1]['user']['id'], 4);
        $this->assertEquals($data['result']['submission_user'][1]['user']['firstname'], "Salim");
        $this->assertEquals($data['result']['submission_user'][1]['user']['lastname'], "Bendacha");
        $this->assertEquals($data['result']['submission_user'][1]['user']['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['submission_user'][1]['user']['birth_date'], null);
        $this->assertEquals($data['result']['submission_user'][1]['user']['position'], null);
        $this->assertEquals($data['result']['submission_user'][1]['user']['school_id'], 1);
        $this->assertEquals($data['result']['submission_user'][1]['user']['interest'], null);
        $this->assertEquals($data['result']['submission_user'][1]['user']['avatar'], null);
        $this->assertEquals($data['result']['submission_user'][1]['user']['has_email_notifier'], 1);
        $this->assertEquals($data['result']['submission_user'][1]['submission_id'], 1);
        $this->assertEquals($data['result']['submission_user'][1]['user_id'], 4);
        $this->assertEquals($data['result']['submission_user'][1]['grade'], null);
        $this->assertEquals($data['result']['submission_user'][1]['submit_date'], null);
        $this->assertEquals($data['result']['submission_user'][1]['start_date'], null);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['item_id'], 2);
        $this->assertEquals($data['result']['group_id'], null);
        $this->assertEquals($data['result']['group_name'], null);
        $this->assertEquals($data['result']['submit_date'], null);
        $this->assertEquals($data['result']['is_graded'], 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 17);
        $this->assertEquals(count($data['result']['program']), 2);
        $this->assertEquals($data['result']['program']['id'], 2);
        $this->assertEquals($data['result']['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['school']), 3);
        $this->assertEquals($data['result']['school']['id'], 3);
        $this->assertEquals($data['result']['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['school']['logo'], "token");
        $this->assertEquals(count($data['result']['instructor']), 1);
        $this->assertEquals(count($data['result']['instructor'][0]), 12);
        $this->assertEquals($data['result']['instructor'][0]['contacts_count'], 0);
        $this->assertEquals($data['result']['instructor'][0]['contact_state'], 0);
        $this->assertEquals(count($data['result']['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['instructor'][0]['nickname'], null);
        $this->assertEquals($data['result']['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['instructor'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['creator']), 5);
        $this->assertEquals(count($data['result']['creator']['school']), 3);
        $this->assertEquals($data['result']['creator']['school']['id'], 3);
        $this->assertEquals($data['result']['creator']['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['creator']['school']['logo'], "token");
        $this->assertEquals($data['result']['creator']['id'], 1);
        $this->assertEquals($data['result']['creator']['firstname'], "Paul");
        $this->assertEquals($data['result']['creator']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['creator']['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['title'], "IMERIR");
        $this->assertEquals($data['result']['abstract'], "un_token");
        $this->assertEquals($data['result']['description'], "description");
        $this->assertEquals($data['result']['objectives'], "objectives");
        $this->assertEquals($data['result']['teaching'], "teaching");
        $this->assertEquals($data['result']['attendance'], "attendance");
        $this->assertEquals($data['result']['duration'], 18);
        $this->assertEquals($data['result']['notes'], "notes");
        $this->assertEquals($data['result']['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['picture'], null);
        $this->assertEquals($data['result']['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['video_token'], "video_token");
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgram
     */
    public function testCourseGetListOne($program)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.getList', array('program' => $program));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 17);
        $this->assertEquals(count($data['result']['list'][0]['student']), 1);
        $this->assertEquals(count($data['result']['list'][0]['student'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['student'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['student'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['student'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['student'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['list'][0]['student'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['list'][0]['student'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['student'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['instructor']), 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "IMERIR");
        $this->assertEquals($data['result']['list'][0]['abstract'], "un_token");
        $this->assertEquals($data['result']['list'][0]['description'], "description");
        $this->assertEquals($data['result']['list'][0]['objectives'], "objectives");
        $this->assertEquals($data['result']['list'][0]['teaching'], "teaching");
        $this->assertEquals($data['result']['list'][0]['attendance'], "attendance");
        $this->assertEquals($data['result']['list'][0]['duration'], 18);
        $this->assertEquals($data['result']['list'][0]['school_id'], 3);
        $this->assertEquals($data['result']['list'][0]['notes'], "notes");
        $this->assertEquals($data['result']['list'][0]['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['list'][0]['picture'], null);
        $this->assertEquals($data['result']['list'][0]['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['list'][0]['video_token'], "video_token");
        $this->assertEquals($data['result']['list'][0]['program_id'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

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
        $data = $this->jsonRpc('thread.getList', array('course' => $course,'name' => 'un'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 9);
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
        $this->assertEquals($data['result']['list'][0]['item_id'], null);
        $this->assertEquals(! empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'], null);
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
        $this->assertEquals($data['result'], 6);
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
        $data = $this->jsonRpc('gradingpolicy.update', ['id' => $id,'name' => 'toto','grade' => 60]);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
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
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 7);
        $this->assertEquals($data['result'][0]['id'], 6);
        $this->assertEquals($data['result'][0]['name'], "toto");
        $this->assertEquals($data['result'][0]['grade'], 60);
        $this->assertEquals($data['result'][0]['type'], null);
        $this->assertEquals($data['result'][0]['tpl'], 0);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['mandatory'], 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     */
    public function testGetQuestionaire($item)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('questionnaire.getByItem', array('item' => $item));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 6);
        $this->assertEquals(count($data['result']['questions']), 9);
        $this->assertEquals(count($data['result']['questions'][0]), 3);
        $this->assertEquals(count($data['result']['questions'][0]['component']), 2);
        $this->assertEquals($data['result']['questions'][0]['component']['id'], 1);
        $this->assertEquals($data['result']['questions'][0]['component']['name'], "Multicultural awareness");
        $this->assertEquals(is_numeric($data['result']['questions'][0]['id']), true);
        $this->assertEquals(! empty($data['result']['questions'][0]['text']), true);
        $this->assertEquals(count($data['result']['questions'][1]), 3);
        $this->assertEquals(count($data['result']['questions'][1]['component']), 2);
        $this->assertEquals($data['result']['questions'][1]['component']['id'], 2);
        $this->assertEquals($data['result']['questions'][1]['component']['name'], "Multicultural sensitivity");
        $this->assertEquals(is_numeric($data['result']['questions'][1]['id']), true);
        $this->assertEquals(! empty($data['result']['questions'][1]['text']), true);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['item_id'], 2);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['max_duration'], 30);
        $this->assertEquals($data['result']['max_time'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['questions'];
    }

    /**
     * @depends testAddItem
     * @depends testGetQuestionaire
     */
    public function testGetQuestionaireAnswer($item, $questions)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('questionnaire.answer', array('item' => $item,'user' => 6,'question' => $questions[1]['id'],'scale' => 3));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     */
    public function testGetAnswer($item)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('questionnaire.getAnswer', array('item' => $item));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 8);
        $this->assertEquals(count($data['result']['answers']), 1);
        $this->assertEquals(count($data['result']['answers'][0]), 8);
        $this->assertEquals($data['result']['answers'][0]['id'], 1);
        $this->assertEquals($data['result']['answers'][0]['questionnaire_user_id'], 1);
        $this->assertEquals($data['result']['answers'][0]['questionnaire_question_id'], 2);
        $this->assertEquals(is_numeric($data['result']['answers'][0]['question_id']), true);
        $this->assertEquals($data['result']['answers'][0]['peer_id'], 6);
        $this->assertEquals($data['result']['answers'][0]['scale_id'], 3);
        $this->assertEquals($data['result']['answers'][0]['type'], "PEER");
        $this->assertEquals(! empty($data['result']['answers'][0]['created_date']), true);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['user_id'], 4);
        $this->assertEquals($data['result']['questionnaire_id'], 1);
        $this->assertEquals($data['result']['submission_id'], 1);
        $this->assertEquals($data['result']['state'], null);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['end_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     */
    public function testGetListAnswerItemprog($item)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('answer.getList', array('item' => $item));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 14);
        $this->assertEquals($data['result'][0]['course'], 1);
        $this->assertEquals($data['result'][0]['item'], 2);
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
     * @depends testAddItem
     */
    public function testGetListAnswerPeer($item)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('answer.getList', array('peer' => 6));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 14);
        $this->assertEquals($data['result'][0]['course'], 1);
        $this->assertEquals($data['result'][0]['item'], 2);
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
     * @depends testCanAddSchool
     */
    public function testDimension($school_id)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('dimension.getEqCq', array('school' => $school_id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 3);
        $this->assertEquals($data['result'][0]['scale'], 3.000000000000);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['name'], "CQ");
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddSchool
     */
    public function testComponent($school_id)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('component.getEqCq', array('school' => $school_id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['stats']), 1);
        $this->assertEquals(count($data['result']['stats'][0]), 4);
        $this->assertEquals($data['result']['stats'][0]['average'], 60.00000000);
        $this->assertEquals($data['result']['stats'][0]['id'], 2);
        $this->assertEquals($data['result']['stats'][0]['dimension'], 1);
        $this->assertEquals($data['result']['stats'][0]['label'], "Multicultural sensitivity");
        $this->assertEquals(count($data['result']['description']), 7);
        $this->assertEquals($data['result']['description']['avgage'], null);
        $this->assertEquals($data['result']['description']['maxage'], null);
        $this->assertEquals($data['result']['description']['minage'], null);
        $this->assertEquals($data['result']['description']['total'], 1);
        $this->assertEquals(count($data['result']['description']['genre']), 0);
        $this->assertEquals(count($data['result']['description']['nationality']), 0);
        $this->assertEquals(count($data['result']['description']['origin']), 0);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testSchoolEqCq()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('component.getListEqCq', array('schools' => [1]));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][1]), 2);
        $this->assertEquals(count($data['result'][1]['eqcq']), 2);
        $this->assertEquals(count($data['result'][1]['eqcq']['stats']), 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']), 7);
        $this->assertEquals($data['result'][1]['eqcq']['description']['avgage'], null);
        $this->assertEquals($data['result'][1]['eqcq']['description']['maxage'], null);
        $this->assertEquals($data['result'][1]['eqcq']['description']['minage'], null);
        $this->assertEquals($data['result'][1]['eqcq']['description']['total'], 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']['genre']), 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']['nationality']), 0);
        $this->assertEquals(count($data['result'][1]['eqcq']['description']['origin']), 0);
        $this->assertEquals(count($data['result'][1]['nbr']), 5);
        $this->assertEquals(count($data['result'][1]['nbr'][0]), 3);
        $this->assertEquals($data['result'][1]['nbr'][0]['role_id'], 1);
        $this->assertEquals($data['result'][1]['nbr'][0]['nb_user'], 1);
        $this->assertEquals($data['result'][1]['nbr'][0]['school_id'], 1);
        $this->assertEquals(count($data['result'][1]['nbr'][1]), 3);
        $this->assertEquals($data['result'][1]['nbr'][1]['role_id'], 2);
        $this->assertEquals($data['result'][1]['nbr'][1]['nb_user'], 2);
        $this->assertEquals($data['result'][1]['nbr'][1]['school_id'], 1);
        $this->assertEquals(count($data['result'][1]['nbr'][2]), 3);
        $this->assertEquals($data['result'][1]['nbr'][2]['role_id'], 3);
        $this->assertEquals($data['result'][1]['nbr'][2]['nb_user'], 1);
        $this->assertEquals($data['result'][1]['nbr'][2]['school_id'], 1);
        $this->assertEquals(count($data['result'][1]['nbr'][3]), 3);
        $this->assertEquals($data['result'][1]['nbr'][3]['role_id'], 4);
        $this->assertEquals($data['result'][1]['nbr'][3]['nb_user'], 1);
        $this->assertEquals($data['result'][1]['nbr'][3]['school_id'], 1);
        $this->assertEquals(count($data['result'][1]['nbr'][4]), 3);
        $this->assertEquals($data['result'][1]['nbr'][4]['role_id'], 5);
        $this->assertEquals($data['result'][1]['nbr'][4]['nb_user'], 1);
        $this->assertEquals($data['result'][1]['nbr'][4]['school_id'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddSchool
     */
    public function testSchoolEqCq2($school_id)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('component.getListEqCq', array('schools' => [$school_id]));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][$school_id]), 2);
        $this->assertEquals(count($data['result'][$school_id]['eqcq']), 2);
        $this->assertEquals(count($data['result'][$school_id]['eqcq']['stats']), 1);
        $this->assertEquals(count($data['result'][$school_id]['eqcq']['stats'][0]), 4);
        $this->assertEquals($data['result'][$school_id]['eqcq']['stats'][0]['average'], 60.00000000);
        $this->assertEquals($data['result'][$school_id]['eqcq']['stats'][0]['id'], 2);
        $this->assertEquals($data['result'][$school_id]['eqcq']['stats'][0]['dimension'], 1);
        $this->assertEquals($data['result'][$school_id]['eqcq']['stats'][0]['label'], "Multicultural sensitivity");
        $this->assertEquals(count($data['result'][$school_id]['eqcq']['description']), 7);
        $this->assertEquals($data['result'][$school_id]['eqcq']['description']['avgage'], null);
        $this->assertEquals($data['result'][$school_id]['eqcq']['description']['maxage'], null);
        $this->assertEquals($data['result'][$school_id]['eqcq']['description']['minage'], null);
        $this->assertEquals($data['result'][$school_id]['eqcq']['description']['total'], 1);
        $this->assertEquals(count($data['result'][$school_id]['eqcq']['description']['genre']), 0);
        $this->assertEquals(count($data['result'][$school_id]['eqcq']['description']['nationality']), 0);
        $this->assertEquals(count($data['result'][$school_id]['eqcq']['description']['origin']), 0);
        $this->assertEquals(count($data['result'][$school_id]['nbr']), 1);
        $this->assertEquals(count($data['result'][$school_id]['nbr'][0]), 3);
        $this->assertEquals($data['result'][$school_id]['nbr'][0]['role_id'], 1);
        $this->assertEquals($data['result'][$school_id]['nbr'][0]['nb_user'], 1);
        $this->assertEquals($data['result'][$school_id]['nbr'][0]['school_id'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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

    public function testProgramGetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('program.getList', []);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 9);
        $this->assertEquals($data['result']['list'][0]['student'] , 1);
        $this->assertEquals($data['result']['list'][0]['instructor'] , 1);
        $this->assertEquals($data['result']['list'][0]['course'] , 1);
        $this->assertEquals($data['result']['list'][0]['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['name'] , "program name upd");
        $this->assertEquals($data['result']['list'][0]['school_id'] , 3);
        $this->assertEquals($data['result']['list'][0]['level'] , "mba");
        $this->assertEquals($data['result']['list'][0]['sis'] , "sisupd");
        $this->assertEquals($data['result']['list'][0]['year'] , null);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testUserGetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('user.getList', array('type' => [],'search' => 'Bo'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 14);
        $this->assertEquals($data['result']['list'][0]['contacts_count'], 1);
        $this->assertEquals($data['result']['list'][0]['contact_state'], 0);
        $this->assertEquals(count($data['result']['list'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['school']['background'], 'background');
        $this->assertEquals($data['result']['list'][0]['school']['id'], 3);
        $this->assertEquals($data['result']['list'][0]['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['list'][0]['school']['short_name'], "IUM");
        $this->assertEquals($data['result']['list'][0]['school']['logo'], "token");
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['list'][0]['program']), 1);
        $this->assertEquals(count($data['result']['list'][0]['program'][0]), 5);
        $this->assertEquals($data['result']['list'][0]['program'][0]['id'], 2);
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
        $this->assertEquals(count($data['result']['list'][0]), 17);
        $this->assertEquals(count($data['result']['list'][0]['student']), 1);
        $this->assertEquals(count($data['result']['list'][0]['student'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['student'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['student'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['student'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['student'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['list'][0]['student'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['list'][0]['student'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['student'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['instructor']), 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "IMERIR");
        $this->assertEquals($data['result']['list'][0]['abstract'], "un_token");
        $this->assertEquals($data['result']['list'][0]['description'], "description");
        $this->assertEquals($data['result']['list'][0]['objectives'], "objectives");
        $this->assertEquals($data['result']['list'][0]['teaching'], "teaching");
        $this->assertEquals($data['result']['list'][0]['attendance'], "attendance");
        $this->assertEquals($data['result']['list'][0]['duration'], 18);
        $this->assertEquals($data['result']['list'][0]['school_id'], 3);
        $this->assertEquals($data['result']['list'][0]['notes'], "notes");
        $this->assertEquals($data['result']['list'][0]['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['list'][0]['picture'], null);
        $this->assertEquals($data['result']['list'][0]['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['list'][0]['video_token'], "video_token");
        $this->assertEquals($data['result']['list'][0]['program_id'], 2);
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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals(count($data['result']['d']), 2);
        $this->assertEquals($data['result']['d']['nbr_session'], null);
        $this->assertEquals($data['result']['d']['avg'], null);
        $this->assertEquals(count($data['result']['w']), 2);
        $this->assertEquals($data['result']['w']['nbr_session'], null);
        $this->assertEquals($data['result']['w']['avg'], null);
        $this->assertEquals(count($data['result']['m']), 2);
        $this->assertEquals($data['result']['m']['nbr_session'], null);
        $this->assertEquals($data['result']['m']['avg'], null);
        $this->assertEquals(count($data['result']['a']), 2);
        $this->assertEquals($data['result']['a']['nbr_session'], null);
        $this->assertEquals($data['result']['a']['avg'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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
        $this->assertEquals(count($data['result'][0]), 7);
        $this->assertEquals(count($data['result'][0]['thread']), 2);
        $this->assertEquals($data['result'][0]['thread']['id'], 1);
        $this->assertEquals($data['result'][0]['thread']['course_id'], 1);
        $this->assertEquals(count($data['result'][0]['parent']), 3);
        $this->assertEquals(count($data['result'][0]['parent']['user']), 4);
        $this->assertEquals($data['result'][0]['parent']['user']['id'], null);
        $this->assertEquals($data['result'][0]['parent']['user']['firstname'], null);
        $this->assertEquals($data['result'][0]['parent']['user']['lastname'], null);
        $this->assertEquals($data['result'][0]['parent']['user']['avatar'], null);
        $this->assertEquals($data['result'][0]['parent']['id'], null);
        $this->assertEquals($data['result'][0]['parent']['deleted_date'], null);
        $this->assertEquals(count($data['result'][0]['user']), 5);
        $this->assertEquals(count($data['result'][0]['user']['roles']), 1);
        $this->assertEquals($data['result'][0]['user']['roles'][0], "admin");
        $this->assertEquals($data['result'][0]['user']['id'], 2);
        $this->assertEquals($data['result'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result'][0]['user']['avatar'], null);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['message'], "un super message update");
        $this->assertEquals($data['result'][0]['parent_id'], null);
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals(count($data['result'][1]), 7);
        $this->assertEquals(count($data['result'][1]['thread']), 2);
        $this->assertEquals($data['result'][1]['thread']['id'], 1);
        $this->assertEquals($data['result'][1]['thread']['course_id'], 1);
        $this->assertEquals(count($data['result'][1]['parent']), 3);
        $this->assertEquals(count($data['result'][1]['parent']['user']), 4);
        $this->assertEquals($data['result'][1]['parent']['user']['id'], null);
        $this->assertEquals($data['result'][1]['parent']['user']['firstname'], null);
        $this->assertEquals($data['result'][1]['parent']['user']['lastname'], null);
        $this->assertEquals($data['result'][1]['parent']['user']['avatar'], null);
        $this->assertEquals($data['result'][1]['parent']['id'], null);
        $this->assertEquals($data['result'][1]['parent']['deleted_date'], null);
        $this->assertEquals(count($data['result'][1]['user']), 5);
        $this->assertEquals(count($data['result'][1]['user']['roles']), 1);
        $this->assertEquals($data['result'][1]['user']['roles'][0], "super_admin");
        $this->assertEquals($data['result'][1]['user']['id'], 1);
        $this->assertEquals($data['result'][1]['user']['firstname'], "Paul");
        $this->assertEquals($data['result'][1]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result'][1]['user']['avatar'], null);
        $this->assertEquals($data['result'][1]['id'], 1);
        $this->assertEquals($data['result'][1]['message'], "super messge");
        $this->assertEquals($data['result'][1]['parent_id'], null);
        $this->assertEquals(! empty($data['result'][1]['created_date']), true);
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
        $this->assertEquals(count($data['result']['list'][0]), 9);
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
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0], "super_admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "un titre update");
        $this->assertEquals($data['result']['list'][0]['item_id'], null);
        $this->assertEquals(! empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['list'][0]['id'];
    }

    /**
     * @depends testCanAddSchool
     */
    public function testGetNbrMessageThread($school_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.getNbrMessage', array('school' => $school_id,'day' => 30));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals($data['result']['d'], 2);
        $this->assertEquals($data['result']['w'], 2);
        $this->assertEquals($data['result']['m'], 2);
        $this->assertEquals($data['result']['a'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testGetThreadTwo
     */
    public function testGetThreadOnlyOne($thread)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.get', array('id' => $thread));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 9);
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
        $this->assertEquals($data['result']['user']['roles'][0], "super_admin");
        $this->assertEquals($data['result']['user']['id'], 1);
        $this->assertEquals($data['result']['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['title'], "un titre update");
        $this->assertEquals($data['result']['item_id'], null);
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddItemDocument($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', array('course' => $id,'duration' => 234,'title' => 'un document','describe' => 'super description','type' => 'DOC','data' => ['link' => 'link','token' => 'token','title' => 'title']));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 5);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }
    
    // DELETE
    
    /**
     * @depends testAddCourse
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
        $this->assertEquals(count($data['result']['list'][0]), 17);
        $this->assertEquals(count($data['result']['list'][0]['student']), 1);
        $this->assertEquals(count($data['result']['list'][0]['student'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['student'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['student'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['student'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['student'][0]['firstname'], "Salim");
        $this->assertEquals($data['result']['list'][0]['student'][0]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['list'][0]['student'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['student'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['student'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['instructor']), 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['title'], "IMERIR");
        $this->assertEquals($data['result']['list'][0]['abstract'], "un_token");
        $this->assertEquals($data['result']['list'][0]['description'], "description");
        $this->assertEquals($data['result']['list'][0]['objectives'], "objectives");
        $this->assertEquals($data['result']['list'][0]['teaching'], "teaching");
        $this->assertEquals($data['result']['list'][0]['attendance'], "attendance");
        $this->assertEquals($data['result']['list'][0]['duration'], 18);
        $this->assertEquals($data['result']['list'][0]['school_id'], 3);
        $this->assertEquals($data['result']['list'][0]['notes'], "notes");
        $this->assertEquals($data['result']['list'][0]['learning_outcomes'], "learning_outcomes");
        $this->assertEquals($data['result']['list'][0]['picture'], null);
        $this->assertEquals($data['result']['list'][0]['video_link'], "http://google.fr");
        $this->assertEquals($data['result']['list'][0]['video_token'], "video_token");
        $this->assertEquals($data['result']['list'][0]['program_id'], 2);
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
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('event.getList', array());
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 5);
        $this->assertEquals(count($data['result']['list'][0]) , 10);
        $this->assertEquals($data['result']['list'][0]['view_date'] , null);
        $this->assertEquals($data['result']['list'][0]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][0]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][0]['read_date'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 8);
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
        $this->assertEquals($data['result']['list'][0]['source']['data']['user_roles'][0] , "admin");
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
        $this->assertEquals($data['result']['list'][1]['id'] , 7);
        $this->assertEquals(count($data['result']['list'][1]['source']) , 3);
        $this->assertEquals($data['result']['list'][1]['source']['id'] , 3);
        $this->assertEquals($data['result']['list'][1]['source']['name'] , "user");
        $this->assertEquals(count($data['result']['list'][1]['source']['data']) , 8);
        $this->assertEquals($data['result']['list'][1]['source']['data']['firstname'] , "Christophe");
        $this->assertEquals($data['result']['list'][1]['source']['data']['email'] , "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['source']['data']['lastname'] , "Robert");
        $this->assertEquals($data['result']['list'][1]['source']['data']['gender'] , null);
        $this->assertEquals($data['result']['list'][1]['source']['data']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['list'][1]['source']['data']['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][1]['source']['data']['school']) , 5);
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['background'] , null);
        $this->assertEquals($data['result']['list'][1]['source']['data']['school']['name'] , "Morbi Corporation");
        $this->assertEquals(count($data['result']['list'][1]['source']['data']['user_roles']) , 1);
        $this->assertEquals($data['result']['list'][1]['source']['data']['user_roles'][0] , "academic");
        $this->assertEquals(!empty($data['result']['list'][1]['date']) , true);
        $this->assertEquals($data['result']['list'][1]['event'] , "task.shared");
        $this->assertEquals(count($data['result']['list'][1]['object']) , 3);
        $this->assertEquals($data['result']['list'][1]['object']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['object']['name'] , "task");
        $this->assertEquals(count($data['result']['list'][1]['object']['data']) , 6);
        $this->assertEquals($data['result']['list'][1]['object']['data']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['object']['data']['title'] , "TEST 2");
        $this->assertEquals($data['result']['list'][1]['object']['data']['content'] , null);
        $this->assertEquals(!empty($data['result']['list'][1]['object']['data']['start']) , true);
        $this->assertEquals(!empty($data['result']['list'][1]['object']['data']['end']) , true);
        $this->assertEquals($data['result']['list'][1]['object']['data']['creator_id'] , 3);
        $this->assertEquals(count($data['result']['list'][1]['comment']) , 0);
        $this->assertEquals(count($data['result']['list'][2]) , 10);
        $this->assertEquals($data['result']['list'][2]['view_date'] , null);
        $this->assertEquals($data['result']['list'][2]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][2]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][2]['read_date'] , null);
        $this->assertEquals($data['result']['list'][2]['id'] , 5);
        $this->assertEquals(count($data['result']['list'][2]['source']) , 0);
        $this->assertEquals(!empty($data['result']['list'][2]['date']) , true);
        $this->assertEquals($data['result']['list'][2]['event'] , "eqcq.available");
        $this->assertEquals(count($data['result']['list'][2]['object']) , 3);
        $this->assertEquals($data['result']['list'][2]['object']['id'] , 1);
        $this->assertEquals($data['result']['list'][2]['object']['name'] , "submission");
        $this->assertEquals(count($data['result']['list'][2]['object']['data']) , 2);
        $this->assertEquals(count($data['result']['list'][2]['object']['data']['item']) , 3);
        $this->assertEquals($data['result']['list'][2]['object']['data']['item']['id'] , 2);
        $this->assertEquals($data['result']['list'][2]['object']['data']['item']['title'] , "titl2e");
        $this->assertEquals($data['result']['list'][2]['object']['data']['item']['type'] , "HANGOUT");
        $this->assertEquals(count($data['result']['list'][2]['object']['data']['users']) , 2);
        $this->assertEquals(count($data['result']['list'][2]['object']['data']['users'][0]) , 3);
        $this->assertEquals($data['result']['list'][2]['object']['data']['users'][0]['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][2]['object']['data']['users'][0]['lastname'] , "Boussekey");
        $this->assertEquals(count($data['result']['list'][2]['object']['data']['users'][0]['avatar']) , 0);
        $this->assertEquals(count($data['result']['list'][2]['object']['data']['users'][1]) , 3);
        $this->assertEquals($data['result']['list'][2]['object']['data']['users'][1]['firstname'] , "Salim");
        $this->assertEquals($data['result']['list'][2]['object']['data']['users'][1]['lastname'] , "Bendacha");
        $this->assertEquals(count($data['result']['list'][2]['object']['data']['users'][1]['avatar']) , 0);
        $this->assertEquals(count($data['result']['list'][2]['comment']) , 0);
        $this->assertEquals(count($data['result']['list'][3]) , 10);
        $this->assertEquals($data['result']['list'][3]['view_date'] , null);
        $this->assertEquals($data['result']['list'][3]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][3]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][3]['read_date'] , null);
        $this->assertEquals($data['result']['list'][3]['id'] , 4);
        $this->assertEquals(count($data['result']['list'][3]['source']) , 3);
        $this->assertEquals($data['result']['list'][3]['source']['id'] , 1);
        $this->assertEquals($data['result']['list'][3]['source']['name'] , "user");
        $this->assertEquals(count($data['result']['list'][3]['source']['data']) , 8);
        $this->assertEquals($data['result']['list'][3]['source']['data']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][3]['source']['data']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][3]['source']['data']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][3]['source']['data']['gender'] , null);
        $this->assertEquals($data['result']['list'][3]['source']['data']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['list'][3]['source']['data']['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][3]['source']['data']['school']) , 5);
        $this->assertEquals($data['result']['list'][3]['source']['data']['school']['id'] , 3);
        $this->assertEquals($data['result']['list'][3]['source']['data']['school']['short_name'] , "IUM");
        $this->assertEquals($data['result']['list'][3]['source']['data']['school']['logo'] , "token");
        $this->assertEquals($data['result']['list'][3]['source']['data']['school']['background'] , "background");
        $this->assertEquals($data['result']['list'][3]['source']['data']['school']['name'] , "université de monaco");
        $this->assertEquals(count($data['result']['list'][3]['source']['data']['user_roles']) , 1);
        $this->assertEquals($data['result']['list'][3]['source']['data']['user_roles'][0] , "super_admin");
        $this->assertEquals(!empty($data['result']['list'][3]['date']) , true);
        $this->assertEquals($data['result']['list'][3]['event'] , "thread.new");
        $this->assertEquals(count($data['result']['list'][3]['object']) , 3);
        $this->assertEquals($data['result']['list'][3]['object']['id'] , 1);
        $this->assertEquals($data['result']['list'][3]['object']['name'] , "thread");
        $this->assertEquals(count($data['result']['list'][3]['object']['data']) , 3);
        $this->assertEquals($data['result']['list'][3]['object']['data']['id'] , 1);
        $this->assertEquals($data['result']['list'][3]['object']['data']['title'] , "un titre");
        $this->assertEquals(count($data['result']['list'][3]['object']['data']['course']) , 2);
        $this->assertEquals($data['result']['list'][3]['object']['data']['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][3]['object']['data']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][3]['comment']) , 0);
        $this->assertEquals(count($data['result']['list'][4]) , 10);
        $this->assertEquals($data['result']['list'][4]['view_date'] , null);
        $this->assertEquals($data['result']['list'][4]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][4]['nb_like'] , 0);
        $this->assertEquals($data['result']['list'][4]['read_date'] , null);
        $this->assertEquals($data['result']['list'][4]['id'] , 3);
        $this->assertEquals(count($data['result']['list'][4]['source']) , 3);
        $this->assertEquals($data['result']['list'][4]['source']['id'] , 1);
        $this->assertEquals($data['result']['list'][4]['source']['name'] , "user");
        $this->assertEquals(count($data['result']['list'][4]['source']['data']) , 8);
        $this->assertEquals($data['result']['list'][4]['source']['data']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][4]['source']['data']['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][4]['source']['data']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][4]['source']['data']['gender'] , null);
        $this->assertEquals($data['result']['list'][4]['source']['data']['has_email_notifier'] , 1);
        $this->assertEquals($data['result']['list'][4]['source']['data']['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][4]['source']['data']['school']) , 5);
        $this->assertEquals($data['result']['list'][4]['source']['data']['school']['id'] , 3);
        $this->assertEquals($data['result']['list'][4]['source']['data']['school']['short_name'] , "IUM");
        $this->assertEquals($data['result']['list'][4]['source']['data']['school']['logo'] , "token");
        $this->assertEquals($data['result']['list'][4]['source']['data']['school']['background'] , "background");
        $this->assertEquals($data['result']['list'][4]['source']['data']['school']['name'] , "université de monaco");
        $this->assertEquals(count($data['result']['list'][4]['source']['data']['user_roles']) , 1);
        $this->assertEquals($data['result']['list'][4]['source']['data']['user_roles'][0] , "super_admin");
        $this->assertEquals(!empty($data['result']['list'][4]['date']) , true);
        $this->assertEquals($data['result']['list'][4]['event'] , "submission.new");
        $this->assertEquals(count($data['result']['list'][4]['object']) , 3);
        $this->assertEquals($data['result']['list'][4]['object']['id'] , 1);
        $this->assertEquals($data['result']['list'][4]['object']['name'] , "submission");
        $this->assertEquals(count($data['result']['list'][4]['object']['data']) , 1);
        $this->assertEquals(count($data['result']['list'][4]['object']['data']['item']) , 6);
        $this->assertEquals($data['result']['list'][4]['object']['data']['item']['id'] , 2);
        $this->assertEquals($data['result']['list'][4]['object']['data']['item']['title'] , "title");
        $this->assertEquals($data['result']['list'][4]['object']['data']['item']['type'] , "HANGOUT");
        $this->assertEquals($data['result']['list'][4]['object']['data']['item']['duration'] , null);
        $this->assertEquals(count($data['result']['list'][4]['object']['data']['item']['start']) , 0);
        $this->assertEquals(count($data['result']['list'][4]['object']['data']['item']['cut_off']) , 0);
        $this->assertEquals(count($data['result']['list'][4]['comment']) , 0);
        $this->assertEquals($data['result']['count'] , 5);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testEventRead()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('eventuser.read', array('ids' => [1,2,4,5,6]));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 5);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }


    public function testEventView()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('eventuser.view', array('id' => 4));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
}
