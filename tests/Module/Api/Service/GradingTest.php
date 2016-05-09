<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class GradingTest extends AbstractService
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

        // ADD COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 1,'course' => $course_id));
        $this->reset();
        
        
        // ADD GRADING POLICY
        $this->setIdentity(1);
        $data = $this->jsonRpc('gradingpolicy.add', array('course_id' => $course_id,'name' => 'tata','grade' => 50));
        $grading_policy_id = $data['result'];
        $this->reset();
        
        // ADD SET
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.add', ['course' => $course_id,'name' => 'nameset','uid' => 'suid',
            'groups'=>[['name' =>'namegroup','uid'=>'guid','users'=>[1,3,4]]]]);
        $set_id = $data['result']['id'];
        $this->reset();
        
        return [
            'school_id' => $school_id,
            'set_id' => $set_id,
            'course_id' => $course_id,
            'grading_policy_id' => $grading_policy_id
        ];
    }
   
    /**
     * @depends testCreateInit
     */
    public function testAddCriteria($data)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('criteria.add', array('grading_policy' => $data['grading_policy_id'],'name' => 'criteria','points' => 50, 'description' =>  'description!!'));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        
        return $data['result'];
    }
  
     /**
     * @depends testAddCriteria
     */
    public function testUpdateCriteria($criteria)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('criteria.update', array('id' => $criteria, 'name' => 'criteria!!','points' => 40, 'description' =>  'description!!!!!'));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 

        
        return $criteria;
    }
    
    
      /**
     * @depends testAddCriteria
     */
    public function testGetCriteria($criteria)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('criteria.get', array('id' => $criteria));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 5); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['name'] , "criteria!!"); 
        $this->assertEquals($data['result']['points'] , 40); 
        $this->assertEquals($data['result']['description'] , "description!!!!!"); 
        $this->assertEquals($data['result']['grading_policy_id'] , 11); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 


        
        return $data['result'];
    }
    
      /**
     * @depends testAddCriteria
     */
    public function testDeleteCriteria($criteria)
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('criteria.delete', array('id' => $criteria));
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result'];
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
