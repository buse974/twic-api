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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 14);
        $this->assertEquals(count($data['result']['address']), 14);
        $this->assertEquals(count($data['result']['address']['city']), 2);
        $this->assertEquals($data['result']['address']['city']['id'], 1);
        $this->assertEquals($data['result']['address']['city']['name'], "Monaco");
        $this->assertEquals(count($data['result']['address']['division']), 2);
        $this->assertEquals($data['result']['address']['division']['id'], null);
        $this->assertEquals($data['result']['address']['division']['name'], null);
        $this->assertEquals(count($data['result']['address']['country']), 3);
        $this->assertEquals($data['result']['address']['country']['id'], 1);
        $this->assertEquals($data['result']['address']['country']['short_name'], null);
        $this->assertEquals($data['result']['address']['country']['name'], "Monaco");
        $this->assertEquals($data['result']['address']['id'], 1);
        $this->assertEquals($data['result']['address']['street_no'], 12);
        $this->assertEquals($data['result']['address']['street_type'], "rue");
        $this->assertEquals($data['result']['address']['street_name'], "du stade");
        $this->assertEquals($data['result']['address']['longitude'], 11.6412744);
        $this->assertEquals($data['result']['address']['latitude'], 48.142066700000001);
        $this->assertEquals($data['result']['address']['door'], null);
        $this->assertEquals($data['result']['address']['building'], null);
        $this->assertEquals($data['result']['address']['apartment'], null);
        $this->assertEquals($data['result']['address']['floor'], null);
        $this->assertEquals($data['result']['address']['timezone'], "Europe/Berlin");
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
        $this->assertEquals($data['result']['id'], 2);
        $this->assertEquals($data['result']['name'], "université de monaco");
        $this->assertEquals($data['result']['next_name'], "buisness school");
        $this->assertEquals($data['result']['short_name'], "IUM");
        $this->assertEquals($data['result']['logo'], "token");
        $this->assertEquals($data['result']['describe'], "une description");
        $this->assertEquals($data['result']['website'], "www.ium.com");
        $this->assertEquals($data['result']['programme'], "super programme");
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
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
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
        $this->assertEquals(count($data['result']), 19);
        $this->assertEquals(count($data['result']['program']), 2);
        $this->assertEquals($data['result']['program']['id'], 1);
        $this->assertEquals($data['result']['program']['name'], "program name upd");
        $this->assertEquals(count($data['result']['school']), 3);
        $this->assertEquals($data['result']['school']['id'], 2);
        $this->assertEquals($data['result']['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['school']['logo'], "token");
        $this->assertEquals(count($data['result']['creator']), 5);
        $this->assertEquals(count($data['result']['creator']['school']), 3);
        $this->assertEquals($data['result']['creator']['school']['id'], 2);
        $this->assertEquals($data['result']['creator']['school']['name'], "université de monaco");
        $this->assertEquals($data['result']['creator']['school']['logo'], "token");
        $this->assertEquals($data['result']['creator']['id'], 1);
        $this->assertEquals($data['result']['creator']['firstname'], "Paul");
        $this->assertEquals($data['result']['creator']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['creator']['email'], "pboussekey@thestudnet.com");
        $this->assertEquals(count($data['result']['material_document']), 1);
        $this->assertEquals(count($data['result']['material_document'][0]), 12);
        $this->assertEquals($data['result']['material_document'][0]['id'], 1);
        $this->assertEquals($data['result']['material_document'][0]['course_id'], 1);
        $this->assertEquals($data['result']['material_document'][0]['type'], "link");
        $this->assertEquals($data['result']['material_document'][0]['title'], "title");
        $this->assertEquals($data['result']['material_document'][0]['author'], "author");
        $this->assertEquals($data['result']['material_document'][0]['link'], "link");
        $this->assertEquals($data['result']['material_document'][0]['source'], "source");
        $this->assertEquals($data['result']['material_document'][0]['token'], "token");
        $this->assertEquals($data['result']['material_document'][0]['date'], "2011-01-01");
        $this->assertEquals(! empty($data['result']['material_document'][0]['created_date']), true);
        $this->assertEquals($data['result']['material_document'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['material_document'][0]['updated_date'], null);
        $this->assertEquals(count($data['result']['grading']), 12);
        $this->assertEquals(count($data['result']['grading'][0]), 8);
        $this->assertEquals($data['result']['grading'][0]['id'], 13);
        $this->assertEquals($data['result']['grading'][0]['letter'], "A");
        $this->assertEquals($data['result']['grading'][0]['min'], 95);
        $this->assertEquals($data['result']['grading'][0]['max'], 100);
        $this->assertEquals($data['result']['grading'][0]['grade'], 4);
        $this->assertEquals($data['result']['grading'][0]['description'], "Outstanding performance, works shows superior command of the subject.");
        $this->assertEquals($data['result']['grading'][0]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][0]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][1]), 8);
        $this->assertEquals($data['result']['grading'][1]['id'], 14);
        $this->assertEquals($data['result']['grading'][1]['letter'], "A-");
        $this->assertEquals($data['result']['grading'][1]['min'], 90);
        $this->assertEquals($data['result']['grading'][1]['max'], 94);
        $this->assertEquals($data['result']['grading'][1]['grade'], 3.7);
        $this->assertEquals($data['result']['grading'][1]['description'], "Very good work showing understanding and mastery of all concepts.");
        $this->assertEquals($data['result']['grading'][1]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][1]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][2]), 8);
        $this->assertEquals($data['result']['grading'][2]['id'], 15);
        $this->assertEquals($data['result']['grading'][2]['letter'], "B+");
        $this->assertEquals($data['result']['grading'][2]['min'], 87);
        $this->assertEquals($data['result']['grading'][2]['max'], 89);
        $this->assertEquals($data['result']['grading'][2]['grade'], 3.3);
        $this->assertEquals($data['result']['grading'][2]['description'], "Good work showing understanding and mastery of most concepts.");
        $this->assertEquals($data['result']['grading'][2]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][2]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][3]), 8);
        $this->assertEquals($data['result']['grading'][3]['id'], 16);
        $this->assertEquals($data['result']['grading'][3]['letter'], "B");
        $this->assertEquals($data['result']['grading'][3]['min'], 83);
        $this->assertEquals($data['result']['grading'][3]['max'], 86);
        $this->assertEquals($data['result']['grading'][3]['grade'], 3);
        $this->assertEquals($data['result']['grading'][3]['description'], "Fairly good work that shows an understanding of the main concepts.");
        $this->assertEquals($data['result']['grading'][3]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][3]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][4]), 8);
        $this->assertEquals($data['result']['grading'][4]['id'], 17);
        $this->assertEquals($data['result']['grading'][4]['letter'], "B-");
        $this->assertEquals($data['result']['grading'][4]['min'], 80);
        $this->assertEquals($data['result']['grading'][4]['max'], 82);
        $this->assertEquals($data['result']['grading'][4]['grade'], 2.7);
        $this->assertEquals($data['result']['grading'][4]['description'], "Fairly good work showing understanding of several important concepts.");
        $this->assertEquals($data['result']['grading'][4]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][4]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][5]), 8);
        $this->assertEquals($data['result']['grading'][5]['id'], 18);
        $this->assertEquals($data['result']['grading'][5]['letter'], "C+");
        $this->assertEquals($data['result']['grading'][5]['min'], 77);
        $this->assertEquals($data['result']['grading'][5]['max'], 79);
        $this->assertEquals($data['result']['grading'][5]['grade'], 2.3);
        $this->assertEquals($data['result']['grading'][5]['description'], "Uneven understanding of the concepts with occasional lack of clarity");
        $this->assertEquals($data['result']['grading'][5]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][5]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][6]), 8);
        $this->assertEquals($data['result']['grading'][6]['id'], 19);
        $this->assertEquals($data['result']['grading'][6]['letter'], "C");
        $this->assertEquals($data['result']['grading'][6]['min'], 73);
        $this->assertEquals($data['result']['grading'][6]['max'], 76);
        $this->assertEquals($data['result']['grading'][6]['grade'], 2);
        $this->assertEquals($data['result']['grading'][6]['description'], "Work that barely meets modest expectations for the class");
        $this->assertEquals($data['result']['grading'][6]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][6]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][7]), 8);
        $this->assertEquals($data['result']['grading'][7]['id'], 20);
        $this->assertEquals($data['result']['grading'][7]['letter'], "C-");
        $this->assertEquals($data['result']['grading'][7]['min'], 70);
        $this->assertEquals($data['result']['grading'][7]['max'], 72);
        $this->assertEquals($data['result']['grading'][7]['grade'], 1.7);
        $this->assertEquals($data['result']['grading'][7]['description'], "Work that is below modest expectations for the class");
        $this->assertEquals($data['result']['grading'][7]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][7]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][8]), 8);
        $this->assertEquals($data['result']['grading'][8]['id'], 21);
        $this->assertEquals($data['result']['grading'][8]['letter'], "D+");
        $this->assertEquals($data['result']['grading'][8]['min'], 67);
        $this->assertEquals($data['result']['grading'][8]['max'], 69);
        $this->assertEquals($data['result']['grading'][8]['grade'], 1.3);
        $this->assertEquals($data['result']['grading'][8]['description'], "Poor performance with lack of understanding of several important concepts");
        $this->assertEquals($data['result']['grading'][8]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][8]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][9]), 8);
        $this->assertEquals($data['result']['grading'][9]['id'], 22);
        $this->assertEquals($data['result']['grading'][9]['letter'], "D");
        $this->assertEquals($data['result']['grading'][9]['min'], 63);
        $this->assertEquals($data['result']['grading'][9]['max'], 66);
        $this->assertEquals($data['result']['grading'][9]['grade'], 1);
        $this->assertEquals($data['result']['grading'][9]['description'], "Work that is marginally above the minimum expectations for the class");
        $this->assertEquals($data['result']['grading'][9]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][9]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][10]), 8);
        $this->assertEquals($data['result']['grading'][10]['id'], 23);
        $this->assertEquals($data['result']['grading'][10]['letter'], "D-");
        $this->assertEquals($data['result']['grading'][10]['min'], 60);
        $this->assertEquals($data['result']['grading'][10]['max'], 62);
        $this->assertEquals($data['result']['grading'][10]['grade'], 0.7);
        $this->assertEquals($data['result']['grading'][10]['description'], "Work that barely meets the minimum expectations for the class");
        $this->assertEquals($data['result']['grading'][10]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][10]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading'][11]), 8);
        $this->assertEquals($data['result']['grading'][11]['id'], 24);
        $this->assertEquals($data['result']['grading'][11]['letter'], "F");
        $this->assertEquals($data['result']['grading'][11]['min'], 0);
        $this->assertEquals($data['result']['grading'][11]['max'], 59);
        $this->assertEquals($data['result']['grading'][11]['grade'], 0);
        $this->assertEquals($data['result']['grading'][11]['description'], "Work does not meet the minimum expectations for the class");
        $this->assertEquals($data['result']['grading'][11]['tpl'], 0);
        $this->assertEquals($data['result']['grading'][11]['school_id'], 2);
        $this->assertEquals(count($data['result']['grading_policy']), 5);
        $this->assertEquals(count($data['result']['grading_policy'][0]), 7);
        $this->assertEquals($data['result']['grading_policy'][0]['id'], 6);
        $this->assertEquals($data['result']['grading_policy'][0]['name'], "Individual assignment");
        $this->assertEquals($data['result']['grading_policy'][0]['grade'], 20);
        $this->assertEquals($data['result']['grading_policy'][0]['type'], "IA");
        $this->assertEquals($data['result']['grading_policy'][0]['tpl'], 0);
        $this->assertEquals($data['result']['grading_policy'][0]['course_id'], 1);
        $this->assertEquals($data['result']['grading_policy'][0]['mandatory'], 1);
        $this->assertEquals(count($data['result']['grading_policy'][1]), 7);
        $this->assertEquals($data['result']['grading_policy'][1]['id'], 7);
        $this->assertEquals($data['result']['grading_policy'][1]['name'], "Group work");
        $this->assertEquals($data['result']['grading_policy'][1]['grade'], 20);
        $this->assertEquals($data['result']['grading_policy'][1]['type'], "WG");
        $this->assertEquals($data['result']['grading_policy'][1]['tpl'], 0);
        $this->assertEquals($data['result']['grading_policy'][1]['course_id'], 1);
        $this->assertEquals($data['result']['grading_policy'][1]['mandatory'], 1);
        $this->assertEquals(count($data['result']['grading_policy'][2]), 7);
        $this->assertEquals($data['result']['grading_policy'][2]['id'], 8);
        $this->assertEquals($data['result']['grading_policy'][2]['name'], "Live class");
        $this->assertEquals($data['result']['grading_policy'][2]['grade'], 20);
        $this->assertEquals($data['result']['grading_policy'][2]['type'], "LC");
        $this->assertEquals($data['result']['grading_policy'][2]['tpl'], 0);
        $this->assertEquals($data['result']['grading_policy'][2]['course_id'], 1);
        $this->assertEquals($data['result']['grading_policy'][2]['mandatory'], 1);
        $this->assertEquals(count($data['result']['grading_policy'][3]), 7);
        $this->assertEquals($data['result']['grading_policy'][3]['id'], 9);
        $this->assertEquals($data['result']['grading_policy'][3]['name'], "Capstone project");
        $this->assertEquals($data['result']['grading_policy'][3]['grade'], 20);
        $this->assertEquals($data['result']['grading_policy'][3]['type'], "CP");
        $this->assertEquals($data['result']['grading_policy'][3]['tpl'], 0);
        $this->assertEquals($data['result']['grading_policy'][3]['course_id'], 1);
        $this->assertEquals($data['result']['grading_policy'][3]['mandatory'], 1);
        $this->assertEquals(count($data['result']['grading_policy'][4]), 7);
        $this->assertEquals($data['result']['grading_policy'][4]['id'], 10);
        $this->assertEquals($data['result']['grading_policy'][4]['name'], "Attendance and participation");
        $this->assertEquals($data['result']['grading_policy'][4]['grade'], 20);
        $this->assertEquals($data['result']['grading_policy'][4]['type'], null);
        $this->assertEquals($data['result']['grading_policy'][4]['tpl'], 0);
        $this->assertEquals($data['result']['grading_policy'][4]['course_id'], 1);
        $this->assertEquals($data['result']['grading_policy'][4]['mandatory'], 1);
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
    public function testAddMaterialDocument($course_id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('materialdocument.add', array('course_id' => $course_id,'type' => 'type','title' => 'title','author' => 'author','link' => 'link','source' => 'src','token' => 'token','date' => '2015-01-01'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddCourse
     */
    public function testAddMaterialDocumentTwo($course_id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('materialdocument.add', array('course_id' => $course_id,'type' => 'type2','title' => 'title2','author' => 'author2','link' => 'link2','source' => 'src2','token' => 'token2','date' => '2015-01-02'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddMaterialDocument
     */
    public function testUpdateMaterialDocument($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('materialdocument.update', array('id' => $id,'type' => 'updatetype','title' => 'updatetitle','author' => 'updateauthor','link' => 'updatelink','source' => 'updatesrc','token' => 'updatetoken','date' => '2015-01-10'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddModuleInCourse($course_id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('module.add', array('course' => $course_id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddCourse
     * @depends testAddMaterialDocument
     * @depends testAddModuleInCourse
     */
    public function testAddItem($id, $material, $module)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.add', array('course' => $id,'grading_policy' => 6,'duration' => 234,'title' => 'title','describe' => 'description','type' => 'WG','weight' => 1,'module' => $module,'materials' => array($material)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
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
    
        $data = $this->jsonRpc('item.get', array('id' => $id,));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 13);
        $this->assertEquals(count($data['result']['module']) , 2);
        $this->assertEquals($data['result']['module']['id'] , 1);
        $this->assertEquals($data['result']['module']['title'] , null);
        $this->assertEquals(count($data['result']['program']) , 2);
        $this->assertEquals($data['result']['program']['id'] , 1);
        $this->assertEquals($data['result']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['course']) , 2);
        $this->assertEquals($data['result']['course']['id'] , 1);
        $this->assertEquals($data['result']['course']['title'] , "IMERIR");
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "title");
        $this->assertEquals($data['result']['describe'] , "description");
        $this->assertEquals($data['result']['duration'] , 234);
        $this->assertEquals($data['result']['type'] , "WG");
        $this->assertEquals($data['result']['weight'] , 1);
        $this->assertEquals($data['result']['course_id'] , 1);
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['grading_policy_id'] , 6);
        $this->assertEquals($data['result']['module_id'] , 1);
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
        
        $data = $this->jsonRpc('item.add', array('course' => $id,'grading_policy' => 7,'duration' => 234,'title' => 'titl2e','describe' => 'description2','type' => 'CP','weight' => 1));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testAddModuleInCourse
     */
    public function testGetModuleListByCourse($course_id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('module.getList', array('course' => $course_id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 3);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['title'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddItem
     * @depends testAddMaterialDocumentTwo
     * @depends testAddItemTwo
     */
    public function testUpdateItem($id, $material)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('item.update', array('id' => $id,'grading_policy' => 8,'duration' => 123,'title' => 'titl2e','describe' => 'description2','weight' => 1,'parent' => 2,'materials' => array($material)));
        
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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 11);
        $this->assertEquals(count($data['result'][0]['materials']), 0);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['title'], "titl2e");
        $this->assertEquals($data['result'][0]['describe'], "description2");
        $this->assertEquals($data['result'][0]['duration'], 234);
        $this->assertEquals($data['result'][0]['type'], "CP");
        $this->assertEquals($data['result'][0]['weight'], 1);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['parent_id'], null);
        $this->assertEquals($data['result'][0]['grading_policy_id'], 7);
        $this->assertEquals($data['result'][0]['module_id'], null);
        $this->assertEquals(count($data['result'][1]), 11);
        $this->assertEquals(count($data['result'][1]['materials']), 1);
        $this->assertEquals($data['result'][1]['materials'][0], 3);
        $this->assertEquals($data['result'][1]['id'], 1);
        $this->assertEquals($data['result'][1]['title'], "titl2e");
        $this->assertEquals($data['result'][1]['describe'], "description2");
        $this->assertEquals($data['result'][1]['duration'], 123);
        $this->assertEquals($data['result'][1]['type'], "WG");
        $this->assertEquals($data['result'][1]['weight'], 1);
        $this->assertEquals($data['result'][1]['course_id'], 1);
        $this->assertEquals($data['result'][1]['parent_id'], 2);
        $this->assertEquals($data['result'][1]['grading_policy_id'], 8);
        $this->assertEquals($data['result'][1]['module_id'], 1);
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
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 20);
        $this->assertEquals(count($data['result']['program']) , 2);
        $this->assertEquals($data['result']['program']['id'] , 1);
        $this->assertEquals($data['result']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['school']) , 3);
        $this->assertEquals($data['result']['school']['id'] , 2);
        $this->assertEquals($data['result']['school']['name'] , "université de monaco");
        $this->assertEquals($data['result']['school']['logo'] , "token");
        $this->assertEquals(count($data['result']['instructor']) , 1);
        $this->assertEquals(count($data['result']['instructor'][0]) , 12);
        $this->assertEquals($data['result']['instructor'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['instructor'][0]['school']) , 4);
        $this->assertEquals($data['result']['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['instructor'][0]['school']['logo'] , null);
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
        $this->assertEquals(count($data['result']['material_document']) , 3);
        $this->assertEquals(count($data['result']['material_document'][0]) , 12);
        $this->assertEquals($data['result']['material_document'][0]['id'] , 1);
        $this->assertEquals($data['result']['material_document'][0]['course_id'] , 1);
        $this->assertEquals($data['result']['material_document'][0]['type'] , "link");
        $this->assertEquals($data['result']['material_document'][0]['title'] , "title");
        $this->assertEquals($data['result']['material_document'][0]['author'] , "author");
        $this->assertEquals($data['result']['material_document'][0]['link'] , "link");
        $this->assertEquals($data['result']['material_document'][0]['source'] , "source");
        $this->assertEquals($data['result']['material_document'][0]['token'] , "token");
        $this->assertEquals($data['result']['material_document'][0]['date'] , "2011-01-01");
        $this->assertEquals(!empty($data['result']['material_document'][0]['created_date']) , true);
        $this->assertEquals($data['result']['material_document'][0]['deleted_date'] , null);
        $this->assertEquals($data['result']['material_document'][0]['updated_date'] , null);
        $this->assertEquals(count($data['result']['material_document'][1]) , 12);
        $this->assertEquals($data['result']['material_document'][1]['id'] , 2);
        $this->assertEquals($data['result']['material_document'][1]['course_id'] , 1);
        $this->assertEquals($data['result']['material_document'][1]['type'] , "updatetype");
        $this->assertEquals($data['result']['material_document'][1]['title'] , "updatetitle");
        $this->assertEquals($data['result']['material_document'][1]['author'] , "updateauthor");
        $this->assertEquals($data['result']['material_document'][1]['link'] , "updatelink");
        $this->assertEquals($data['result']['material_document'][1]['source'] , "updatesrc");
        $this->assertEquals($data['result']['material_document'][1]['token'] , "updatetoken");
        $this->assertEquals($data['result']['material_document'][1]['date'] , "2015-01-10");
        $this->assertEquals(!empty($data['result']['material_document'][1]['created_date']) , true);
        $this->assertEquals($data['result']['material_document'][1]['deleted_date'] , null);
        $this->assertEquals(!empty($data['result']['material_document'][1]['updated_date']) , true);
        $this->assertEquals(count($data['result']['material_document'][2]) , 12);
        $this->assertEquals($data['result']['material_document'][2]['id'] , 3);
        $this->assertEquals($data['result']['material_document'][2]['course_id'] , 1);
        $this->assertEquals($data['result']['material_document'][2]['type'] , "type2");
        $this->assertEquals($data['result']['material_document'][2]['title'] , "title2");
        $this->assertEquals($data['result']['material_document'][2]['author'] , "author2");
        $this->assertEquals($data['result']['material_document'][2]['link'] , "link2");
        $this->assertEquals($data['result']['material_document'][2]['source'] , "src2");
        $this->assertEquals($data['result']['material_document'][2]['token'] , "token2");
        $this->assertEquals($data['result']['material_document'][2]['date'] , "2015-01-02");
        $this->assertEquals(!empty($data['result']['material_document'][2]['created_date']) , true);
        $this->assertEquals($data['result']['material_document'][2]['deleted_date'] , null);
        $this->assertEquals($data['result']['material_document'][2]['updated_date'] , null);
        $this->assertEquals(count($data['result']['grading']) , 12);
        $this->assertEquals(count($data['result']['grading'][0]) , 8);
        $this->assertEquals($data['result']['grading'][0]['id'] , 13);
        $this->assertEquals($data['result']['grading'][0]['letter'] , "A");
        $this->assertEquals($data['result']['grading'][0]['min'] , 95);
        $this->assertEquals($data['result']['grading'][0]['max'] , 100);
        $this->assertEquals($data['result']['grading'][0]['grade'] , 4);
        $this->assertEquals($data['result']['grading'][0]['description'] , "Outstanding performance, works shows superior command of the subject.");
        $this->assertEquals($data['result']['grading'][0]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][0]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][1]) , 8);
        $this->assertEquals($data['result']['grading'][1]['id'] , 14);
        $this->assertEquals($data['result']['grading'][1]['letter'] , "A-");
        $this->assertEquals($data['result']['grading'][1]['min'] , 90);
        $this->assertEquals($data['result']['grading'][1]['max'] , 94);
        $this->assertEquals($data['result']['grading'][1]['grade'] , 3.7);
        $this->assertEquals($data['result']['grading'][1]['description'] , "Very good work showing understanding and mastery of all concepts.");
        $this->assertEquals($data['result']['grading'][1]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][1]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][2]) , 8);
        $this->assertEquals($data['result']['grading'][2]['id'] , 15);
        $this->assertEquals($data['result']['grading'][2]['letter'] , "B+");
        $this->assertEquals($data['result']['grading'][2]['min'] , 87);
        $this->assertEquals($data['result']['grading'][2]['max'] , 89);
        $this->assertEquals($data['result']['grading'][2]['grade'] , 3.3);
        $this->assertEquals($data['result']['grading'][2]['description'] , "Good work showing understanding and mastery of most concepts.");
        $this->assertEquals($data['result']['grading'][2]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][2]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][3]) , 8);
        $this->assertEquals($data['result']['grading'][3]['id'] , 16);
        $this->assertEquals($data['result']['grading'][3]['letter'] , "B");
        $this->assertEquals($data['result']['grading'][3]['min'] , 83);
        $this->assertEquals($data['result']['grading'][3]['max'] , 86);
        $this->assertEquals($data['result']['grading'][3]['grade'] , 3);
        $this->assertEquals($data['result']['grading'][3]['description'] , "Fairly good work that shows an understanding of the main concepts.");
        $this->assertEquals($data['result']['grading'][3]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][3]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][4]) , 8);
        $this->assertEquals($data['result']['grading'][4]['id'] , 17);
        $this->assertEquals($data['result']['grading'][4]['letter'] , "B-");
        $this->assertEquals($data['result']['grading'][4]['min'] , 80);
        $this->assertEquals($data['result']['grading'][4]['max'] , 82);
        $this->assertEquals($data['result']['grading'][4]['grade'] , 2.7);
        $this->assertEquals($data['result']['grading'][4]['description'] , "Fairly good work showing understanding of several important concepts.");
        $this->assertEquals($data['result']['grading'][4]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][4]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][5]) , 8);
        $this->assertEquals($data['result']['grading'][5]['id'] , 18);
        $this->assertEquals($data['result']['grading'][5]['letter'] , "C+");
        $this->assertEquals($data['result']['grading'][5]['min'] , 77);
        $this->assertEquals($data['result']['grading'][5]['max'] , 79);
        $this->assertEquals($data['result']['grading'][5]['grade'] , 2.3);
        $this->assertEquals($data['result']['grading'][5]['description'] , "Uneven understanding of the concepts with occasional lack of clarity");
        $this->assertEquals($data['result']['grading'][5]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][5]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][6]) , 8);
        $this->assertEquals($data['result']['grading'][6]['id'] , 19);
        $this->assertEquals($data['result']['grading'][6]['letter'] , "C");
        $this->assertEquals($data['result']['grading'][6]['min'] , 73);
        $this->assertEquals($data['result']['grading'][6]['max'] , 76);
        $this->assertEquals($data['result']['grading'][6]['grade'] , 2);
        $this->assertEquals($data['result']['grading'][6]['description'] , "Work that barely meets modest expectations for the class");
        $this->assertEquals($data['result']['grading'][6]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][6]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][7]) , 8);
        $this->assertEquals($data['result']['grading'][7]['id'] , 20);
        $this->assertEquals($data['result']['grading'][7]['letter'] , "C-");
        $this->assertEquals($data['result']['grading'][7]['min'] , 70);
        $this->assertEquals($data['result']['grading'][7]['max'] , 72);
        $this->assertEquals($data['result']['grading'][7]['grade'] , 1.7);
        $this->assertEquals($data['result']['grading'][7]['description'] , "Work that is below modest expectations for the class");
        $this->assertEquals($data['result']['grading'][7]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][7]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][8]) , 8);
        $this->assertEquals($data['result']['grading'][8]['id'] , 21);
        $this->assertEquals($data['result']['grading'][8]['letter'] , "D+");
        $this->assertEquals($data['result']['grading'][8]['min'] , 67);
        $this->assertEquals($data['result']['grading'][8]['max'] , 69);
        $this->assertEquals($data['result']['grading'][8]['grade'] , 1.3);
        $this->assertEquals($data['result']['grading'][8]['description'] , "Poor performance with lack of understanding of several important concepts");
        $this->assertEquals($data['result']['grading'][8]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][8]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][9]) , 8);
        $this->assertEquals($data['result']['grading'][9]['id'] , 22);
        $this->assertEquals($data['result']['grading'][9]['letter'] , "D");
        $this->assertEquals($data['result']['grading'][9]['min'] , 63);
        $this->assertEquals($data['result']['grading'][9]['max'] , 66);
        $this->assertEquals($data['result']['grading'][9]['grade'] , 1);
        $this->assertEquals($data['result']['grading'][9]['description'] , "Work that is marginally above the minimum expectations for the class");
        $this->assertEquals($data['result']['grading'][9]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][9]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][10]) , 8);
        $this->assertEquals($data['result']['grading'][10]['id'] , 23);
        $this->assertEquals($data['result']['grading'][10]['letter'] , "D-");
        $this->assertEquals($data['result']['grading'][10]['min'] , 60);
        $this->assertEquals($data['result']['grading'][10]['max'] , 62);
        $this->assertEquals($data['result']['grading'][10]['grade'] , 0.7);
        $this->assertEquals($data['result']['grading'][10]['description'] , "Work that barely meets the minimum expectations for the class");
        $this->assertEquals($data['result']['grading'][10]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][10]['school_id'] , 2);
        $this->assertEquals(count($data['result']['grading'][11]) , 8);
        $this->assertEquals($data['result']['grading'][11]['id'] , 24);
        $this->assertEquals($data['result']['grading'][11]['letter'] , "F");
        $this->assertEquals($data['result']['grading'][11]['min'] , 0);
        $this->assertEquals($data['result']['grading'][11]['max'] , 59);
        $this->assertEquals($data['result']['grading'][11]['grade'] , 0);
        $this->assertEquals($data['result']['grading'][11]['description'] , "Work does not meet the minimum expectations for the class");
        $this->assertEquals($data['result']['grading'][11]['tpl'] , 0);
        $this->assertEquals($data['result']['grading'][11]['school_id'] , 2);
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
     * @depends testAddCourse
     */
    public function testCanGetListModule($course_id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('module.getList', array('course' => $course_id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 3);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['course_id'], 1);
        $this->assertEquals($data['result'][0]['title'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testgetGrading($id)
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

    /**
     * @depends testCanAddSchool
     */
    public function testAddGrading($id)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('grading.update', array('school' => $id,'datas' => array(array('letter' => 'A','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'),array('letter' => 'B','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'),array('letter' => 'C','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'),array('letter' => 'D','min' => 5,'max' => 50,'grade' => 6.5,'description' => 'description'))));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
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
    public function testCanAddProgrmItem($item)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.add', array('item' => $item,'start_date' => '2017-01-01 10:10','due_date' => '2018-01-01 10:10','users' => array(1,2,3,4)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanUpdateProgrmItem($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.update', array('id' => $item_prog,'start_date' => '2017-06-01 12:10','users' => array(6)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanAddItemsProgUSer($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.addUser', array('item_prog' => $item_prog,'user' => array(1,2,6,4)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result'][0], 6);
        $this->assertEquals($data['result'][1], 4);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testGetQuestionaire($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('questionnaire.getByItemProg', array('item_prog' => $item_prog));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 6);
        $this->assertEquals(count($data['result']['questions']) , 11);
        $this->assertEquals(count($data['result']['questions'][0]) , 2);
        $this->assertEquals(is_numeric($data['result']['questions'][10]['id']) , true);
        $this->assertEquals(!empty($data['result']['questions'][10]['text']) , true);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['max_duration'] , 30);
        $this->assertEquals($data['result']['max_time'] , 10);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['questions'];
    }
    
    
    /**
     * @depends testCanAddProgrmItem
     * @depends testGetQuestionaire
     */
    public function testGetQuestionaireAnswer($item_prog, $questions)
    {
        $this->setIdentity(4);
        
        $data = $this->jsonRpc('questionnaire.answer', array('item_prog' => $item_prog, 'user' => 6, 'question' => $questions[1]['id'], 'scale' => 3));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanAddProgrmItem
     */
    public function testGetAnswer($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('questionnaire.getAnswer', array('item_prog' => $item_prog));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 7);
        $this->assertEquals(count($data['result']['answers']) , 1);
        $this->assertEquals(count($data['result']['answers'][0]) , 8);
        $this->assertEquals($data['result']['answers'][0]['id'] , 1);
        $this->assertEquals($data['result']['answers'][0]['questionnaire_user_id'] , 1);
        $this->assertEquals($data['result']['answers'][0]['questionnaire_question_id'] , 2);
        $this->assertEquals(!empty($data['result']['answers'][0]['question_id']) , true);
        $this->assertEquals($data['result']['answers'][0]['peer_id'] , 6);
        $this->assertEquals($data['result']['answers'][0]['scale_id'] , 3);
        $this->assertEquals($data['result']['answers'][0]['type'] , "PEER");
        $this->assertEquals(!empty($data['result']['answers'][0]['created_date']) , true);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['user_id'] , 4);
        $this->assertEquals($data['result']['questionnaire_id'] , 1);
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
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 14);
        $this->assertEquals($data['result'][0]['course'] , 1);
        $this->assertEquals($data['result'][0]['item'] , 1);
        $this->assertEquals($data['result'][0]['origin_name'] , null);
        $this->assertEquals($data['result'][0]['origin'] , null);
        $this->assertEquals($data['result'][0]['nationality_name'] , null);
        $this->assertEquals($data['result'][0]['nationality'] , null);
        $this->assertEquals($data['result'][0]['gender'] , null);
        $this->assertEquals($data['result'][0]['dimension'] , 1);
        $this->assertEquals($data['result'][0]['component'] , 2);
        $this->assertEquals($data['result'][0]['scale'] , 3);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['peer_id'] , 6);
        $this->assertEquals($data['result'][0]['type'] , "PEER");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanAddProgrmItem
     */
    public function testGetListAnswerPeer($item_prog)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('answer.getList', array('peer' => 6));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 14);
        $this->assertEquals($data['result'][0]['course'] , 1);
        $this->assertEquals($data['result'][0]['item'] , 1);
        $this->assertEquals($data['result'][0]['origin_name'] , null);
        $this->assertEquals($data['result'][0]['origin'] , null);
        $this->assertEquals($data['result'][0]['nationality_name'] , null);
        $this->assertEquals($data['result'][0]['nationality'] , null);
        $this->assertEquals($data['result'][0]['gender'] , null);
        $this->assertEquals($data['result'][0]['dimension'] , 1);
        $this->assertEquals($data['result'][0]['component'] , 2);
        $this->assertEquals($data['result'][0]['scale'] , 3);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['peer_id'] , 6);
        $this->assertEquals($data['result'][0]['type'] , "PEER");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGetValidTransfertVideo()
    {
        system('phing -q test-videoconf');
        
        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.validTransfertVideo', array('videoconf_archive' => 1,'url' => 'urlvideo'));
        
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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 8);
        $this->assertEquals(count($data['result'][0]['program']), 2);
        $this->assertEquals($data['result'][0]['program']['id'], 1);
        $this->assertEquals($data['result'][0]['program']['name'], "program name upd");
        $this->assertEquals(count($data['result'][0]['school']), 3);
        $this->assertEquals($data['result'][0]['school']['id'], 2);
        $this->assertEquals($data['result'][0]['school']['name'], "université de monaco");
        $this->assertEquals($data['result'][0]['school']['logo'], "token");
        $this->assertEquals(count($data['result'][0]['items']), 1);
        $this->assertEquals(count($data['result'][0]['items'][0]), 4);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog']), 1);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]), 5);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives']), 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]), 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['archive_token'], null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['archive_link'], "urlvideo");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][0]['archive_duration'], null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]), 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['id'], 2);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['archive_token'], null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['archive_link'], "urlvideo");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['videoconf_archives'][1]['archive_duration'], null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users']), 2);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users'][0]), 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['id'], 6);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['firstname'], "Guillaume");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['lastname'], "Masmejean");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][0]['avatar'], null);
        $this->assertEquals(count($data['result'][0]['items'][0]['item_prog'][0]['users'][1]), 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['id'], 4);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['firstname'], "Salim");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['lastname'], "Bendacha");
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['users'][1]['avatar'], null);
        $this->assertEquals($data['result'][0]['items'][0]['item_prog'][0]['id'], 1);
        $this->assertEquals(! empty($data['result'][0]['items'][0]['item_prog'][0]['start_date']), true);
        $this->assertEquals(! empty($data['result'][0]['items'][0]['item_prog'][0]['due_date']), true);
        $this->assertEquals($data['result'][0]['items'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['items'][0]['title'], "titl2e");
        $this->assertEquals($data['result'][0]['items'][0]['type'], "WG");
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['title'], "IMERIR");
        $this->assertEquals($data['result'][0]['abstract'], "un_token");
        $this->assertEquals($data['result'][0]['description'], "description");
        $this->assertEquals($data['result'][0]['picture'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddProgrmItem
     */
    public function testCanGetByItemProg($item_prog)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('videoconf.getByItemProg', array('itm_prog' => $item_prog));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 14); 
        $this->assertEquals(count($data['result']['videoconf_archives']) , 2); 
        $this->assertEquals(count($data['result']['videoconf_archives'][0]) , 4); 
        $this->assertEquals($data['result']['videoconf_archives'][0]['id'] , 1); 
        $this->assertEquals($data['result']['videoconf_archives'][0]['archive_token'] , null); 
        $this->assertEquals($data['result']['videoconf_archives'][0]['archive_link'] , "urlvideo"); 
        $this->assertEquals($data['result']['videoconf_archives'][0]['archive_duration'] , null); 
        $this->assertEquals(count($data['result']['videoconf_archives'][1]) , 4); 
        $this->assertEquals($data['result']['videoconf_archives'][1]['id'] , 2); 
        $this->assertEquals($data['result']['videoconf_archives'][1]['archive_token'] , null); 
        $this->assertEquals($data['result']['videoconf_archives'][1]['archive_link'] , "urlvideo"); 
        $this->assertEquals($data['result']['videoconf_archives'][1]['archive_duration'] , null); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals(!empty($data['result']['token']) , true); 
        $this->assertEquals(!empty($data['result']['start_date']) , true); 
        $this->assertEquals($data['result']['duration'] , null); 
        $this->assertEquals($data['result']['archive_token'] , null); 
        $this->assertEquals($data['result']['archive_link'] , null); 
        $this->assertEquals($data['result']['archive_status'] , null); 
        $this->assertEquals($data['result']['conversation_id'] , 1); 
        $this->assertEquals($data['result']['item_prog_id'] , 1); 
        $this->assertEquals($data['result']['title'] , null); 
        $this->assertEquals($data['result']['description'] , null); 
        $this->assertEquals(!empty($data['result']['created_date']) , true); 
        $this->assertEquals($data['result']['deleted_date'] , null); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
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
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 8);
        $this->assertEquals(count($data['result']['message']) , 10);
        $this->assertEquals(count($data['result']['message']['from']) , 1);
        $this->assertEquals(count($data['result']['message']['from'][0]) , 14);
        $this->assertEquals($data['result']['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['message']['from'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['message']['from'][0]['school']) , 4);
        $this->assertEquals($data['result']['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['id'] , 4);
        $this->assertEquals($data['result']['message']['from'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result']['message']['from'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['message']['from'][0]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['message']['from'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['message']['document']) , 0);
        $this->assertEquals(count($data['result']['message']['to']) , 1);
        $this->assertEquals(count($data['result']['message']['to'][0]) , 14);
        $this->assertEquals($data['result']['message']['to'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['message']['to'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['message']['to'][0]['school']) , 4);
        $this->assertEquals($data['result']['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['id'] , 6);
        $this->assertEquals($data['result']['message']['to'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['message']['to'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['message']['to'][0]['email'] , "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['message']['to'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['message']['to'][0]['program']) , 0);
        $this->assertEquals($data['result']['message']['id'] , 1);
        $this->assertEquals($data['result']['message']['title'] , null);
        $this->assertEquals($data['result']['message']['text'] , "Salut les copains");
        $this->assertEquals($data['result']['message']['token'] , null);
        $this->assertEquals($data['result']['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['message']['type'] , 3);
        $this->assertEquals(!empty($data['result']['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['user']) , 4);
        $this->assertEquals($data['result']['user']['id'] , 4);
        $this->assertEquals($data['result']['user']['firstname'] , "Salim");
        $this->assertEquals($data['result']['user']['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['user']['avatar'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['conversation_id'] , 1);
        $this->assertEquals($data['result']['from_id'] , 4);
        $this->assertEquals($data['result']['user_id'] , 4);
        $this->assertEquals(!empty($data['result']['read_date']) , true);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanGetByItemProg
     */
    public function testCanAddConversationInVideoconf($videoconf)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.addConversation', array('videoconf' => $videoconf,'users' => array(6),'text' => 'super text qui tue'));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 8);
        $this->assertEquals(count($data['result']['message']) , 10);
        $this->assertEquals(count($data['result']['message']['from']) , 1);
        $this->assertEquals(count($data['result']['message']['from'][0]) , 14);
        $this->assertEquals($data['result']['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['message']['from'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['message']['from'][0]['school']) , 4);
        $this->assertEquals($data['result']['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['id'] , 4);
        $this->assertEquals($data['result']['message']['from'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result']['message']['from'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['message']['from'][0]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['message']['from'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['message']['document']) , 0);
        $this->assertEquals(count($data['result']['message']['to']) , 1);
        $this->assertEquals(count($data['result']['message']['to'][0]) , 14);
        $this->assertEquals($data['result']['message']['to'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['message']['to'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['message']['to'][0]['school']) , 4);
        $this->assertEquals($data['result']['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['id'] , 6);
        $this->assertEquals($data['result']['message']['to'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['message']['to'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['message']['to'][0]['email'] , "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['message']['to'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['message']['to'][0]['program']) , 0);
        $this->assertEquals($data['result']['message']['id'] , 2);
        $this->assertEquals($data['result']['message']['title'] , null);
        $this->assertEquals($data['result']['message']['text'] , "super text qui tue");
        $this->assertEquals($data['result']['message']['token'] , null);
        $this->assertEquals($data['result']['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['message']['type'] , 3);
        $this->assertEquals(!empty($data['result']['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['user']) , 4);
        $this->assertEquals($data['result']['user']['id'] , 4);
        $this->assertEquals($data['result']['user']['firstname'] , "Salim");
        $this->assertEquals($data['result']['user']['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['user']['avatar'] , null);
        $this->assertEquals($data['result']['id'] , 3);
        $this->assertEquals($data['result']['conversation_id'] , 2);
        $this->assertEquals($data['result']['from_id'] , 4);
        $this->assertEquals($data['result']['user_id'] , 4);
        $this->assertEquals(!empty($data['result']['read_date']) , true);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['conversation_id'];
    }

    /**
     * @depends testCanAddConversationInVideoconf
     */
    public function testCanGetConversation($conv)
    {
        $this->setIdentity(6);
        $data = $this->jsonRpc('conversation.getConversation', array('conversation' => $conv));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 3);
        $this->assertEquals(count($data['result']['users']) , 2);
        $this->assertEquals(count($data['result']['users'][4]) , 4);
        $this->assertEquals($data['result']['users'][4]['id'] , 4);
        $this->assertEquals($data['result']['users'][4]['firstname'] , "Salim");
        $this->assertEquals($data['result']['users'][4]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['users'][4]['avatar'] , null);
        $this->assertEquals(count($data['result']['users'][6]) , 4);
        $this->assertEquals($data['result']['users'][6]['id'] , 6);
        $this->assertEquals($data['result']['users'][6]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['users'][6]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['users'][6]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']) , 2);
        $this->assertEquals(count($data['result']['messages']['list']) , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']) , 10);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from']) , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]) , 14);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['school']) , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['id'] , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['document']) , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to']) , 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]) , 14);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['school']) , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['id'] , 6);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['email'] , "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['program']) , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['id'] , 2);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['title'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['text'] , "super text qui tue");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['token'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['type'] , 3);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['messages']['list'][0]['user']) , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['id'] , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['firstname'] , "Salim");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['messages']['list'][0]['id'] , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['conversation_id'] , 2);
        $this->assertEquals($data['result']['messages']['list'][0]['from_id'] , 4);
        $this->assertEquals($data['result']['messages']['list'][0]['user_id'] , 6);
        $this->assertEquals($data['result']['messages']['list'][0]['read_date'] , null);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['messages']['count'] , 1);
        $this->assertEquals($data['result']['id'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }

    /**
     * @depends testCanGetByItemProg
     */
    public function testCanJoinUser($videoconf)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('videoconf.joinUser', array('id' => $videoconf));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 18);
        $this->assertEquals($data['result']['item_assignment_id'] , 1);
        $this->assertEquals(count($data['result']['docs']) , 1);
        $this->assertEquals(count($data['result']['docs'][0]) , 5);
        $this->assertEquals($data['result']['docs'][0]['id'] , 1);
        $this->assertEquals($data['result']['docs'][0]['videoconf_id'] , 1);
        $this->assertEquals($data['result']['docs'][0]['token'] , 1234567890);
        $this->assertEquals($data['result']['docs'][0]['name'] , "nomdufichier");
        $this->assertEquals(!empty($data['result']['docs'][0]['created_date']) , true);
        $this->assertEquals(count($data['result']['users']) , 2);
        $this->assertEquals(count($data['result']['users'][6]) , 4);
        $this->assertEquals($data['result']['users'][6]['id'] , 6);
        $this->assertEquals($data['result']['users'][6]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['users'][6]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['users'][6]['avatar'] , null);
        $this->assertEquals(count($data['result']['users'][4]) , 4);
        $this->assertEquals($data['result']['users'][4]['id'] , 4);
        $this->assertEquals($data['result']['users'][4]['firstname'] , "Salim");
        $this->assertEquals($data['result']['users'][4]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['users'][4]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations']) , 2);
        $this->assertEquals(count($data['result']['conversations'][1]) , 3);
        $this->assertEquals(count($data['result']['conversations'][1]['users']) , 2);
        $this->assertEquals(count($data['result']['conversations'][1]['users'][4]) , 4);
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['id'] , 4);
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['firstname'] , "Salim");
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['conversations'][1]['users'][4]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][1]['users'][6]) , 4);
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['id'] , 6);
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['conversations'][1]['users'][6]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']) , 2);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list']) , 1);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']) , 10);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from']) , 1);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]) , 14);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']) , 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['id'] , 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['document']) , 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to']) , 1);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]) , 14);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']) , 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['id'] , 6);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['email'] , "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['message']['to'][0]['program']) , 0);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['title'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['text'] , "Salut les copains");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['token'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['message']['type'] , 3);
        $this->assertEquals(!empty($data['result']['conversations'][1]['messages']['list'][0]['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['conversations'][1]['messages']['list'][0]['user']) , 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['id'] , 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['firstname'] , "Salim");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['conversation_id'] , 1);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['from_id'] , 4);
        $this->assertEquals($data['result']['conversations'][1]['messages']['list'][0]['user_id'] , 4);
        $this->assertEquals(!empty($data['result']['conversations'][1]['messages']['list'][0]['read_date']) , true);
        $this->assertEquals(!empty($data['result']['conversations'][1]['messages']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['conversations'][1]['messages']['count'] , 1);
        $this->assertEquals($data['result']['conversations'][1]['id'] , 1);
        $this->assertEquals(count($data['result']['conversations'][2]) , 3);
        $this->assertEquals(count($data['result']['conversations'][2]['users']) , 2);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][4]) , 4);
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['id'] , 4);
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['firstname'] , "Salim");
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['conversations'][2]['users'][4]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['users'][6]) , 4);
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['id'] , 6);
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['conversations'][2]['users'][6]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']) , 2);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']) , 10);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]) , 14);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']) , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['id'] , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['firstname'] , "Salim");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['email'] , "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['position'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['interest'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['from'][0]['program']) , 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['document']) , 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to']) , 1);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]) , 14);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']) , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['id'] , 6);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['email'] , "gmasmejean@thestudnet.com");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['position'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['interest'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['roles']) , 1);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['roles'][0] , "student");
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['message']['to'][0]['program']) , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['title'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['text'] , "super text qui tue");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['token'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['is_draft'] , 0);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['message']['type'] , 3);
        $this->assertEquals(!empty($data['result']['conversations'][2]['messages']['list'][0]['message']['created_date']) , true);
        $this->assertEquals(count($data['result']['conversations'][2]['messages']['list'][0]['user']) , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['id'] , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['firstname'] , "Salim");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['id'] , 3);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['conversation_id'] , 2);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['from_id'] , 4);
        $this->assertEquals($data['result']['conversations'][2]['messages']['list'][0]['user_id'] , 4);
        $this->assertEquals(!empty($data['result']['conversations'][2]['messages']['list'][0]['read_date']) , true);
        $this->assertEquals(!empty($data['result']['conversations'][2]['messages']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['conversations'][2]['messages']['count'] , 1);
        $this->assertEquals($data['result']['conversations'][2]['id'] , 2);
        $this->assertEquals(count($data['result']['videoconf_admin']) , 5);
        $this->assertEquals($data['result']['videoconf_admin']['id'] , 1);
        $this->assertEquals($data['result']['videoconf_admin']['videoconf_id'] , 1);
        $this->assertEquals($data['result']['videoconf_admin']['user_id'] , 4);
        $this->assertEquals(!empty($data['result']['videoconf_admin']['token']) , true);
        $this->assertEquals(!empty($data['result']['videoconf_admin']['created_date']) , true);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals(!empty($data['result']['token']) , true);
        $this->assertEquals(!empty($data['result']['start_date']) , true);
        $this->assertEquals($data['result']['duration'] , null);
        $this->assertEquals($data['result']['archive_token'] , null);
        $this->assertEquals($data['result']['archive_link'] , null);
        $this->assertEquals($data['result']['archive_status'] , null);
        $this->assertEquals($data['result']['conversation_id'] , 1);
        $this->assertEquals($data['result']['item_prog_id'] , 1);
        $this->assertEquals($data['result']['title'] , null);
        $this->assertEquals($data['result']['description'] , null);
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddItemsProgUSer
     */
    public function testCanItemProgForCalendar($item_prog_user)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('itemprog.getList', array('start' => '2013-06-01 00:00','end' => '2018-07-01 00:00'));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 1); 
        $this->assertEquals(count($data['result'][0]) , 8); 
        $this->assertEquals(count($data['result'][0]['item_prog_user']) , 2); 
        $this->assertEquals(count($data['result'][0]['item_prog_user'][0]) , 5); 
        $this->assertEquals($data['result'][0]['item_prog_user'][0]['id'] , 3); 
        $this->assertEquals($data['result'][0]['item_prog_user'][0]['user_id'] , 4); 
        $this->assertEquals($data['result'][0]['item_prog_user'][0]['item_prog_id'] , 1); 
        $this->assertEquals($data['result'][0]['item_prog_user'][0]['started_date'] , null); 
        $this->assertEquals($data['result'][0]['item_prog_user'][0]['finished_date'] , null); 
        $this->assertEquals(count($data['result'][0]['item_prog_user'][1]) , 5); 
        $this->assertEquals($data['result'][0]['item_prog_user'][1]['id'] , 2); 
        $this->assertEquals($data['result'][0]['item_prog_user'][1]['user_id'] , 6); 
        $this->assertEquals($data['result'][0]['item_prog_user'][1]['item_prog_id'] , 1); 
        $this->assertEquals($data['result'][0]['item_prog_user'][1]['started_date'] , null); 
        $this->assertEquals($data['result'][0]['item_prog_user'][1]['finished_date'] , null); 
        $this->assertEquals(count($data['result'][0]['users']) , 2); 
        $this->assertEquals(count($data['result'][0]['users'][0]) , 4); 
        $this->assertEquals($data['result'][0]['users'][0]['id'] , 6); 
        $this->assertEquals($data['result'][0]['users'][0]['firstname'] , "Guillaume"); 
        $this->assertEquals($data['result'][0]['users'][0]['lastname'] , "Masmejean"); 
        $this->assertEquals($data['result'][0]['users'][0]['avatar'] , null); 
        $this->assertEquals(count($data['result'][0]['users'][1]) , 4); 
        $this->assertEquals($data['result'][0]['users'][1]['id'] , 4); 
        $this->assertEquals($data['result'][0]['users'][1]['firstname'] , "Salim"); 
        $this->assertEquals($data['result'][0]['users'][1]['lastname'] , "Bendacha"); 
        $this->assertEquals($data['result'][0]['users'][1]['avatar'] , null); 
        $this->assertEquals(count($data['result'][0]['item']) , 6); 
        $this->assertEquals(count($data['result'][0]['item']['module']) , 2); 
        $this->assertEquals($data['result'][0]['item']['module']['id'] , 1); 
        $this->assertEquals($data['result'][0]['item']['module']['title'] , null); 
        $this->assertEquals(count($data['result'][0]['item']['program']) , 2); 
        $this->assertEquals($data['result'][0]['item']['program']['id'] , 1); 
        $this->assertEquals($data['result'][0]['item']['program']['name'] , "program name upd"); 
        $this->assertEquals(count($data['result'][0]['item']['course']) , 3); 
        $this->assertEquals(count($data['result'][0]['item']['course']['grading_policy']) , 2); 
        $this->assertEquals($data['result'][0]['item']['course']['grading_policy']['name'] , "Live class"); 
        $this->assertEquals($data['result'][0]['item']['course']['grading_policy']['type'] , "LC"); 
        $this->assertEquals($data['result'][0]['item']['course']['id'] , 1); 
        $this->assertEquals($data['result'][0]['item']['course']['title'] , "IMERIR"); 
        $this->assertEquals($data['result'][0]['item']['id'] , 1); 
        $this->assertEquals($data['result'][0]['item']['title'] , "titl2e"); 
        $this->assertEquals($data['result'][0]['item']['type'] , "WG"); 
        $this->assertEquals($data['result'][0]['editable'] , 1); 
        $this->assertEquals($data['result'][0]['id'] , 1); 
        $this->assertEquals($data['result'][0]['item_id'] , 1); 
        $this->assertEquals(!empty($data['result'][0]['start_date']) , true); 
        $this->assertEquals(!empty($data['result'][0]['due_date']) , true); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
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
     *
     * @depends testCanAddProgrmItem
     */
    public function testCanVideoconfStart($item_prog)
    {
        $this->setIdentity(4);
    
        $data = $this->jsonRpc('itemproguser.start', array('item_prog' => $item_prog));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     *
     * @depends testCanVideoconfStart
     */
    public function testCanGetStartedConference()
    {
        $this->setIdentity(4);
    
        $data = $this->jsonRpc('itemproguser.getStartedConference', array());
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 3);
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
     *
     * @depends testCanAddProgrmItem
     */
    public function testCanVideoconfEnd($item_prog)
    {
        $this->setIdentity(4);
    
        $data = $this->jsonRpc('itemproguser.end', array('item_prog' => $item_prog));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
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

    /**
     * @depends testAddModuleInCourse
     * @depends testCanAddItemsProgUSer
     */
    public function testCanGetItemsProgUSerByModule($module)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('item.getListByModule', array('module' => $module));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 11);
        $this->assertEquals(count($data['result'][0]['item_prog']) , 1);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]) , 8);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item_prog_user']) , 2);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item_prog_user'][0]) , 5);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][0]['id'] , 3);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][0]['user_id'] , 4);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][0]['item_prog_id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['item_prog'][0]['item_prog_user'][0]['started_date']) , true);
        $this->assertEquals(!empty($data['result'][0]['item_prog'][0]['item_prog_user'][0]['finished_date']) , true);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item_prog_user'][1]) , 5);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][1]['id'] , 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][1]['user_id'] , 6);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][1]['item_prog_id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][1]['started_date'] , null);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_prog_user'][1]['finished_date'] , null);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['users']) , 2);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['users'][0]) , 4);
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][0]['id'] , 6);
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][0]['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['users'][1]) , 4);
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][1]['id'] , 4);
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][1]['firstname'] , "Salim");
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][1]['lastname'] , "Bendacha");
        $this->assertEquals($data['result'][0]['item_prog'][0]['users'][1]['avatar'] , null);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']) , 6);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['module']) , 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['module']['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['module']['title'] , null);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['program']) , 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['program']['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['course']) , 3);
        $this->assertEquals(count($data['result'][0]['item_prog'][0]['item']['course']['grading_policy']) , 2);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['course']['grading_policy']['name'] , "Live class");
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['course']['grading_policy']['type'] , "LC");
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['course']['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['course']['title'] , "IMERIR");
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['title'] , "titl2e");
        $this->assertEquals($data['result'][0]['item_prog'][0]['item']['type'] , "WG");
        $this->assertEquals($data['result'][0]['item_prog'][0]['editable'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['item_prog'][0]['item_id'] , 1);
        $this->assertEquals(!empty($data['result'][0]['item_prog'][0]['start_date']) , true);
        $this->assertEquals(!empty($data['result'][0]['item_prog'][0]['due_date']) , true);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['title'] , "titl2e");
        $this->assertEquals($data['result'][0]['describe'] , "description2");
        $this->assertEquals($data['result'][0]['duration'] , 123);
        $this->assertEquals($data['result'][0]['type'] , "WG");
        $this->assertEquals($data['result'][0]['weight'] , 1);
        $this->assertEquals($data['result'][0]['course_id'] , 1);
        $this->assertEquals($data['result'][0]['parent_id'] , 2);
        $this->assertEquals($data['result'][0]['grading_policy_id'] , 8);
        $this->assertEquals($data['result'][0]['module_id'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testCanAddProgrmItem
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

    /**
     * @depends testCanAddProgram
     * @depends testAddCourse
     */
    public function testCangetListGrade($program, $course)
    {
         system('phing -q test-dateitemprog');
        
        $this->setIdentity(3);
        $data = $this->jsonRpc('item.getListGrade', array('program' => $program,'course' => $course,'type' => array("IA","CP","WG","LC"),"new_message" => true,"filter" => array("n" => 10,"p" => 1)));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['list'][0]['module']) , 2);
        $this->assertEquals($data['result']['list'][0]['module']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['module']['title'] , null);
        $this->assertEquals(count($data['result']['list'][0]['program']) , 2);
        $this->assertEquals($data['result']['list'][0]['program']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['list'][0]['course']) , 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['item_prog']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_assignment']) , 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_assignment']['id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][0]['item_prog']['item_assignment']['submit_date']) , true);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_grade']) , 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['letter'] , null);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['grade'] , null);
        $this->assertEquals($data['result']['list'][0]['item_prog']['id'] , 1);
        $this->assertEquals(!empty($data['result']['list'][0]['item_prog']['start_date']) , true);
        $this->assertEquals(!empty($data['result']['list'][0]['item_prog']['due_date']) , true);
        $this->assertEquals(count($data['result']['list'][0]['users']) , 2);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]) , 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['logo'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['roles'][0] , "student");
        $this->assertEquals($data['result']['list'][0]['users'][0]['id'] , 6);
        $this->assertEquals($data['result']['list'][0]['users'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['list'][0]['users'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['list'][0]['users'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]) , 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['logo'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['roles'][0] , "student");
        $this->assertEquals($data['result']['list'][0]['users'][1]['id'] , 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['firstname'] , "Salim");
        $this->assertEquals($data['result']['list'][0]['users'][1]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['list'][0]['users'][1]['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['new_message'] , 1);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "titl2e");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
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
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('item.getListGrade', array('program' => $program));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['list'][0]['module']) , 2);
        $this->assertEquals($data['result']['list'][0]['module']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['module']['title'] , null);
        $this->assertEquals(count($data['result']['list'][0]['program']) , 2);
        $this->assertEquals($data['result']['list'][0]['program']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['list'][0]['course']) , 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['item_prog']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_assignment']) , 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_assignment']['id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][0]['item_prog']['item_assignment']['submit_date']) , true);
        $this->assertEquals(count($data['result']['list'][0]['item_prog']['item_grade']) , 2);
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['letter'] , "D-");
        $this->assertEquals($data['result']['list'][0]['item_prog']['item_grade']['grade'] , 60);
        $this->assertEquals($data['result']['list'][0]['item_prog']['id'] , 1);
        $this->assertEquals(!empty($data['result']['list'][0]['item_prog']['start_date']) , true);
        $this->assertEquals(!empty($data['result']['list'][0]['item_prog']['due_date']) , true);
        $this->assertEquals(count($data['result']['list'][0]['users']) , 2);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]) , 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][0]['school']['logo'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['roles'][0] , "student");
        $this->assertEquals($data['result']['list'][0]['users'][0]['id'] , 6);
        $this->assertEquals($data['result']['list'][0]['users'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['list'][0]['users'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['list'][0]['users'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]) , 6);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['users'][1]['school']['logo'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users'][1]['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['roles'][0] , "student");
        $this->assertEquals($data['result']['list'][0]['users'][1]['id'] , 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['firstname'] , "Salim");
        $this->assertEquals($data['result']['list'][0]['users'][1]['lastname'] , "Bendacha");
        $this->assertEquals($data['result']['list'][0]['users'][1]['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['new_message'] , 1);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "titl2e");
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
        $this->assertEquals(count($data['result'][2]['items'][0]) , 5);
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
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 14);
        $this->assertEquals($data['result']['list'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['list'][0]['contacts_count'] , 1);
        $this->assertEquals(count($data['result']['list'][0]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['school']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['school']['name'] , "université de monaco");
        $this->assertEquals($data['result']['list'][0]['school']['short_name'] , "IUM");
        $this->assertEquals($data['result']['list'][0]['school']['logo'] , "token");
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['email'] , "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][0]['position'] , null);
        $this->assertEquals($data['result']['list'][0]['interest'] , null);
        $this->assertEquals($data['result']['list'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['list'][0]['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['roles'][0] , "admin");
        $this->assertEquals(count($data['result']['list'][0]['program']) , 1);
        $this->assertEquals(count($data['result']['list'][0]['program'][0]) , 5);
        $this->assertEquals($data['result']['list'][0]['program'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['program'][0]['name'] , "program name upd");
        $this->assertEquals($data['result']['list'][0]['program'][0]['level'] , "mba");
        $this->assertEquals($data['result']['list'][0]['program'][0]['sis'] , "sisupd");
        $this->assertEquals($data['result']['list'][0]['program'][0]['year'] , null);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanAddProgram
     * @depends testCanAddUserCourse
     */
    public function testCanGetCoursesListByUser($program)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('course.getList', array('filter' => ['user' => 1]));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 17);
        $this->assertEquals(count($data['result']['list'][0]['instructor']) , 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]) , 12);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'] , 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'] , null);
        $this->assertEquals(!empty($data['result']['list'][0]['start_date']) , true);
        $this->assertEquals(!empty($data['result']['list'][0]['end_date']) , true);
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
    public function testAddThread($course)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('thread.add', array('title' => 'un titre','course' => $course, 'message' => 'super messge'));
        
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
        $data = $this->jsonRpc('thread.getList', array('course' => $course));

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['list'][0]['course']) , 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['message']) , 4);
        $this->assertEquals(count($data['result']['list'][0]['message']['user']) , 4);
        $this->assertEquals($data['result']['list'][0]['message']['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['message']['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['message']['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['message']['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['message']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['message']['message'] , "super messge");
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']) , true);
        $this->assertEquals($data['result']['list'][0]['nb_message'] , 1);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0] , "admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "un titre update");
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
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

    /**
     * @depends testAddThread
     */
    public function testGetListThreadMessage($thread)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('threadmessage.getList', array('thread' => $thread));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 2);
        $this->assertEquals(count($data['result']['list']) , 2);
        $this->assertEquals(count($data['result']['list'][0]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['thread']) , 2);
        $this->assertEquals($data['result']['list'][0]['thread']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['thread']['course_id'] , 1);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0] , "admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['message'] , "super messge");
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
        $this->assertEquals(count($data['result']['list'][1]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['thread']) , 2);
        $this->assertEquals($data['result']['list'][1]['thread']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['thread']['course_id'] , 1);
        $this->assertEquals(count($data['result']['list'][1]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][1]['user']['roles']) , 1);
        $this->assertEquals($data['result']['list'][1]['user']['roles'][0] , "super_admin");
        $this->assertEquals($data['result']['list'][1]['user']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result']['list'][1]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][1]['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['message'] , "un super message update");
        $this->assertEquals(!empty($data['result']['list'][1]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testGetThreadTwo($course)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.getList', array('course' => $course));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 8);
        $this->assertEquals(count($data['result']['list'][0]['course']) , 2);
        $this->assertEquals($data['result']['list'][0]['course']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['list'][0]['message']) , 4);
        $this->assertEquals(count($data['result']['list'][0]['message']['user']) , 4);
        $this->assertEquals($data['result']['list'][0]['message']['user']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['message']['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['message']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['message']['message'] , "un super message update");
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']) , true);
        $this->assertEquals($data['result']['list'][0]['nb_message'] , 2);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['roles']) , 1);
        $this->assertEquals($data['result']['list'][0]['user']['roles'][0] , "admin");
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "un titre update");
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result']['list'][0]['id'];
    }
    
    /**
     * @depends testGetThreadTwo
     */
    public function testGetThreadOnlyOne($thread)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('thread.get', array('id' => $thread));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 8);
        $this->assertEquals(count($data['result']['course']) , 2);
        $this->assertEquals($data['result']['course']['id'] , 1);
        $this->assertEquals($data['result']['course']['title'] , "IMERIR");
        $this->assertEquals(count($data['result']['message']) , 4);
        $this->assertEquals(count($data['result']['message']['user']) , 4);
        $this->assertEquals($data['result']['message']['user']['id'] , 2);
        $this->assertEquals($data['result']['message']['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['message']['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result']['message']['user']['avatar'] , null);
        $this->assertEquals($data['result']['message']['id'] , 2);
        $this->assertEquals($data['result']['message']['message'] , "un super message update");
        $this->assertEquals(!empty($data['result']['message']['created_date']) , true);
        $this->assertEquals($data['result']['nb_message'] , 2);
        $this->assertEquals(count($data['result']['user']) , 5);
        $this->assertEquals(count($data['result']['user']['roles']) , 1);
        $this->assertEquals($data['result']['user']['roles'][0] , "admin");
        $this->assertEquals($data['result']['user']['id'] , 1);
        $this->assertEquals($data['result']['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['user']['avatar'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "un titre update");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
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
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 7);
        $this->assertEquals(count($data['result']['item_prog']) , 4);
        $this->assertEquals(count($data['result']['item_prog']['item']) , 9);
        $this->assertEquals(count($data['result']['item_prog']['item']['materials']) , 1);
        $this->assertEquals(count($data['result']['item_prog']['item']['materials'][0]) , 8);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['id'] , 3);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['course_id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['type'] , "type2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['title'] , "title2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['author'] , "author2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['link'] , "link2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['source'] , "src2");
        $this->assertEquals($data['result']['item_prog']['item']['materials'][0]['token'] , "token2");
        $this->assertEquals(count($data['result']['item_prog']['item']['module']) , 2);
        $this->assertEquals($data['result']['item_prog']['item']['module']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['module']['title'] , null);
        $this->assertEquals(count($data['result']['item_prog']['item']['program']) , 2);
        $this->assertEquals($data['result']['item_prog']['item']['program']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['program']['name'] , "program name upd");
        $this->assertEquals(count($data['result']['item_prog']['item']['course']) , 3);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor']) , 1);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor'][0]) , 12);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['item_prog']['item']['course']['instructor'][0]['school']) , 4);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['item_prog']['item']['course']['instructor'][0]['school']['logo'] , null);
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
        $this->assertEquals($data['result']['item_prog']['item']['item_grade']['grade'] , 60);
        $this->assertEquals(!empty($data['result']['item_prog']['item']['item_grade']['created_date']) , true);
        $this->assertEquals($data['result']['item_prog']['item']['id'] , 1);
        $this->assertEquals($data['result']['item_prog']['item']['title'] , "titl2e");
        $this->assertEquals($data['result']['item_prog']['item']['describe'] , "description2");
        $this->assertEquals($data['result']['item_prog']['item']['type'] , "WG");
        $this->assertEquals($data['result']['item_prog']['id'] , 1);
        $this->assertEquals(!empty($data['result']['item_prog']['start_date']) , true);
        $this->assertEquals(!empty($data['result']['item_prog']['due_date']) , true);
        $this->assertEquals(count($data['result']['students']) , 2);
        $this->assertEquals(count($data['result']['students'][0]) , 6);
        $this->assertEquals(count($data['result']['students'][0]['school']) , 4);
        $this->assertEquals($data['result']['students'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['students'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['students'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['students'][0]['school']['logo'] , null);
        $this->assertEquals(count($data['result']['students'][0]['roles']) , 1);
        $this->assertEquals($data['result']['students'][0]['roles'][0] , "student");
        $this->assertEquals($data['result']['students'][0]['id'] , 6);
        $this->assertEquals($data['result']['students'][0]['firstname'] , "Guillaume");
        $this->assertEquals($data['result']['students'][0]['lastname'] , "Masmejean");
        $this->assertEquals($data['result']['students'][0]['avatar'] , null);
        $this->assertEquals(count($data['result']['students'][1]) , 6);
        $this->assertEquals(count($data['result']['students'][1]['school']) , 4);
        $this->assertEquals($data['result']['students'][1]['school']['id'] , 1);
        $this->assertEquals($data['result']['students'][1]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['students'][1]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['students'][1]['school']['logo'] , null);
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
        $this->assertEquals(count($data['result']['comments'][0]) , 6);
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
        $this->assertEquals($data['result']['comments'][0]['item_assignment_id'] , 2);
        $this->assertEquals(!empty($data['result']['comments'][0]['created_date']) , true);
        $this->assertEquals($data['result']['comments'][0]['read_date'] , null);
        $this->assertEquals($data['result']['id'] , 2);
        $this->assertEquals($data['result']['response'] , "response");
        $this->assertEquals(!empty($data['result']['submit_date']) , true);
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
     * @depends testAddModuleInCourse
     */
    public function testCanDeleteModule($module)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('module.delete', array('id' => $module));
        
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
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 17);
        $this->assertEquals(count($data['result']['list'][0]['instructor']) , 1);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]) , 12);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contact_state'] , 0);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['contacts_count'] , 0);
        $this->assertEquals(count($data['result']['list'][0]['instructor'][0]['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['id'] , 5);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['firstname'] , "Sébastien");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['lastname'] , "Sayegh");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['email'] , "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['birth_date'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['position'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['interest'] , null);
        $this->assertEquals($data['result']['list'][0]['instructor'][0]['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['start_date'] , null);
        $this->assertEquals($data['result']['list'][0]['end_date'] , null);
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
            ->will($this->returnValue(array('id' => $id)));
        
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
