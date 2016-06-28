<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ConversationVideoTest extends AbstractService
{
    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAdd()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('conversation.create', [
            'users' => [1,2,3,4], 
            'text' => 'un text de la mort qui tue', 
            'text_editors' => [''], 
            'whiteboards' => [''], 
            'documents' => [
                'name' => 'un document', 
                'link' => 'http://www.cellie.fr/wp-content/uploads/2015/01/Tutoriel_Google_Drive_Cellie.pdf'
            ], 
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
            'conversation_id' => 2,
        ]);
    
        print_r($data);
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
            'conversation_id' => 3,
        ]);
        
        print_r($data);
    }
    
    
    public function testCanGet()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('conversation.get', [
            'id' => 1,
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
