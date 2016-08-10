<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class MaterialTest extends AbstractService
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
        
        return $course_id;
    }

    /**
     * @depends testCreateInit
     */
    public function testAddMaterial($course_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('material.add', ['course_id' => (int) $course_id,'name' => 'name2','type' => 'type2','link' => 'link2','token' => 'token2']);
        
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('material.add', ['course_id' => (int) $course_id,'name' => 'name','type' => 'type','link' => 'link','token' => 'token']);
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 11);
        $this->assertEquals($data['result']['id'], 4);
        $this->assertEquals($data['result']['name'], "name");
        $this->assertEquals($data['result']['link'], "link");
        $this->assertEquals($data['result']['token'], "token");
        $this->assertEquals($data['result']['type'], "type");
        $this->assertEquals(! empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals($data['result']['updated_date'], null);
        $this->assertEquals($data['result']['folder_id'], 1);
        $this->assertEquals($data['result']['owner_id'], 1);
        $this->assertEquals($data['result']['box_id'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['id'];
    }

    /**
     * @depends testCreateInit
     */
    public function testGetListMaterial($course_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('material.getList', [
            'course_id' => (int) $course_id
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][0]) , 11);
        $this->assertEquals($data['result'][0]['id'] , 3);
        $this->assertEquals($data['result'][0]['name'] , "name2");
        $this->assertEquals($data['result'][0]['link'] , "link2");
        $this->assertEquals($data['result'][0]['token'] , "token2");
        $this->assertEquals($data['result'][0]['type'] , "type2");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals($data['result'][0]['deleted_date'] , null);
        $this->assertEquals($data['result'][0]['updated_date'] , null);
        $this->assertEquals($data['result'][0]['folder_id'] , 1);
        $this->assertEquals($data['result'][0]['owner_id'] , 1);
        $this->assertEquals($data['result'][0]['box_id'] , null);
        $this->assertEquals(count($data['result'][1]) , 11);
        $this->assertEquals($data['result'][1]['id'] , 4);
        $this->assertEquals($data['result'][1]['name'] , "name");
        $this->assertEquals($data['result'][1]['link'] , "link");
        $this->assertEquals($data['result'][1]['token'] , "token");
        $this->assertEquals($data['result'][1]['type'] , "type");
        $this->assertEquals(!empty($data['result'][1]['created_date']) , true);
        $this->assertEquals($data['result'][1]['deleted_date'] , null);
        $this->assertEquals($data['result'][1]['updated_date'] , null);
        $this->assertEquals($data['result'][1]['folder_id'] , 1);
        $this->assertEquals($data['result'][1]['owner_id'] , 1);
        $this->assertEquals($data['result'][1]['box_id'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddMaterial
     */
    public function testDeleteMaterial($library_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('material.delete', ['library_id' => (int) $library_id]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCreateInit
     */
    public function testGetListMaterial2($course_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('material.getList', [
            'course_id' => (int) $course_id
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 11);
        $this->assertEquals($data['result'][0]['id'] , 3);
        $this->assertEquals($data['result'][0]['name'] , "name2");
        $this->assertEquals($data['result'][0]['link'] , "link2");
        $this->assertEquals($data['result'][0]['token'] , "token2");
        $this->assertEquals($data['result'][0]['type'] , "type2");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals($data['result'][0]['deleted_date'] , null);
        $this->assertEquals($data['result'][0]['updated_date'] , null);
        $this->assertEquals($data['result'][0]['folder_id'] , 1);
        $this->assertEquals($data['result'][0]['owner_id'] , 1);
        $this->assertEquals($data['result'][0]['box_id'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
    }
    
}
