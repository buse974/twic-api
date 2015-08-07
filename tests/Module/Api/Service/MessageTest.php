<?php

namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class MessageTest extends AbstractService
{
	public static function setUpBeforeClass()
	{
		system('phing -q reset-db deploy-db');
		
		parent::setUpBeforeClass();
	}
    

	public function testCanSendMessage()
	{
		$this->setIdentity(1);
		
		$data = $this->jsonRpc('message.send', array(
				'to' => array(2,3),
				'text' => 'super message qwerty'
		));
		$this->assertEquals(count($data) , 3);
		$this->assertEquals(count($data['result']) , 8);
		$this->assertEquals(count($data['result']['message']) , 4);
		$this->assertEquals($data['result']['message']['id'] , 1);
		$this->assertEquals($data['result']['message']['text'] , "super message qwerty");
		$this->assertEquals($data['result']['message']['token'] , null);
		$this->assertEquals(!empty($data['result']['message']['created_date']) , true);
		$this->assertEquals(count($data['result']['user']) , 4);
		$this->assertEquals($data['result']['user']['id'] , 1);
		$this->assertEquals($data['result']['user']['firstname'] , "Paul");
		$this->assertEquals($data['result']['user']['lastname'] , "Boussekey");
		$this->assertEquals($data['result']['user']['avatar'] , null);
		$this->assertEquals($data['result']['id'] , 1);
		$this->assertEquals($data['result']['conversation_id'] , 1);
                $this->assertEquals($data['result']['from_id'] , 1); 
		$this->assertEquals($data['result']['user_id'] , 1);
		$this->assertEquals($data['result']['read_date'] , null);
		$this->assertEquals(!empty($data['result']['created_date']) , true);
		$this->assertEquals($data['id'] , 1);
		$this->assertEquals($data['jsonrpc'] , 2.0);
	}
	

	public function testCanSendMessageTwo()
	{
		$this->setIdentity(2);
		
		$data = $this->jsonRpc('message.send', array(
				'to' => 3,
				'text' => 'super message deux qwerty 1'
		));
	
		return $data['result'];
	}
	

	public function testCanSendMessagethree()
	{
		$this->setIdentity(3);
		
		$data = $this->jsonRpc('message.send', array(
				'to' => 2,
				'text' => 'super message un azerty 2'
		));
	}
	
	/**
	 * @depends testCanSendMessageTwo
	 */
	public function testCanSendMessagethreebis($conv)
	{
		$this->setIdentity(2);
		$data = $this->jsonRpc('message.send', array(
				'conversation' => $conv['conversation_id'],
				'text' => 'dernier message'
		));
	}
	
	public function testCanSendMessageFoor()
	{
		$this->setIdentity(4);
		
		$data = $this->jsonRpc('message.send', array(
				'to' => 5,
				'text' => 'super message un azerty 2'
		));
	}
	
	
	/**
	 * @depends testCanSendMessageTwo
	 */
	public function testCanGetList($conv)
	{
		$this->setIdentity(2);
		
		$data = $this->jsonRpc('message.getList', array(
				'conversation' => $conv['conversation_id']
		));

		$this->assertEquals(count($data) , 3);
		$this->assertEquals(count($data['result']) , 2);
		$this->assertEquals(count($data['result']['list']) , 3);
		$this->assertEquals(count($data['result']['list'][0]) , 8);
		$this->assertEquals(count($data['result']['list'][0]['message']) , 4);
		$this->assertEquals($data['result']['list'][0]['message']['id'] , 2);
		$this->assertEquals($data['result']['list'][0]['message']['text'] , "super message deux qwerty 1");
		$this->assertEquals($data['result']['list'][0]['message']['token'] , null);
		$this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][0]['user']) , 4);
		$this->assertEquals($data['result']['list'][0]['user']['id'] , 2);
		$this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Xuan-Anh");
		$this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Hoang");
		$this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
		$this->assertEquals($data['result']['list'][0]['id'] , 4);
		$this->assertEquals($data['result']['list'][0]['conversation_id'] , 2);
		$this->assertEquals($data['result']['list'][0]['from_id'] , 2);
		$this->assertEquals($data['result']['list'][0]['user_id'] , 2);
		$this->assertEquals($data['result']['list'][0]['read_date'] , null);
		$this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][1]) , 8);
		$this->assertEquals(count($data['result']['list'][1]['message']) , 4);
		$this->assertEquals($data['result']['list'][1]['message']['id'] , 3);
		$this->assertEquals($data['result']['list'][1]['message']['text'] , "super message un azerty 2");
		$this->assertEquals($data['result']['list'][1]['message']['token'] , null);
		$this->assertEquals(!empty($data['result']['list'][1]['message']['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][1]['user']) , 4);
		$this->assertEquals($data['result']['list'][1]['user']['id'] , 3);
		$this->assertEquals($data['result']['list'][1]['user']['firstname'] , "Christophe");
		$this->assertEquals($data['result']['list'][1]['user']['lastname'] , "Robert");
		$this->assertEquals($data['result']['list'][1]['user']['avatar'] , null);
		$this->assertEquals($data['result']['list'][1]['id'] , 6);
		$this->assertEquals($data['result']['list'][1]['conversation_id'] , 2);
		$this->assertEquals($data['result']['list'][1]['from_id'] , 3);
		$this->assertEquals($data['result']['list'][1]['user_id'] , 2);
		$this->assertEquals($data['result']['list'][1]['read_date'] , null);
		$this->assertEquals(!empty($data['result']['list'][1]['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][2]) , 8);
		$this->assertEquals(count($data['result']['list'][2]['message']) , 4);
		$this->assertEquals($data['result']['list'][2]['message']['id'] , 4);
		$this->assertEquals($data['result']['list'][2]['message']['text'] , "dernier message");
		$this->assertEquals($data['result']['list'][2]['message']['token'] , null);
		$this->assertEquals(!empty($data['result']['list'][2]['message']['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][2]['user']) , 4);
		$this->assertEquals($data['result']['list'][2]['user']['id'] , 2);
		$this->assertEquals($data['result']['list'][2]['user']['firstname'] , "Xuan-Anh");
		$this->assertEquals($data['result']['list'][2]['user']['lastname'] , "Hoang");
		$this->assertEquals($data['result']['list'][2]['user']['avatar'] , null);
		$this->assertEquals($data['result']['list'][2]['id'] , 8);
		$this->assertEquals($data['result']['list'][2]['conversation_id'] , 2);
		$this->assertEquals($data['result']['list'][2]['from_id'] , 2);
		$this->assertEquals($data['result']['list'][2]['user_id'] , 2);
		$this->assertEquals($data['result']['list'][2]['read_date'] , null);
		$this->assertEquals(!empty($data['result']['list'][2]['created_date']) , true);
		$this->assertEquals($data['result']['count'] , 3);
		$this->assertEquals($data['id'] , 1);
		$this->assertEquals($data['jsonrpc'] , 2.0);


	}
	
	public function testCanGetListConversation()
	{
		$this->setIdentity(2);
		
		$data = $this->jsonRpc('message.getListConversation', array());
	
		$this->assertEquals(count($data) , 3);
		$this->assertEquals(count($data['result']) , 2);
		$this->assertEquals(count($data['result']['list']) , 2);
		$this->assertEquals(count($data['result']['list'][0]) , 8);
		$this->assertEquals(count($data['result']['list'][0]['message']) , 4);
		$this->assertEquals($data['result']['list'][0]['message']['id'] , 1);
		$this->assertEquals($data['result']['list'][0]['message']['text'] , "super message qwerty");
		$this->assertEquals($data['result']['list'][0]['message']['token'] , null);
		$this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][0]['user']) , 4);
		$this->assertEquals($data['result']['list'][0]['user']['id'] , 1);
		$this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Paul");
		$this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Boussekey");
		$this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
		$this->assertEquals($data['result']['list'][0]['id'] , 2);
		$this->assertEquals($data['result']['list'][0]['conversation_id'] , 1);
		$this->assertEquals($data['result']['list'][0]['from_id'] , 1);
		$this->assertEquals($data['result']['list'][0]['user_id'] , 2);
		$this->assertEquals($data['result']['list'][0]['read_date'] , null);
		$this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][1]) , 8);
		$this->assertEquals(count($data['result']['list'][1]['message']) , 4);
		$this->assertEquals($data['result']['list'][1]['message']['id'] , 4);
		$this->assertEquals($data['result']['list'][1]['message']['text'] , "dernier message");
		$this->assertEquals($data['result']['list'][1]['message']['token'] , null);
		$this->assertEquals(!empty($data['result']['list'][1]['message']['created_date']) , true);
		$this->assertEquals(count($data['result']['list'][1]['user']) , 4);
		$this->assertEquals($data['result']['list'][1]['user']['id'] , 2);
		$this->assertEquals($data['result']['list'][1]['user']['firstname'] , "Xuan-Anh");
		$this->assertEquals($data['result']['list'][1]['user']['lastname'] , "Hoang");
		$this->assertEquals($data['result']['list'][1]['user']['avatar'] , null);
		$this->assertEquals($data['result']['list'][1]['id'] , 8);
		$this->assertEquals($data['result']['list'][1]['conversation_id'] , 2);
		$this->assertEquals($data['result']['list'][1]['from_id'] , 2);
		$this->assertEquals($data['result']['list'][1]['user_id'] , 2);
		$this->assertEquals($data['result']['list'][1]['read_date'] , null);
		$this->assertEquals(!empty($data['result']['list'][1]['created_date']) , true);
		$this->assertEquals($data['result']['count'] , 2);
		$this->assertEquals($data['id'] , 1);
		$this->assertEquals($data['jsonrpc'] , 2.0);
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
	
		$identityMock->expects($this->any())
		             ->method('toArray')
		             ->will($this->returnValue(array('id' => $id)));
		
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