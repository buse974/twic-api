<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class EQCQTest extends AbstractService
{

    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testAddDimension()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('dimension.add', array('name' => 'Dimension','describe' => 'une super dimension '));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddDimension
     */
    public function testUpdateDimension($dimension)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('dimension.update', array('id' => $dimension,'name' => 'Dimension UPT','describe' => 'une super dimension UPT'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddDimension
     */
    public function testGetListDimension($dimension)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('dimension.getList', array());
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 3);
        $this->assertEquals(count($data['result']['list']), 3);
        $this->assertEquals(count($data['result']['list'][0]), 5);
        $this->assertEquals(count($data['result']['list'][0]['component']), 4);
        $this->assertEquals(count($data['result']['list'][0]['component'][0]), 6);
        $this->assertEquals(count($data['result']['list'][0]['component'][0]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][0]['component'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['component'][0]['name'], "Awareness");
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][0]['describe']), true);
        $this->assertEquals(count($data['result']['list'][0]['component'][1]), 6);
        $this->assertEquals(count($data['result']['list'][0]['component'][1]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][0]['component'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['component'][1]['name'], "Literacy");
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][1]['describe']), true);
        $this->assertEquals(count($data['result']['list'][0]['component'][2]), 6);
        $this->assertEquals(count($data['result']['list'][0]['component'][2]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][0]['component'][2]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['component'][2]['name'], "Impulse");
        $this->assertEquals($data['result']['list'][0]['component'][2]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][2]['describe']), true);
        $this->assertEquals(count($data['result']['list'][0]['component'][3]), 6);
        $this->assertEquals(count($data['result']['list'][0]['component'][3]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][0]['component'][3]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['component'][3]['name'], "Performance");
        $this->assertEquals($data['result']['list'][0]['component'][3]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][3]['describe']), true);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['name'], "CQ");
        $this->assertEquals(! empty($data['result']['list'][0]['describe']), true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'], null);
        $this->assertEquals(count($data['result']['list'][1]), 5);
        $this->assertEquals(count($data['result']['list'][1]['component']), 7);
        $this->assertEquals(count($data['result']['list'][1]['component'][0]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][0]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][1]['component'][0]['name'], "Positive drive");
        $this->assertEquals($data['result']['list'][1]['component'][0]['dimension_id'], 2);
        $this->assertEquals($data['result']['list'][1]['component'][0]['describe'], "Describe Positive drive");
        $this->assertEquals(count($data['result']['list'][1]['component'][1]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][1]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][1]['id'], 6);
        $this->assertEquals($data['result']['list'][1]['component'][1]['name'], "Empathy");
        $this->assertEquals($data['result']['list'][1]['component'][1]['dimension_id'], 2);
        $this->assertEquals($data['result']['list'][1]['component'][1]['describe'], "Describe Empathy");
        $this->assertEquals(count($data['result']['list'][1]['component'][2]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][2]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][2]['id'], 7);
        $this->assertEquals($data['result']['list'][1]['component'][2]['name'], "Happy Emotions");
        $this->assertEquals($data['result']['list'][1]['component'][2]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][2]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][3]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][3]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][3]['id'], 8);
        $this->assertEquals($data['result']['list'][1]['component'][3]['name'], "Emotional Self-Awareness");
        $this->assertEquals($data['result']['list'][1]['component'][3]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][3]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][4]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][4]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][4]['id'], 9);
        $this->assertEquals($data['result']['list'][1]['component'][4]['name'], "Emotional Display");
        $this->assertEquals($data['result']['list'][1]['component'][4]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][4]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][5]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][5]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][5]['id'], 10);
        $this->assertEquals($data['result']['list'][1]['component'][5]['name'], "Emotional Management");
        $this->assertEquals($data['result']['list'][1]['component'][5]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][5]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][6]), 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][6]['component_scales']), 0);
        $this->assertEquals($data['result']['list'][1]['component'][6]['id'], 11);
        $this->assertEquals($data['result']['list'][1]['component'][6]['name'], "Non-specific");
        $this->assertEquals($data['result']['list'][1]['component'][6]['dimension_id'], 2);
        $this->assertEquals($data['result']['list'][1]['component'][6]['describe'], "Non-specific");
        $this->assertEquals($data['result']['list'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['name'], "EQ");
        $this->assertEquals(! empty($data['result']['list'][1]['describe']), true);
        $this->assertEquals($data['result']['list'][1]['deleted_date'], null);
        $this->assertEquals(count($data['result']['list'][2]), 5);
        $this->assertEquals(count($data['result']['list'][2]['component']), 0);
        $this->assertEquals($data['result']['list'][2]['id'], 3);
        $this->assertEquals($data['result']['list'][2]['name'], null);
        $this->assertEquals($data['result']['list'][2]['describe'], "une super dimension UPT");
        $this->assertEquals($data['result']['list'][2]['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testAddQuestion()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc(
            'question.add', array(
                'text' => 'super texte de fou',
                'component' => 1
        ));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 52);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
        
    }
    
    /**
     * @depends testAddQuestion
     */
    public function testUpdateQuestion($question)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'question.update', array(
                'id' => $question,
                'text' => 'super texte de fou deux',
                'component' => 2
            ));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddQuestion
     */
    public function testDeleteQuestion($question)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'question.delete', array(
                'id' => $question,
            ));
    
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
