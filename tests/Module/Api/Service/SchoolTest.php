<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class SchoolTest extends AbstractService
{

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCreateSchool()
    {
        // ADD SCHOOL
        $this->setIdentity(1);
        $data = $this->jsonRpc('school.add', array(
            'name' => 'université de monaco',
            'next_name' => 'buisness school',
            'short_name' => 'IUM','logo' => 'token',
            'describe' => 'une description',
            'website' => 'www.ium.com',
            'libelle' => 'gnam',
            'custom' => '{chainejson}',
            'programme' => 'super programme',
            'background' => 'background',   
            'phone' => '+33480547852',
            'contact' => 'contact@ium.com',
            'contact_id' => 1,
            'address' => array("street_no" => 12,"street_type" => "rue","street_name" => "du stade","city" => array("name" => "Monaco"),"country" => array("name" => "Monaco"))));
        return $data['result']['id'];
    }
    
    public function testCustom()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('school.getCustom', array(
            'libelle' => 'gnam'
        ));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 3);
        $this->assertEquals($data['result']['id'] , 3);
        $this->assertEquals($data['result']['custom'] , "{chainejson}");
        $this->assertEquals($data['result']['libelle'] , "gnam");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
   
}
