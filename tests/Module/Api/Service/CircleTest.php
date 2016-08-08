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

    public function testAddCircle()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.add', [
            'name' => 'gnam'
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
    /**
     * @depends testAddCircle
     */
    public function testUpdateCircle($circle_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.update', [
            'id' => $circle_id,
            'name' => 'gnam'
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 0);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCircle
     */
    public function testGetListCircle($circle_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.getList', [
            'id' => $circle_id
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 2);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['name'] , "gnam");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
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
    
    /**
     * @depends testAddCircle
     * @depends testCreateSchool
     */
    public function testAddCircleSchool($circle_id, $school_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.addOrganizations', [
            'id' => $circle_id,
            'organizations' => [$school_id]
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][2] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    } 
    
    
    
    /**
     * @depends testAddCircle
     */
    public function testgetCircle($circle_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.get', [
            'id' => $circle_id
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 3);
        $this->assertEquals(count($data['result']['organizations']) , 1);
        $this->assertEquals(count($data['result']['organizations'][0]) , 2);
        $this->assertEquals($data['result']['organizations'][0]['circle_id'] , 1);
        $this->assertEquals($data['result']['organizations'][0]['organization_id'] , 2);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['name'] , "gnam");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCircle
     * @depends testCreateSchool
     */
    public function testRemoveCircleOrganization($circle_id, $school_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.deleteOrganizations', [
            'id' => $circle_id,
            'organizations' => [$school_id]
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals($data['result'][2] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddCircle
     */
    public function testDeleteCircle($circle_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('circle.delete', [
            'id' => $circle_id
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
   
}
