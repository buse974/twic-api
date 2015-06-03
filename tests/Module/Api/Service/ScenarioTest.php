<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class CourseTest extends AbstractService
{
    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAddSchool()
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('school.add', array(
            'name' => 'université de monaco',
            'next_name' => 'buisness school',
            'short_name' => 'IUM',
            'logo' => 'token',
            'describe' => 'une description',
            'website' => 'www.ium.com',
            'programme' => 'super programme',
            'backroung' => 'backroung',
            'phone' => '+33480547852',
            'contact' => 'contact@ium.com',
            'contact_id' => 1,
            'address' => array(
                "street_no" => 12,
                "street_type" => "rue",
                "street_name" => "du stade",
                "city" => array(
                    "name" => "Monaco"
                ),
                "country" => array(
                    "name" => "Monaco"
                )
            )
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 14);
        $this->assertEquals(count($datas['result']['address']), 14);
        $this->assertEquals(count($datas['result']['address']['city']), 2);
        $this->assertEquals($datas['result']['address']['city']['id'], 19064);
        $this->assertEquals($datas['result']['address']['city']['name'], "Monaco");
        $this->assertEquals(count($datas['result']['address']['division']), 2);
        $this->assertEquals($datas['result']['address']['division']['id'], null);
        $this->assertEquals($datas['result']['address']['division']['name'], null);
        $this->assertEquals(count($datas['result']['address']['country']), 3);
        $this->assertEquals($datas['result']['address']['country']['id'], 145);
        $this->assertEquals($datas['result']['address']['country']['short_name'], "Monaco");
        $this->assertEquals($datas['result']['address']['country']['name'], "Principality of Monaco");
        $this->assertEquals($datas['result']['address']['id'], 1);
        $this->assertEquals($datas['result']['address']['street_no'], 12);
        $this->assertEquals($datas['result']['address']['street_type'], "rue");
        $this->assertEquals($datas['result']['address']['street_name'], "du stade");
        $this->assertEquals($datas['result']['address']['longitude'], 7.3999876);
        $this->assertEquals($datas['result']['address']['latitude'], 43.7441142);
        $this->assertEquals($datas['result']['address']['door'], null);
        $this->assertEquals($datas['result']['address']['building'], null);
        $this->assertEquals($datas['result']['address']['apartment'], null);
        $this->assertEquals($datas['result']['address']['floor'], null);
        $this->assertEquals($datas['result']['address']['timezone'], "Europe/Paris");
        $this->assertEquals(count($datas['result']['contact_user']), 9);
        $this->assertEquals($datas['result']['contact_user']['id'], 1);
        $this->assertEquals($datas['result']['contact_user']['firstname'], "Nicolas");
        $this->assertEquals($datas['result']['contact_user']['lastname'], "Maremmani");
        $this->assertEquals($datas['result']['contact_user']['status'], null);
        $this->assertEquals($datas['result']['contact_user']['email'], "nmaremmani@thestudnet.com");
        $this->assertEquals($datas['result']['contact_user']['birth_date'], null);
        $this->assertEquals($datas['result']['contact_user']['position'], null);
        $this->assertEquals($datas['result']['contact_user']['interest'], null);
        $this->assertEquals($datas['result']['contact_user']['avatar'], null);
        $this->assertEquals($datas['result']['id'], 101);
        $this->assertEquals($datas['result']['name'], "université de monaco");
        $this->assertEquals($datas['result']['next_name'], "buisness school");
        $this->assertEquals($datas['result']['short_name'], "IUM");
        $this->assertEquals($datas['result']['logo'], "token");
        $this->assertEquals($datas['result']['describe'], "une description");
        $this->assertEquals($datas['result']['website'], "www.ium.com");
        $this->assertEquals($datas['result']['programme'], "super programme");
        $this->assertEquals($datas['result']['backroung'], "backroung");
        $this->assertEquals($datas['result']['phone'], + 33480547852);
        $this->assertEquals($datas['result']['contact'], "contact@ium.com");
        $this->assertEquals($datas['result']['address_id'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result']['id'];
    }

    /**
     * @depends testCanAddSchool
     *
     * @param integer $school_id            
     */
    public function testCanAddProgram($school_id)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('program.add', array(
            'name' => 'program name',
            'school_id' => $school_id,
            'level' => 'emba',
            'sis' => 'sis'
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 101);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testCanAddProgram
     *
     * @param integer $program_id            
     */
    public function testCanUpdateProgram($program_id)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('program.update', array(
            'id' => $program_id,
            'name' => 'program name upd',
            'level' => 'mba',
            'sis' => 'sisupd'
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanAddSchool
     * @depends testCanAddProgram
     */
    public function testAddCourse($school_id, $program_id)
    {
        $this->setIdentity(1);
        
        $datas = $this->jsonRpc('course.add', array(
            'title' => 'IMERIR',
            'abstract' => 'un_token',
            'description' => 'description',
            'objectives' => 'objectives',
            'teaching' => 'teaching',
            'attendance' => 'attendance',
            'duration' => 18,
            'notes' => 'notes',
            'learning_outcomes' => 'learning_outcomes',
            'video_link' => 'http://google.fr',
            'video_token' => 'video_token',
            'material_document' => array(
                array(
                    'type' => 'link',
                    'title' => 'title',
                    'author' => 'author',
                    'link' => 'link',
                    'source' => 'source',
                    'token' => 'token',
                    'date' => '2011-01-01'
                )
            ),
            'program_id' => $program_id
        ));
        
        $this->assertEquals(count($datas) , 3);
        $this->assertEquals(count($datas['result']) , 16);
        $this->assertEquals(count($datas['result']['creator']) , 5);
        $this->assertEquals(count($datas['result']['creator']['school']) , 3);
        $this->assertEquals($datas['result']['creator']['school']['id'] , 1);
        $this->assertEquals($datas['result']['creator']['school']['name'] , 'Tempor Limited');
        $this->assertEquals($datas['result']['creator']['school']['logo'] , null);
        $this->assertEquals($datas['result']['creator']['id'] , 1);
        $this->assertEquals($datas['result']['creator']['firstname'] , "Nicolas");
        $this->assertEquals($datas['result']['creator']['lastname'] , "Maremmani");
        $this->assertEquals($datas['result']['creator']['email'] , "nmaremmani@thestudnet.com");
        $this->assertEquals(count($datas['result']['material_document']) , 1);
        $this->assertEquals(count($datas['result']['material_document'][0]) , 12);
        $this->assertEquals($datas['result']['material_document'][0]['id'] , 1);
        $this->assertEquals($datas['result']['material_document'][0]['course_id'] , 5);
        $this->assertEquals($datas['result']['material_document'][0]['type'] , "link");
        $this->assertEquals($datas['result']['material_document'][0]['title'] , "title");
        $this->assertEquals($datas['result']['material_document'][0]['author'] , "author");
        $this->assertEquals($datas['result']['material_document'][0]['link'] , "link");
        $this->assertEquals($datas['result']['material_document'][0]['source'] , "source");
        $this->assertEquals($datas['result']['material_document'][0]['token'] , "token");
        $this->assertEquals($datas['result']['material_document'][0]['date'] , "2011-01-01");
        $this->assertEquals(!empty($datas['result']['material_document'][0]['created_date']) , true);
        $this->assertEquals($datas['result']['material_document'][0]['deleted_date'] , null);
        $this->assertEquals($datas['result']['material_document'][0]['updated_date'] , null);
        $this->assertEquals(count($datas['result']['grading']) , 12);
        $this->assertEquals(count($datas['result']['grading'][0]) , 8);
        $this->assertEquals($datas['result']['grading'][0]['id'] , 13);
        $this->assertEquals($datas['result']['grading'][0]['letter'] , "A");
        $this->assertEquals($datas['result']['grading'][0]['min'] , 95);
        $this->assertEquals($datas['result']['grading'][0]['max'] , 100);
        $this->assertEquals($datas['result']['grading'][0]['grade'] , 4);
        $this->assertEquals($datas['result']['grading'][0]['description'] , "Outstanding performance, works shows superior command of the subject.");
        $this->assertEquals($datas['result']['grading'][0]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][0]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][1]) , 8);
        $this->assertEquals($datas['result']['grading'][1]['id'] , 14);
        $this->assertEquals($datas['result']['grading'][1]['letter'] , "A-");
        $this->assertEquals($datas['result']['grading'][1]['min'] , 90);
        $this->assertEquals($datas['result']['grading'][1]['max'] , 94);
        $this->assertEquals($datas['result']['grading'][1]['grade'] , 3.7);
        $this->assertEquals($datas['result']['grading'][1]['description'] , "Very good work showing understanding and mastery of all concepts.");
        $this->assertEquals($datas['result']['grading'][1]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][1]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][2]) , 8);
        $this->assertEquals($datas['result']['grading'][2]['id'] , 15);
        $this->assertEquals($datas['result']['grading'][2]['letter'] , "B+");
        $this->assertEquals($datas['result']['grading'][2]['min'] , 87);
        $this->assertEquals($datas['result']['grading'][2]['max'] , 89);
        $this->assertEquals($datas['result']['grading'][2]['grade'] , 3.3);
        $this->assertEquals($datas['result']['grading'][2]['description'] , "Good work showing understanding and mastery of most concepts.");
        $this->assertEquals($datas['result']['grading'][2]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][2]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][3]) , 8);
        $this->assertEquals($datas['result']['grading'][3]['id'] , 16);
        $this->assertEquals($datas['result']['grading'][3]['letter'] , "B");
        $this->assertEquals($datas['result']['grading'][3]['min'] , 83);
        $this->assertEquals($datas['result']['grading'][3]['max'] , 86);
        $this->assertEquals($datas['result']['grading'][3]['grade'] , 3);
        $this->assertEquals($datas['result']['grading'][3]['description'] , "Fairly good work that shows an understanding of the main concepts.");
        $this->assertEquals($datas['result']['grading'][3]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][3]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][4]) , 8);
        $this->assertEquals($datas['result']['grading'][4]['id'] , 17);
        $this->assertEquals($datas['result']['grading'][4]['letter'] , "B-");
        $this->assertEquals($datas['result']['grading'][4]['min'] , 80);
        $this->assertEquals($datas['result']['grading'][4]['max'] , 82);
        $this->assertEquals($datas['result']['grading'][4]['grade'] , 2.7);
        $this->assertEquals($datas['result']['grading'][4]['description'] , "Fairly good work showing understanding of several important concepts.");
        $this->assertEquals($datas['result']['grading'][4]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][4]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][5]) , 8);
        $this->assertEquals($datas['result']['grading'][5]['id'] , 18);
        $this->assertEquals($datas['result']['grading'][5]['letter'] , "C+");
        $this->assertEquals($datas['result']['grading'][5]['min'] , 77);
        $this->assertEquals($datas['result']['grading'][5]['max'] , 79);
        $this->assertEquals($datas['result']['grading'][5]['grade'] , 2.3);
        $this->assertEquals($datas['result']['grading'][5]['description'] , "Uneven understanding of the concepts with occasional lack of clarity");
        $this->assertEquals($datas['result']['grading'][5]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][5]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][6]) , 8);
        $this->assertEquals($datas['result']['grading'][6]['id'] , 19);
        $this->assertEquals($datas['result']['grading'][6]['letter'] , "C");
        $this->assertEquals($datas['result']['grading'][6]['min'] , 73);
        $this->assertEquals($datas['result']['grading'][6]['max'] , 76);
        $this->assertEquals($datas['result']['grading'][6]['grade'] , 2);
        $this->assertEquals($datas['result']['grading'][6]['description'] , "Work that barely meets modest expectations for the class");
        $this->assertEquals($datas['result']['grading'][6]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][6]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][7]) , 8);
        $this->assertEquals($datas['result']['grading'][7]['id'] , 20);
        $this->assertEquals($datas['result']['grading'][7]['letter'] , "C-");
        $this->assertEquals($datas['result']['grading'][7]['min'] , 70);
        $this->assertEquals($datas['result']['grading'][7]['max'] , 72);
        $this->assertEquals($datas['result']['grading'][7]['grade'] , 1.7);
        $this->assertEquals($datas['result']['grading'][7]['description'] , "Work that is below modest expectations for the class");
        $this->assertEquals($datas['result']['grading'][7]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][7]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][8]) , 8);
        $this->assertEquals($datas['result']['grading'][8]['id'] , 21);
        $this->assertEquals($datas['result']['grading'][8]['letter'] , "D+");
        $this->assertEquals($datas['result']['grading'][8]['min'] , 67);
        $this->assertEquals($datas['result']['grading'][8]['max'] , 69);
        $this->assertEquals($datas['result']['grading'][8]['grade'] , 1.3);
        $this->assertEquals($datas['result']['grading'][8]['description'] , "Poor performance with lack of understanding of several important concepts");
        $this->assertEquals($datas['result']['grading'][8]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][8]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][9]) , 8);
        $this->assertEquals($datas['result']['grading'][9]['id'] , 22);
        $this->assertEquals($datas['result']['grading'][9]['letter'] , "D");
        $this->assertEquals($datas['result']['grading'][9]['min'] , 63);
        $this->assertEquals($datas['result']['grading'][9]['max'] , 66);
        $this->assertEquals($datas['result']['grading'][9]['grade'] , 1);
        $this->assertEquals($datas['result']['grading'][9]['description'] , "Work that is marginally above the minimum expectations for the class");
        $this->assertEquals($datas['result']['grading'][9]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][9]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][10]) , 8);
        $this->assertEquals($datas['result']['grading'][10]['id'] , 23);
        $this->assertEquals($datas['result']['grading'][10]['letter'] , "D-");
        $this->assertEquals($datas['result']['grading'][10]['min'] , 60);
        $this->assertEquals($datas['result']['grading'][10]['max'] , 62);
        $this->assertEquals($datas['result']['grading'][10]['grade'] , 0.7);
        $this->assertEquals($datas['result']['grading'][10]['description'] , "Work that barely meets the minimum expectations for the class");
        $this->assertEquals($datas['result']['grading'][10]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][10]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][11]) , 8);
        $this->assertEquals($datas['result']['grading'][11]['id'] , 24);
        $this->assertEquals($datas['result']['grading'][11]['letter'] , "F");
        $this->assertEquals($datas['result']['grading'][11]['min'] , 0);
        $this->assertEquals($datas['result']['grading'][11]['max'] , 59);
        $this->assertEquals($datas['result']['grading'][11]['grade'] , 0);
        $this->assertEquals($datas['result']['grading'][11]['description'] , "Work does not meet the minimum expectations for the class");
        $this->assertEquals($datas['result']['grading'][11]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][11]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading_policy']) , 2);
        $this->assertEquals(count($datas['result']['grading_policy'][0]) , 6);
        $this->assertEquals($datas['result']['grading_policy'][0]['id'] , 3);
        $this->assertEquals($datas['result']['grading_policy'][0]['name'] , "Individual Assignment");
        $this->assertEquals($datas['result']['grading_policy'][0]['grade'] , null);
        $this->assertEquals($datas['result']['grading_policy'][0]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading_policy'][0]['course_id'] , 5);
        $this->assertEquals($datas['result']['grading_policy'][0]['mandatory'] , 1);
        $this->assertEquals(count($datas['result']['grading_policy'][1]) , 6);
        $this->assertEquals($datas['result']['grading_policy'][1]['id'] , 4);
        $this->assertEquals($datas['result']['grading_policy'][1]['name'] , "Group work");
        $this->assertEquals($datas['result']['grading_policy'][1]['grade'] , null);
        $this->assertEquals($datas['result']['grading_policy'][1]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading_policy'][1]['course_id'] , 5);
        $this->assertEquals($datas['result']['grading_policy'][1]['mandatory'] , 1);
        $this->assertEquals($datas['result']['id'] , 5);
        $this->assertEquals($datas['result']['title'] , "IMERIR");
        $this->assertEquals($datas['result']['abstract'] , "un_token");
        $this->assertEquals($datas['result']['description'] , "description");
        $this->assertEquals($datas['result']['objectives'] , "objectives");
        $this->assertEquals($datas['result']['teaching'] , "teaching");
        $this->assertEquals($datas['result']['attendance'] , "attendance");
        $this->assertEquals($datas['result']['duration'] , 18);
        $this->assertEquals($datas['result']['notes'] , "notes");
        $this->assertEquals($datas['result']['learning_outcomes'] , "learning_outcomes");
        $this->assertEquals($datas['result']['video_link'] , "http://google.fr");
        $this->assertEquals($datas['result']['video_token'] , "video_token");
        $this->assertEquals($datas['id'] , 1);
        $this->assertEquals($datas['jsonrpc'] , 2.0);
        
        return $datas['result']['id'];
    }

    /**
     * @depends testAddCourse
     */
    public function testUpdateCourse($id)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('course.update', array(
            'id' => $id,
            'title' => 'IMERIR',
            'abstract' => 'un_token',
            'description' => 'description',
            'objectives' => 'objectives',
            'teaching' => 'teaching',
            'attendance' => 'attendance',
            'duration' => 18,
            'notes' => 'notes',
            'learning_outcomes' => 'learning_outcomes',
            'video_link' => 'http://google.fr',
            'video_token' => 'video_token'
        ));
        
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testUpdateCourse
     */
    public function testGetCourse($id)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('course.get', array(
            'id' => $id
        ));
        
        $this->assertEquals(count($datas) , 3);
        $this->assertEquals(count($datas['result']) , 16);
        $this->assertEquals(count($datas['result']['creator']) , 5);
        $this->assertEquals(count($datas['result']['creator']['school']) , 3);
        $this->assertEquals($datas['result']['creator']['school']['id'] , 1);
        $this->assertEquals($datas['result']['creator']['school']['name'] , 'Tempor Limited');
        $this->assertEquals($datas['result']['creator']['school']['logo'] , null);
        $this->assertEquals($datas['result']['creator']['id'] , 1);
        $this->assertEquals($datas['result']['creator']['firstname'] , "Nicolas");
        $this->assertEquals($datas['result']['creator']['lastname'] , "Maremmani");
        $this->assertEquals($datas['result']['creator']['email'] , "nmaremmani@thestudnet.com");
        $this->assertEquals(count($datas['result']['material_document']) , 1);
        $this->assertEquals(count($datas['result']['material_document'][0]) , 12);
        $this->assertEquals($datas['result']['material_document'][0]['id'] , 1);
        $this->assertEquals($datas['result']['material_document'][0]['course_id'] , 5);
        $this->assertEquals($datas['result']['material_document'][0]['type'] , "link");
        $this->assertEquals($datas['result']['material_document'][0]['title'] , "title");
        $this->assertEquals($datas['result']['material_document'][0]['author'] , "author");
        $this->assertEquals($datas['result']['material_document'][0]['link'] , "link");
        $this->assertEquals($datas['result']['material_document'][0]['source'] , "source");
        $this->assertEquals($datas['result']['material_document'][0]['token'] , "token");
        $this->assertEquals($datas['result']['material_document'][0]['date'] , "2011-01-01");
        $this->assertEquals(!empty($datas['result']['material_document'][0]['created_date']) , true);
        $this->assertEquals($datas['result']['material_document'][0]['deleted_date'] , null);
        $this->assertEquals($datas['result']['material_document'][0]['updated_date'] , null);
        $this->assertEquals(count($datas['result']['grading']) , 12);
        $this->assertEquals(count($datas['result']['grading'][0]) , 8);
        $this->assertEquals($datas['result']['grading'][0]['id'] , 13);
        $this->assertEquals($datas['result']['grading'][0]['letter'] , "A");
        $this->assertEquals($datas['result']['grading'][0]['min'] , 95);
        $this->assertEquals($datas['result']['grading'][0]['max'] , 100);
        $this->assertEquals($datas['result']['grading'][0]['grade'] , 4);
        $this->assertEquals($datas['result']['grading'][0]['description'] , "Outstanding performance, works shows superior command of the subject.");
        $this->assertEquals($datas['result']['grading'][0]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][0]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][1]) , 8);
        $this->assertEquals($datas['result']['grading'][1]['id'] , 14);
        $this->assertEquals($datas['result']['grading'][1]['letter'] , "A-");
        $this->assertEquals($datas['result']['grading'][1]['min'] , 90);
        $this->assertEquals($datas['result']['grading'][1]['max'] , 94);
        $this->assertEquals($datas['result']['grading'][1]['grade'] , 3.7);
        $this->assertEquals($datas['result']['grading'][1]['description'] , "Very good work showing understanding and mastery of all concepts.");
        $this->assertEquals($datas['result']['grading'][1]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][1]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][2]) , 8);
        $this->assertEquals($datas['result']['grading'][2]['id'] , 15);
        $this->assertEquals($datas['result']['grading'][2]['letter'] , "B+");
        $this->assertEquals($datas['result']['grading'][2]['min'] , 87);
        $this->assertEquals($datas['result']['grading'][2]['max'] , 89);
        $this->assertEquals($datas['result']['grading'][2]['grade'] , 3.3);
        $this->assertEquals($datas['result']['grading'][2]['description'] , "Good work showing understanding and mastery of most concepts.");
        $this->assertEquals($datas['result']['grading'][2]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][2]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][3]) , 8);
        $this->assertEquals($datas['result']['grading'][3]['id'] , 16);
        $this->assertEquals($datas['result']['grading'][3]['letter'] , "B");
        $this->assertEquals($datas['result']['grading'][3]['min'] , 83);
        $this->assertEquals($datas['result']['grading'][3]['max'] , 86);
        $this->assertEquals($datas['result']['grading'][3]['grade'] , 3);
        $this->assertEquals($datas['result']['grading'][3]['description'] , "Fairly good work that shows an understanding of the main concepts.");
        $this->assertEquals($datas['result']['grading'][3]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][3]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][4]) , 8);
        $this->assertEquals($datas['result']['grading'][4]['id'] , 17);
        $this->assertEquals($datas['result']['grading'][4]['letter'] , "B-");
        $this->assertEquals($datas['result']['grading'][4]['min'] , 80);
        $this->assertEquals($datas['result']['grading'][4]['max'] , 82);
        $this->assertEquals($datas['result']['grading'][4]['grade'] , 2.7);
        $this->assertEquals($datas['result']['grading'][4]['description'] , "Fairly good work showing understanding of several important concepts.");
        $this->assertEquals($datas['result']['grading'][4]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][4]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][5]) , 8);
        $this->assertEquals($datas['result']['grading'][5]['id'] , 18);
        $this->assertEquals($datas['result']['grading'][5]['letter'] , "C+");
        $this->assertEquals($datas['result']['grading'][5]['min'] , 77);
        $this->assertEquals($datas['result']['grading'][5]['max'] , 79);
        $this->assertEquals($datas['result']['grading'][5]['grade'] , 2.3);
        $this->assertEquals($datas['result']['grading'][5]['description'] , "Uneven understanding of the concepts with occasional lack of clarity");
        $this->assertEquals($datas['result']['grading'][5]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][5]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][6]) , 8);
        $this->assertEquals($datas['result']['grading'][6]['id'] , 19);
        $this->assertEquals($datas['result']['grading'][6]['letter'] , "C");
        $this->assertEquals($datas['result']['grading'][6]['min'] , 73);
        $this->assertEquals($datas['result']['grading'][6]['max'] , 76);
        $this->assertEquals($datas['result']['grading'][6]['grade'] , 2);
        $this->assertEquals($datas['result']['grading'][6]['description'] , "Work that barely meets modest expectations for the class");
        $this->assertEquals($datas['result']['grading'][6]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][6]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][7]) , 8);
        $this->assertEquals($datas['result']['grading'][7]['id'] , 20);
        $this->assertEquals($datas['result']['grading'][7]['letter'] , "C-");
        $this->assertEquals($datas['result']['grading'][7]['min'] , 70);
        $this->assertEquals($datas['result']['grading'][7]['max'] , 72);
        $this->assertEquals($datas['result']['grading'][7]['grade'] , 1.7);
        $this->assertEquals($datas['result']['grading'][7]['description'] , "Work that is below modest expectations for the class");
        $this->assertEquals($datas['result']['grading'][7]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][7]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][8]) , 8);
        $this->assertEquals($datas['result']['grading'][8]['id'] , 21);
        $this->assertEquals($datas['result']['grading'][8]['letter'] , "D+");
        $this->assertEquals($datas['result']['grading'][8]['min'] , 67);
        $this->assertEquals($datas['result']['grading'][8]['max'] , 69);
        $this->assertEquals($datas['result']['grading'][8]['grade'] , 1.3);
        $this->assertEquals($datas['result']['grading'][8]['description'] , "Poor performance with lack of understanding of several important concepts");
        $this->assertEquals($datas['result']['grading'][8]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][8]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][9]) , 8);
        $this->assertEquals($datas['result']['grading'][9]['id'] , 22);
        $this->assertEquals($datas['result']['grading'][9]['letter'] , "D");
        $this->assertEquals($datas['result']['grading'][9]['min'] , 63);
        $this->assertEquals($datas['result']['grading'][9]['max'] , 66);
        $this->assertEquals($datas['result']['grading'][9]['grade'] , 1);
        $this->assertEquals($datas['result']['grading'][9]['description'] , "Work that is marginally above the minimum expectations for the class");
        $this->assertEquals($datas['result']['grading'][9]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][9]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][10]) , 8);
        $this->assertEquals($datas['result']['grading'][10]['id'] , 23);
        $this->assertEquals($datas['result']['grading'][10]['letter'] , "D-");
        $this->assertEquals($datas['result']['grading'][10]['min'] , 60);
        $this->assertEquals($datas['result']['grading'][10]['max'] , 62);
        $this->assertEquals($datas['result']['grading'][10]['grade'] , 0.7);
        $this->assertEquals($datas['result']['grading'][10]['description'] , "Work that barely meets the minimum expectations for the class");
        $this->assertEquals($datas['result']['grading'][10]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][10]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading'][11]) , 8);
        $this->assertEquals($datas['result']['grading'][11]['id'] , 24);
        $this->assertEquals($datas['result']['grading'][11]['letter'] , "F");
        $this->assertEquals($datas['result']['grading'][11]['min'] , 0);
        $this->assertEquals($datas['result']['grading'][11]['max'] , 59);
        $this->assertEquals($datas['result']['grading'][11]['grade'] , 0);
        $this->assertEquals($datas['result']['grading'][11]['description'] , "Work does not meet the minimum expectations for the class");
        $this->assertEquals($datas['result']['grading'][11]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading'][11]['course_id'] , 5);
        $this->assertEquals(count($datas['result']['grading_policy']) , 2);
        $this->assertEquals(count($datas['result']['grading_policy'][0]) , 6);
        $this->assertEquals($datas['result']['grading_policy'][0]['id'] , 3);
        $this->assertEquals($datas['result']['grading_policy'][0]['name'] , "Individual Assignment");
        $this->assertEquals($datas['result']['grading_policy'][0]['grade'] , null);
        $this->assertEquals($datas['result']['grading_policy'][0]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading_policy'][0]['course_id'] , 5);
        $this->assertEquals($datas['result']['grading_policy'][0]['mandatory'] , 1);
        $this->assertEquals(count($datas['result']['grading_policy'][1]) , 6);
        $this->assertEquals($datas['result']['grading_policy'][1]['id'] , 4);
        $this->assertEquals($datas['result']['grading_policy'][1]['name'] , "Group work");
        $this->assertEquals($datas['result']['grading_policy'][1]['grade'] , null);
        $this->assertEquals($datas['result']['grading_policy'][1]['tpl'] , 0);
        $this->assertEquals($datas['result']['grading_policy'][1]['course_id'] , 5);
        $this->assertEquals($datas['result']['grading_policy'][1]['mandatory'] , 1);
        $this->assertEquals($datas['result']['id'] , 5);
        $this->assertEquals($datas['result']['title'] , "IMERIR");
        $this->assertEquals($datas['result']['abstract'] , "un_token");
        $this->assertEquals($datas['result']['description'] , "description");
        $this->assertEquals($datas['result']['objectives'] , "objectives");
        $this->assertEquals($datas['result']['teaching'] , "teaching");
        $this->assertEquals($datas['result']['attendance'] , "attendance");
        $this->assertEquals($datas['result']['duration'] , 18);
        $this->assertEquals($datas['result']['notes'] , "notes");
        $this->assertEquals($datas['result']['learning_outcomes'] , "learning_outcomes");
        $this->assertEquals($datas['result']['video_link'] , "http://google.fr");
        $this->assertEquals($datas['result']['video_token'] , "video_token");
        $this->assertEquals($datas['id'] , 1);
        $this->assertEquals($datas['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddModuleInCourse($course_id)
    {
    	$this->setIdentity(3);
    	$datas = $this->jsonRpc('module.add', array(
    			'course' => $course_id
    	));
    	
    	print_r($datas);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testAddMaterialDocument($course_id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('materialdocument.add', array(
            'course_id' => $course_id,
            'type' => 'type',
            'title' => 'title',
            'author' => 'author',
            'link' => 'link',
            'source' => 'src',
            'token' => 'token',
            'date' => '2015-01-01'
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 2);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testAddMaterialDocument
     */
    public function testUpdateMaterialDocument($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('materialdocument.update', array(
            'id' => $id,
            'type' => 'updatetype',
            'title' => 'updatetitle',
            'author' => 'updateauthor',
            'link' => 'updatelink',
            'source' => 'updatesrc',
            'token' => 'updatetoken',
            'date' => '2015-01-10'
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testgetGrading($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('grading.get', array(
            'id' => 1
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 12);
        $this->assertEquals(count($datas['result'][0]), 8);
        $this->assertEquals($datas['result'][0]['id'], 13);
        $this->assertEquals($datas['result'][0]['letter'], "A");
        $this->assertEquals($datas['result'][0]['min'], 95);
        $this->assertEquals($datas['result'][0]['max'], 100);
        $this->assertEquals($datas['result'][0]['grade'], 4);
        $this->assertEquals($datas['result'][0]['description'], "Outstanding performance, works shows superior command of the subject.");
        $this->assertEquals($datas['result'][0]['tpl'], 0);
        $this->assertEquals($datas['result'][0]['course_id'], 1);
        $this->assertEquals(count($datas['result'][1]), 8);
        $this->assertEquals($datas['result'][1]['id'], 14);
        $this->assertEquals($datas['result'][1]['letter'], "A-");
        $this->assertEquals($datas['result'][1]['min'], 90);
        $this->assertEquals($datas['result'][1]['max'], 94);
        $this->assertEquals($datas['result'][1]['grade'], 3.7);
        $this->assertEquals($datas['result'][1]['description'], "Very good work showing understanding and mastery of all concepts.");
        $this->assertEquals($datas['result'][1]['tpl'], 0);
        $this->assertEquals($datas['result'][1]['course_id'], 1);
        $this->assertEquals(count($datas['result'][2]), 8);
        $this->assertEquals($datas['result'][2]['id'], 15);
        $this->assertEquals($datas['result'][2]['letter'], "B+");
        $this->assertEquals($datas['result'][2]['min'], 87);
        $this->assertEquals($datas['result'][2]['max'], 89);
        $this->assertEquals($datas['result'][2]['grade'], 3.3);
        $this->assertEquals($datas['result'][2]['description'], "Good work showing understanding and mastery of most concepts.");
        $this->assertEquals($datas['result'][2]['tpl'], 0);
        $this->assertEquals($datas['result'][2]['course_id'], 1);
        $this->assertEquals(count($datas['result'][3]), 8);
        $this->assertEquals($datas['result'][3]['id'], 16);
        $this->assertEquals($datas['result'][3]['letter'], "B");
        $this->assertEquals($datas['result'][3]['min'], 83);
        $this->assertEquals($datas['result'][3]['max'], 86);
        $this->assertEquals($datas['result'][3]['grade'], 3);
        $this->assertEquals($datas['result'][3]['description'], "Fairly good work that shows an understanding of the main concepts.");
        $this->assertEquals($datas['result'][3]['tpl'], 0);
        $this->assertEquals($datas['result'][3]['course_id'], 1);
        $this->assertEquals(count($datas['result'][4]), 8);
        $this->assertEquals($datas['result'][4]['id'], 17);
        $this->assertEquals($datas['result'][4]['letter'], "B-");
        $this->assertEquals($datas['result'][4]['min'], 80);
        $this->assertEquals($datas['result'][4]['max'], 82);
        $this->assertEquals($datas['result'][4]['grade'], 2.7);
        $this->assertEquals($datas['result'][4]['description'], "Fairly good work showing understanding of several important concepts.");
        $this->assertEquals($datas['result'][4]['tpl'], 0);
        $this->assertEquals($datas['result'][4]['course_id'], 1);
        $this->assertEquals(count($datas['result'][5]), 8);
        $this->assertEquals($datas['result'][5]['id'], 18);
        $this->assertEquals($datas['result'][5]['letter'], "C+");
        $this->assertEquals($datas['result'][5]['min'], 77);
        $this->assertEquals($datas['result'][5]['max'], 79);
        $this->assertEquals($datas['result'][5]['grade'], 2.3);
        $this->assertEquals($datas['result'][5]['description'], "Uneven understanding of the concepts with occasional lack of clarity");
        $this->assertEquals($datas['result'][5]['tpl'], 0);
        $this->assertEquals($datas['result'][5]['course_id'], 1);
        $this->assertEquals(count($datas['result'][6]), 8);
        $this->assertEquals($datas['result'][6]['id'], 19);
        $this->assertEquals($datas['result'][6]['letter'], "C");
        $this->assertEquals($datas['result'][6]['min'], 73);
        $this->assertEquals($datas['result'][6]['max'], 76);
        $this->assertEquals($datas['result'][6]['grade'], 2);
        $this->assertEquals($datas['result'][6]['description'], "Work that barely meets modest expectations for the class");
        $this->assertEquals($datas['result'][6]['tpl'], 0);
        $this->assertEquals($datas['result'][6]['course_id'], 1);
        $this->assertEquals(count($datas['result'][7]), 8);
        $this->assertEquals($datas['result'][7]['id'], 20);
        $this->assertEquals($datas['result'][7]['letter'], "C-");
        $this->assertEquals($datas['result'][7]['min'], 70);
        $this->assertEquals($datas['result'][7]['max'], 72);
        $this->assertEquals($datas['result'][7]['grade'], 1.7);
        $this->assertEquals($datas['result'][7]['description'], "Work that is below modest expectations for the class");
        $this->assertEquals($datas['result'][7]['tpl'], 0);
        $this->assertEquals($datas['result'][7]['course_id'], 1);
        $this->assertEquals(count($datas['result'][8]), 8);
        $this->assertEquals($datas['result'][8]['id'], 21);
        $this->assertEquals($datas['result'][8]['letter'], "D+");
        $this->assertEquals($datas['result'][8]['min'], 67);
        $this->assertEquals($datas['result'][8]['max'], 69);
        $this->assertEquals($datas['result'][8]['grade'], 1.3);
        $this->assertEquals($datas['result'][8]['description'], "Poor performance with lack of understanding of several important concepts");
        $this->assertEquals($datas['result'][8]['tpl'], 0);
        $this->assertEquals($datas['result'][8]['course_id'], 1);
        $this->assertEquals(count($datas['result'][9]), 8);
        $this->assertEquals($datas['result'][9]['id'], 22);
        $this->assertEquals($datas['result'][9]['letter'], "D");
        $this->assertEquals($datas['result'][9]['min'], 63);
        $this->assertEquals($datas['result'][9]['max'], 66);
        $this->assertEquals($datas['result'][9]['grade'], 1);
        $this->assertEquals($datas['result'][9]['description'], "Work that is marginally above the minimum expectations for the class");
        $this->assertEquals($datas['result'][9]['tpl'], 0);
        $this->assertEquals($datas['result'][9]['course_id'], 1);
        $this->assertEquals(count($datas['result'][10]), 8);
        $this->assertEquals($datas['result'][10]['id'], 23);
        $this->assertEquals($datas['result'][10]['letter'], "D-");
        $this->assertEquals($datas['result'][10]['min'], 60);
        $this->assertEquals($datas['result'][10]['max'], 62);
        $this->assertEquals($datas['result'][10]['grade'], 0.7);
        $this->assertEquals($datas['result'][10]['description'], "Work that barely meets the minimum expectations for the class");
        $this->assertEquals($datas['result'][10]['tpl'], 0);
        $this->assertEquals($datas['result'][10]['course_id'], 1);
        $this->assertEquals(count($datas['result'][11]), 8);
        $this->assertEquals($datas['result'][11]['id'], 24);
        $this->assertEquals($datas['result'][11]['letter'], "F");
        $this->assertEquals($datas['result'][11]['min'], 0);
        $this->assertEquals($datas['result'][11]['max'], 59);
        $this->assertEquals($datas['result'][11]['grade'], 0);
        $this->assertEquals($datas['result'][11]['description'], "Work does not meet the minimum expectations for the class");
        $this->assertEquals($datas['result'][11]['tpl'], 0);
        $this->assertEquals($datas['result'][11]['course_id'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddGrading($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('grading.update', array(
            'course' => $id,
            'datas' => array(
                array(
                    'letter' => 'A',
                    'min' => 5,
                    'max' => 50,
                    'grade' => 6.5,
                    'description' => 'description'
                ),
                array(
                    'letter' => 'B',
                    'min' => 5,
                    'max' => 50,
                    'grade' => 6.5,
                    'description' => 'description'
                ),
                array(
                    'letter' => 'C',
                    'min' => 5,
                    'max' => 50,
                    'grade' => 6.5,
                    'description' => 'description'
                ),
                array(
                    'letter' => 'D',
                    'min' => 5,
                    'max' => 50,
                    'grade' => 6.5,
                    'description' => 'description'
                )
            )
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], true);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testgetGradingAfter($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('grading.get', array(
            'id' => 1
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 4);
        $this->assertEquals(count($datas['result'][0]), 8);
        $this->assertEquals($datas['result'][0]['id'], 25);
        $this->assertEquals($datas['result'][0]['letter'], "A");
        $this->assertEquals($datas['result'][0]['min'], 5);
        $this->assertEquals($datas['result'][0]['max'], 50);
        $this->assertEquals($datas['result'][0]['grade'], 6.5);
        $this->assertEquals($datas['result'][0]['description'], "description");
        $this->assertEquals($datas['result'][0]['tpl'], 0);
        $this->assertEquals($datas['result'][0]['course_id'], 1);
        $this->assertEquals(count($datas['result'][1]), 8);
        $this->assertEquals($datas['result'][1]['id'], 26);
        $this->assertEquals($datas['result'][1]['letter'], "B");
        $this->assertEquals($datas['result'][1]['min'], 5);
        $this->assertEquals($datas['result'][1]['max'], 50);
        $this->assertEquals($datas['result'][1]['grade'], 6.5);
        $this->assertEquals($datas['result'][1]['description'], "description");
        $this->assertEquals($datas['result'][1]['tpl'], 0);
        $this->assertEquals($datas['result'][1]['course_id'], 1);
        $this->assertEquals(count($datas['result'][2]), 8);
        $this->assertEquals($datas['result'][2]['id'], 27);
        $this->assertEquals($datas['result'][2]['letter'], "C");
        $this->assertEquals($datas['result'][2]['min'], 5);
        $this->assertEquals($datas['result'][2]['max'], 50);
        $this->assertEquals($datas['result'][2]['grade'], 6.5);
        $this->assertEquals($datas['result'][2]['description'], "description");
        $this->assertEquals($datas['result'][2]['tpl'], 0);
        $this->assertEquals($datas['result'][2]['course_id'], 1);
        $this->assertEquals(count($datas['result'][3]), 8);
        $this->assertEquals($datas['result'][3]['id'], 28);
        $this->assertEquals($datas['result'][3]['letter'], "D");
        $this->assertEquals($datas['result'][3]['min'], 5);
        $this->assertEquals($datas['result'][3]['max'], 50);
        $this->assertEquals($datas['result'][3]['grade'], 6.5);
        $this->assertEquals($datas['result'][3]['description'], "description");
        $this->assertEquals($datas['result'][3]['tpl'], 0);
        $this->assertEquals($datas['result'][3]['course_id'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetGradingPolicy($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('gradingpolicy.get', array(
            'id' => $id
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 2);
        $this->assertEquals(count($datas['result'][0]), 6);
        $this->assertEquals($datas['result'][0]['id'], 3);
        $this->assertEquals($datas['result'][0]['name'], "Individual Assignment");
        $this->assertEquals($datas['result'][0]['grade'], null);
        $this->assertEquals($datas['result'][0]['tpl'], 0);
        $this->assertEquals($datas['result'][0]['course_id'], 5);
        $this->assertEquals($datas['result'][0]['mandatory'], 1);
        $this->assertEquals(count($datas['result'][1]), 6);
        $this->assertEquals($datas['result'][1]['id'], 4);
        $this->assertEquals($datas['result'][1]['name'], "Group work");
        $this->assertEquals($datas['result'][1]['grade'], null);
        $this->assertEquals($datas['result'][1]['tpl'], 0);
        $this->assertEquals($datas['result'][1]['course_id'], 5);
        $this->assertEquals($datas['result'][1]['mandatory'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testAddGradingPolicy($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('gradingpolicy.add', array(
            'course_id' => $id,
            'name' => 'tata',
            'grade' => 50
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 5);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testAddGradingPolicy
     */
    public function testUpdateGradingPolicy($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('gradingpolicy.update', array(
            'id' => $id,
            'name' => 'toto',
            'grade' => 60
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], true);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetGradingPolicyAfter($id)
    {
    	$this->setIdentity(3);
        $datas = $this->jsonRpc('gradingpolicy.get', array(
            'id' => $id
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 3);
        $this->assertEquals(count($datas['result'][0]), 6);
        $this->assertEquals($datas['result'][0]['id'], 3);
        $this->assertEquals($datas['result'][0]['name'], "Individual Assignment");
        $this->assertEquals($datas['result'][0]['grade'], null);
        $this->assertEquals($datas['result'][0]['tpl'], 0);
        $this->assertEquals($datas['result'][0]['course_id'], 5);
        $this->assertEquals($datas['result'][0]['mandatory'], 1);
        $this->assertEquals(count($datas['result'][1]), 6);
        $this->assertEquals($datas['result'][1]['id'], 4);
        $this->assertEquals($datas['result'][1]['name'], "Group work");
        $this->assertEquals($datas['result'][1]['grade'], null);
        $this->assertEquals($datas['result'][1]['tpl'], 0);
        $this->assertEquals($datas['result'][1]['course_id'], 5);
        $this->assertEquals($datas['result'][1]['mandatory'], 1);
        $this->assertEquals(count($datas['result'][2]), 6);
        $this->assertEquals($datas['result'][2]['id'], 5);
        $this->assertEquals($datas['result'][2]['name'], "toto");
        $this->assertEquals($datas['result'][2]['grade'], 60);
        $this->assertEquals($datas['result'][2]['tpl'], 0);
        $this->assertEquals($datas['result'][2]['course_id'], 5);
        $this->assertEquals($datas['result'][2]['mandatory'], 0);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }
    
    // FAQ
    /**
     * @depends testAddCourse
     */
    public function testAddFaqAsk($id)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('faq.add', array(
            'ask' => 'une question',
            'answer' => 'une reponse',
            'course' => $id
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testAddFaqAsk
     */
    public function testUpdateFaqAsk($faq)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('faq.update', array(
            'id' => $faq,
            'ask' => 'une question update',
            'answer' => 'une reponse update'
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetList($course)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('faq.getList', array(
            'course' => $course
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 1);
        $this->assertEquals(count($datas['result'][0]), 5);
        $this->assertEquals($datas['result'][0]['id'], 1);
        $this->assertEquals($datas['result'][0]['ask'], "une question update");
        $this->assertEquals($datas['result'][0]['answer'], "une reponse update");
        $this->assertEquals($datas['result'][0]['course_id'], 5);
        $this->assertEquals(! empty($datas['result'][0]['created_date']), true);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddFaqAsk
     */
    public function testDelete($faq)
    {
    	$this->setIdentity(1);
    	
        $datas = $this->jsonRpc('faq.delete', array(
            'id' => $faq
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    public function testProgramGetList()
    {
        $this->setIdentity(1);
    
        $datas = $this->jsonRpc('program.getList', array());

        $this->assertEquals(count($datas) , 3);
        $this->assertEquals(count($datas['result']) , 2);
        $this->assertEquals(count($datas['result']['list']) , 4);
        $this->assertEquals(count($datas['result']['list'][0]) , 7);
        $this->assertEquals(count($datas['result']['list'][0]['student']) , 2);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list']) , 11);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][0]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][0]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['id'] , 13);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['firstname'] , "Ora");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['lastname'] , "Bentley");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['email'] , "auctor@interdumlibero.ca");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][0]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['position'] , "in lobortis tellus");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['interest'] , "nec tempus scelerisque, lorem ipsum sodales purus,");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][0]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][0]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][0]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][0]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][1]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][1]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['id'] , 24);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['firstname'] , "Emma");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['lastname'] , "Norman");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['email'] , "vitae@tellusNunclectus.com");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][1]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['position'] , "consectetuer, cursus");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['interest'] , "In at pede. Cras vulputate velit");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][1]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][1]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][1]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][1]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][2]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][2]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['id'] , 36);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['firstname'] , "Arsenio");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['lastname'] , "Mejia");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['email'] , "in@Sedeueros.net");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][2]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['position'] , "nibh dolor, nonummy");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['interest'] , "Vivamus non lorem vitae odio");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][2]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][2]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][2]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][2]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][3]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][3]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['id'] , 41);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['firstname'] , "Branden");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['lastname'] , "Glover");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['email'] , "malesuada.Integer@Quisquenonummyipsum.com");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][3]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['position'] , "augue malesuada");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['interest'] , "ipsum primis in faucibus orci luctus et ultrices posuere cubilia");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][3]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][3]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][3]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][3]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][4]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][4]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['id'] , 49);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['firstname'] , "Callie");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['lastname'] , "Woods");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['email'] , "dictum.eu@sedduiFusce.edu");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][4]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['position'] , "per conubia nostra,");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['interest'] , "Etiam laoreet, libero et tristique pellentesque, tellus sem mollis");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][4]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][4]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][4]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][4]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][5]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][5]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['id'] , 66);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['firstname'] , "Myles");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['lastname'] , "Kidd");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['email'] , "eget@ornare.ca");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][5]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['position'] , "consectetuer adipiscing");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['interest'] , "parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor.");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][5]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][5]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][5]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][5]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][6]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][6]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['id'] , 68);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['firstname'] , "Buckminster");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['lastname'] , "Whitfield");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['email'] , "mollis.non@Nuncquisarcu.co.uk");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][6]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['position'] , "natoque penatibus");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['interest'] , "facilisi. Sed neque. Sed eget lacus. Mauris");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][6]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][6]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][6]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][6]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][7]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][7]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['id'] , 69);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['firstname'] , "Dalton");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['lastname'] , "Rosario");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['email'] , "Aliquam.vulputate@afelis.net");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][7]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['position'] , "iaculis enim, sit");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['interest'] , "dis parturient montes, nascetur ridiculus mus.");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][7]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][7]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][7]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][7]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][8]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][8]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['id'] , 70);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['firstname'] , "Thane");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['lastname'] , "Roman");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['email'] , "Integer@eleifendnec.com");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][0]['student']['list'][8]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['position'] , "Aenean eget");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['interest'] , "malesuada augue ut lacus. Nulla tincidunt, neque vitae");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][8]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][8]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['student']['list'][8]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['student']['list'][8]['program'][0]['sis'] , "B8E");
    }
    
    public function testUserGetList()
    {
        $this->setIdentity(1);
        
        $datas = $this->jsonRpc('user.getList', array('type' => array(), 'search' => 'orn'));
        
        $this->assertEquals(count($datas) , 3);
        $this->assertEquals(count($datas['result']) , 2);
        $this->assertEquals(count($datas['result']['list']) , 24);
        $this->assertEquals(count($datas['result']['list'][0]) , 12);
        $this->assertEquals(count($datas['result']['list'][0]['school']) , 3);
        $this->assertEquals($datas['result']['list'][0]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][0]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][0]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][0]['id'] , 3);
        $this->assertEquals($datas['result']['list'][0]['firstname'] , "Christophe");
        $this->assertEquals($datas['result']['list'][0]['lastname'] , "Robert");
        $this->assertEquals($datas['result']['list'][0]['email'] , "crobert@thestudnet.com");
        $this->assertEquals($datas['result']['list'][0]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($datas['result']['list'][0]['birth_date'] , null);
        $this->assertEquals($datas['result']['list'][0]['position'] , null);
        $this->assertEquals($datas['result']['list'][0]['interest'] , null);
        $this->assertEquals($datas['result']['list'][0]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][0]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][0]['roles'][0] , "academic");
        $this->assertEquals(count($datas['result']['list'][0]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][0]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][0]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][0]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][0]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][0]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][1]) , 12);
        $this->assertEquals(count($datas['result']['list'][1]['school']) , 3);
        $this->assertEquals($datas['result']['list'][1]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][1]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][1]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][1]['id'] , 10);
        $this->assertEquals($datas['result']['list'][1]['firstname'] , "Maisie");
        $this->assertEquals($datas['result']['list'][1]['lastname'] , "Petersen");
        $this->assertEquals($datas['result']['list'][1]['email'] , "dui@Sedidrisus.net");
        $this->assertEquals($datas['result']['list'][1]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][1]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][1]['position'] , "ultricies ornare, elit");
        $this->assertEquals($datas['result']['list'][1]['interest'] , "consequat auctor, nunc nulla vulputate dui,");
        $this->assertEquals($datas['result']['list'][1]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][1]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][1]['roles'][0] , "academic");
        $this->assertEquals(count($datas['result']['list'][1]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][1]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][1]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][1]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][1]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][1]['program'][0]['sis'] , "B8E");
        $this->assertEquals(count($datas['result']['list'][2]) , 12);
        $this->assertEquals(count($datas['result']['list'][2]['school']) , 3);
        $this->assertEquals($datas['result']['list'][2]['school']['name'] , "Tempor Limited");
        $this->assertEquals($datas['result']['list'][2]['school']['short_name'] , "ornare");
        $this->assertEquals($datas['result']['list'][2]['school']['logo'] , null);
        $this->assertEquals($datas['result']['list'][2]['id'] , 13);
        $this->assertEquals($datas['result']['list'][2]['firstname'] , "Ora");
        $this->assertEquals($datas['result']['list'][2]['lastname'] , "Bentley");
        $this->assertEquals($datas['result']['list'][2]['email'] , "auctor@interdumlibero.ca");
        $this->assertEquals($datas['result']['list'][2]['password'] , "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals(!empty($datas['result']['list'][2]['birth_date']) , true);
        $this->assertEquals($datas['result']['list'][2]['position'] , "in lobortis tellus");
        $this->assertEquals($datas['result']['list'][2]['interest'] , "nec tempus scelerisque, lorem ipsum sodales purus,");
        $this->assertEquals($datas['result']['list'][2]['avatar'] , null);
        $this->assertEquals(count($datas['result']['list'][2]['roles']) , 1);
        $this->assertEquals($datas['result']['list'][2]['roles'][0] , "student");
        $this->assertEquals(count($datas['result']['list'][2]['program']) , 1);
        $this->assertEquals(count($datas['result']['list'][2]['program'][0]) , 4);
        $this->assertEquals($datas['result']['list'][2]['program'][0]['id'] , 46);
        $this->assertEquals($datas['result']['list'][2]['program'][0]['name'] , "ornare, lectus ante dictum");
        $this->assertEquals($datas['result']['list'][2]['program'][0]['level'] , "emba");
        $this->assertEquals($datas['result']['list'][2]['program'][0]['sis'] , "B8E");
    }
      
    /**
     * @depends testAddCourse
     */
    public function testCanAddUserCourse($course)
    {
    	$this->setIdentity(1);
    	
     	$datas = $this->jsonRpc('user.addCourse', array('user' => 1, 'course' => $course));
    	
    	$this->assertEquals(count($datas) , 3);
    	$this->assertEquals(count($datas['result']) , 1);
    	$this->assertEquals(count($datas['result'][1]) , 1);
    	$this->assertEquals($datas['result'][1][5] , 1);
    	$this->assertEquals($datas['id'] , 1);
    	$this->assertEquals($datas['jsonrpc'] , 2.0);
    	
    	$this->printDocTest($datas);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testCanAddDeleteCourse($course)
    {
    	$this->setIdentity(1);
    	 
    	$datas = $this->jsonRpc('user.deleteCourse', array('user' => 1, 'course' => $course));
    	 
    	$this->assertEquals(count($datas) , 3);
    	$this->assertEquals(count($datas['result']) , 1);
    	$this->assertEquals(count($datas['result'][1]) , 1);
    	$this->assertEquals($datas['result'][1][5] , 1);
    	$this->assertEquals($datas['id'] , 1);
    	$this->assertEquals($datas['jsonrpc'] , 2.0);
    	
    	$this->printDocTest($datas);
    }
    
    /**
     * @depends testAddCourse
     */
    public function testAddThread($course)
    {
        $this->setIdentity(1);
        
        $datas = $this->jsonRpc('thread.add', array(
            'title' => 'un titre',
            'course' => $course
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testAddThread
     */
    public function testUpdateThread($thread)
    {
        $this->setIdentity(1);
        
        $datas = $this->jsonRpc('thread.update', array(
            'title' => 'un titre update',
            'id' => $thread
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddCourse
     */
    public function testGetThread($course)
    {
    	$this->setIdentity(1);
        $datas = $this->jsonRpc('thread.getList', array(
            'course' => $course
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 1);
        $this->assertEquals(count($datas['result'][0]), 5);
        $this->assertEquals(count($datas['result'][0]['user']), 3);
        $this->assertEquals($datas['result'][0]['user']['id'], 1);
        $this->assertEquals($datas['result'][0]['user']['firstname'], "Nicolas");
        $this->assertEquals($datas['result'][0]['user']['lastname'], "Maremmani");
        $this->assertEquals($datas['result'][0]['id'], 1);
        $this->assertEquals($datas['result'][0]['title'], "un titre update");
        $this->assertEquals(! empty($datas['result'][0]['created_date']), true);
        $this->assertEquals($datas['result'][0]['deleted_date'], null);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddThread
     */
    public function testAddThreadMessage($thread)
    {
        $this->setIdentity(2);
        
        $datas = $this->jsonRpc('threadmessage.add', array(
            'message' => 'un super message',
            'thread' => $thread
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testAddThreadMessage
     */
    public function testUpdateThreadMessage($threadmessage)
    {
        $this->setIdentity(2);
        
        $datas = $this->jsonRpc('threadmessage.update', array(
            'message' => 'un super message update',
            'id' => $threadmessage
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
        
        return $datas['result'];
    }

    /**
     * @depends testAddThread
     */
    public function testGetListThreadMessage($thread)
    {
    	$this->setIdentity(1);
        $datas = $this->jsonRpc('threadmessage.getList', array(
            'thread' => $thread
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals(count($datas['result']), 1);
        $this->assertEquals(count($datas['result'][0]), 4);
        $this->assertEquals(count($datas['result'][0]['user']), 3);
        $this->assertEquals($datas['result'][0]['user']['id'], 2);
        $this->assertEquals($datas['result'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($datas['result'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($datas['result'][0]['id'], 1);
        $this->assertEquals($datas['result'][0]['message'], "un super message update");
        $this->assertEquals(! empty($datas['result'][0]['created_date']), true);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddThreadMessage
     */
    public function testDeletehreadMessage($threadmessage)
    {
        $this->setIdentity(2);
        
        $datas = $this->jsonRpc('threadmessage.delete', array(
            'id' => $threadmessage
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddThread
     */
    public function testDeletehread($thread)
    {
        $this->setIdentity(1);
        
        $datas = $this->jsonRpc('thread.delete', array(
            'id' => $thread
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }
    
    // DELETE
    
    /**
     * @depends testAddMaterialDocument
     */
    public function testDeleteMaterialDocument($id)
    {
    	$this->setIdentity(1);
        $datas = $this->jsonRpc('materialdocument.delete', array(
            'id' => $id
        ));
        
        $this->assertEquals(count($datas), 3);
        $this->assertEquals($datas['result'], 1);
        $this->assertEquals($datas['id'], 1);
        $this->assertEquals($datas['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddProgram
     */
    public function testCourseGetList($program)
    {
    	$this->setIdentity(1);
    
    	$datas = $this->jsonRpc('course.getList', array(
    			'program' => $program,
    			'search' => 'ME'
    	));

    	$this->assertEquals(count($datas) , 3);
    	$this->assertEquals(count($datas['result']) , 2);
    	$this->assertEquals($datas['result']['count'] , 1);
    	$this->assertEquals(count($datas['result']['list']) , 1);
    	$this->assertEquals(count($datas['result']['list'][0]) , 12);
    	$this->assertEquals($datas['result']['list'][0]['id'] , 5);
    	$this->assertEquals($datas['result']['list'][0]['title'] , "IMERIR");
    	$this->assertEquals($datas['result']['list'][0]['abstract'] , "un_token");
    	$this->assertEquals($datas['result']['list'][0]['description'] , "description");
    	$this->assertEquals($datas['result']['list'][0]['objectives'] , "objectives");
    	$this->assertEquals($datas['result']['list'][0]['teaching'] , "teaching");
    	$this->assertEquals($datas['result']['list'][0]['attendance'] , "attendance");
    	$this->assertEquals($datas['result']['list'][0]['duration'] , 18);
    	$this->assertEquals($datas['result']['list'][0]['notes'] , "notes");
    	$this->assertEquals($datas['result']['list'][0]['learning_outcomes'] , "learning_outcomes");
    	$this->assertEquals($datas['result']['list'][0]['video_link'] , "http://google.fr");
    	$this->assertEquals($datas['result']['list'][0]['video_token'] , "video_token");
    	$this->assertEquals($datas['id'] , 1);
    	$this->assertEquals($datas['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddCourse
     * @depends testCanAddProgram
     */
    public function testDeleteCourse($id)
    {
        $this->setIdentity(1);
        
        $datas = $this->jsonRpc('course.delete', array(
            'id' => $id
        ));
        
        $this->assertEquals(count($datas) , 3);
        $this->assertEquals(count($datas['result']) , 1);
        $this->assertEquals($datas['result'][5] , true);
        $this->assertEquals($datas['id'] , 1);
        $this->assertEquals($datas['jsonrpc'] , 2.0);
    }
    
	public function setIdentity($id)
	{
		$identityMock = $this->getMockBuilder('\Auth\Authentication\Adapter\Model\Identity')
		->disableOriginalConstructor()->getMock();
	
		$rbacMock = $this->getMockBuilder('\Rbac\Service\Rbac')
		->disableOriginalConstructor()->getMock();
	
		$identityMock->expects($this->any())
		->method('getId')
		->will($this->returnValue($id));
	
		$authMock = $this->getMockBuilder('\Zend\Authentication\AuthenticationService')
		->disableOriginalConstructor()->getMock();
	
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