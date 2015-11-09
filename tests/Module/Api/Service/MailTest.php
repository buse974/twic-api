<?php

namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class MailTest extends AbstractService
{
	public static function setUpBeforeClass()
	{
		system('phing -q reset-db deploy-db');
		
		parent::setUpBeforeClass();
	}
    
   /* public function testCanTest()
    {
        $this->setIdentity(1);
    
        $datas = $this->jsonRpc('user.test', array());
        
        print_r($datas);
        
        exit();
    }*/
	
	public function testCanSend()
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.send', array(
				'suject' => 'suject', 
				'content' => 'content', 
				'receiver' => array(array('user' => 2, 'type' => 'to'), 
						      array('user' => 3, 'type' => 'cc'), 
						      array('user' => 4, 'type' => 'cci') ),
				'documents' => array('token1', 'token2')));
		
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals(count($datas['result']) , 2); 
		$this->assertEquals($datas['result']['mail'] , 1); 
		$this->assertEquals(is_string($datas['result']['group']) , true); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['result'];
	}
	
	public function testCanSave()
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.save', array(
				'suject' => 'suject2',
				'content' => 'content2',
				'receiver' => array(array('user' => 2, 'type' => 'to'),
						array('user' => 3, 'type' => 'cc'),
						array('user' => 4, 'type' => 'cci') ),
				'documents' => array('token1', 'token2')));

		$this->assertEquals(count($datas) , 3);
		$this->assertEquals(count($datas['result']) , 2);
		$this->assertEquals($datas['result']['mail'] , 2);
		$this->assertEquals(is_string($datas['result']['group']) , true); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['result'];
	}
	
	/**
	 * 
	 * @depends testCanSave
	 */
	public function testCanAddReveiver($id)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.addReceiver', array(
				'mail' => $id['mail'],
				'receiver' => array('user' => 5, 'type' => 'cc')
		));

		$this->assertEquals(count($datas) , 3);
		$this->assertEquals($datas['result'] , 9);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['result'];
	}
	
	/**
	 *
	 * @depends testCanAddReveiver
	 * @depends testCanSave
	 */
	public function testCanDeleteReveiver($id, $mess)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.deleteReceiver', array(
				'mail' => $mess,
				'receiver_id' => $id
		));
	
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	/**
	 *
	 * @depends testCanSave
	 */
	public function testCanAddDocument($mess)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.addDocument', array(
				'mail' => $mess['mail'],
				'document' => 'token3'
		));
	
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 5); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['result'];
	}
	
	/**
	 *
	 * @depends testCanAddDocument
	 * @depends testCanSave
	 */
	public function testCanDeleteDoc($id, $mess)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.deleteDocument', array(
				'mail' => $mess,
				'document_id' => $id
		));
	
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}

	/**
	 *
	 * @depends testCanSave
	 */
	public function testCanUpdateDraftMessage($mess)
	{
		$this->setIdentity(3);
		
		$datas = $this->jsonRpc('mail.update', array(
				'id' => $mess['mail'],
				'suject' => 'un suject update',
				'content' => 'un content update',
				'receiver' => array(array('type' => 'to', 'user' => 4), array('type' => 'cc', 'user' => 2)),
				'documents' => array('dd', 'ddd')
		));
	
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}

	/**
	 * @depends testCanSave
	 */
	public function testCanSendDraft($mess)
	{
		$this->setIdentity(3);
		$datas = $this->jsonRpc('mail.sendById', array(
				'id' => $mess['mail']
		));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals($datas['result'] , 1);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
		
		return $datas['result'];
	}
	
	/**
	 * @depends testCanSend
	 */
	public function testCanGetGroup($mess)
	{
		/* 
		 * FROM: 1 
		   TO  : 2 
		   MESS: 3
		   GRP : 1
		*/
		$this->setIdentity(1);
		$datas = $this->jsonRpc('mail.send', array(
				'suject' => 'suject1',
				'content' => 'content1',
				'receiver' => array(array('user' => 2, 'type' => 'to'),
						array('user' => 3, 'type' => 'cc'),
						array('user' => 4, 'type' => 'cci') ),
				'documents' => array('token1', 'token2'),
				'group_id' => $mess['group']));
		
		/*
		 * FROM: 2
		   TO  : 1
		   MESS: 4
		   GRP : 1
		 */
		$this->reset();
		$this->setIdentity(2);
		
		$datas = $this->jsonRpc('mail.send', array(
				'suject' => 'suject2',
				'content' => 'content2',
				'receiver' => array(array('user' => 1, 'type' => 'to'),
						array('user' => 3, 'type' => 'cc'),
						array('user' => 4, 'type' => 'cci') ),
				'documents' => array('token1', 'token2'),
				'group_id' => $mess['group']));

		/*
		 * FROM: 1
		   TO  : 2
		   MESS: 5
		   GRP : 3
		 */
		$this->reset();
		$this->setIdentity(1);
		
		$datas = $this->jsonRpc('mail.send', array(
				'suject' => 'sujectG3',
				'content' => 'contentG3',
				'receiver' => array(
						array('user' => 2, 'type' => 'to'),
						array('user' => 3, 'type' => 'cc'),
						array('user' => 4, 'type' => 'cci') 
				),
				'documents' => array('token1', 'token2')));
		
		/*
		 * FROM: 1
		   TO  : 2
		   MESS: 5
		   GRP : 3
		 */
		$this->reset();
		$this->setIdentity(2);
		
		$datas = $this->jsonRpc('mail.send', array(
				'suject' => 'sujectG3m2',
				'content' => 'contentG3m2',
				'receiver' => array(
						array('user' => 1, 'type' => 'to'),
						array('user' => 3, 'type' => 'cc'),
						array('user' => 4, 'type' => 'cci')
				),
				'documents' => array('doc1', 'doc2', 'doc3'),
				'documents' => array('token1', 'token2'),
				'group_id' => $datas['result']['group']
		));

		$mess = $datas['result'];
		
		$this->reset();
		$this->setIdentity(1);
		$datas = $this->jsonRpc('mail.getListPreview', array());

		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals(count($datas['result']) , 2); 
		$this->assertEquals($datas['result']['count'] , 2); 
		$this->assertEquals(count($datas['result']['list']) , 2); 
		$this->assertEquals(count($datas['result']['list'][0]) , 7); 
		$this->assertEquals(count($datas['result']['list'][0]['receiver']) , 4); 
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][0]) , 4); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['id'] , 17); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['type'] , "to"); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['user_id'] , 1); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['mail_id'] , 4); 
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][1]) , 4); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['id'] , 18); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['type'] , "cc"); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['user_id'] , 3); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['mail_id'] , 4); 
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][2]) , 4); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['id'] , 19); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['type'] , "cci"); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['user_id'] , 4); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['mail_id'] , 4); 
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][3]) , 4); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['id'] , 20); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['type'] , "from"); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['user_id'] , 2); 
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['mail_id'] , 4); 
		$this->assertEquals(count($datas['result']['list'][0]['mail_user']) , 4); 
		$this->assertEquals($datas['result']['list'][0]['mail_user']['id'] , 12); 
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_user']['created_date']) , true); 
		$this->assertEquals($datas['result']['list'][0]['mail_user']['deleted_date'] , null); 
		$this->assertEquals($datas['result']['list'][0]['mail_user']['read_date'] , null); 
		$this->assertEquals($datas['result']['list'][0]['id'] , 4); 
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_group_id']) , true); 
		$this->assertEquals($datas['result']['list'][0]['suject'] , "suject2"); 
		$this->assertEquals($datas['result']['list'][0]['content'] , "content2"); 
		$this->assertEquals(!empty($datas['result']['list'][0]['created_date']) , true); 
		$this->assertEquals(count($datas['result']['list'][1]) , 7); 
		$this->assertEquals(count($datas['result']['list'][1]['receiver']) , 4); 
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][0]) , 4); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['id'] , 25); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['type'] , "to"); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['user_id'] , 1); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['mail_id'] , 6); 
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][1]) , 4); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['id'] , 26); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['type'] , "cc"); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['user_id'] , 3); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['mail_id'] , 6); 
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][2]) , 4); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['id'] , 27); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['type'] , "cci"); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['user_id'] , 4); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['mail_id'] , 6); 
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][3]) , 4); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['id'] , 28); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['type'] , "from"); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['user_id'] , 2); 
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['mail_id'] , 6); 
		$this->assertEquals(count($datas['result']['list'][1]['mail_user']) , 4); 
		$this->assertEquals($datas['result']['list'][1]['mail_user']['id'] , 20); 
		$this->assertEquals(!empty($datas['result']['list'][1]['mail_user']['created_date']) , true); 
		$this->assertEquals($datas['result']['list'][1]['mail_user']['deleted_date'] , null); 
		$this->assertEquals($datas['result']['list'][1]['mail_user']['read_date'] , null); 
		$this->assertEquals($datas['result']['list'][1]['id'] , 6); 
		$this->assertEquals(!empty($datas['result']['list'][1]['mail_group_id']) , true); 
		$this->assertEquals($datas['result']['list'][1]['suject'] , "sujectG3m2"); 
		$this->assertEquals($datas['result']['list'][1]['content'] , "contentG3m2"); 
		$this->assertEquals(!empty($datas['result']['list'][1]['created_date']) , true); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	
		return $mess;
	}
	
	/**
	 * 
	 * @depends testCanGetGroup
	 */
	public function testCanGetListByGroup($mess)
	{
		$this->setIdentity(1);
		$datas = $this->jsonRpc('mail.getListByGroup', array('group' => $mess['group']));

		$this->assertEquals(count($datas) , 3);
		$this->assertEquals(count($datas['result']) , 2);
		$this->assertEquals($datas['result']['count'] , 2);
		$this->assertEquals(count($datas['result']['list']) , 2);
		$this->assertEquals(count($datas['result']['list'][0]) , 8);
		$this->assertEquals(count($datas['result']['list'][0]['receiver']) , 4);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][0]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['id'] , 25);
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['type'] , "to");
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['user_id'] , 1);
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['mail_id'] , 6);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][1]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['id'] , 26);
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['type'] , "cc");
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['user_id'] , 3);
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['mail_id'] , 6);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][2]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['id'] , 27);
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['type'] , "cci");
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['user_id'] , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['mail_id'] , 6);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][3]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['id'] , 28);
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['type'] , "from");
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['user_id'] , 2);
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['mail_id'] , 6);
		$this->assertEquals(count($datas['result']['list'][0]['mail_user']) , 4);
		$this->assertEquals($datas['result']['list'][0]['mail_user']['id'] , 20);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_user']['created_date']) , true);
		$this->assertEquals($datas['result']['list'][0]['mail_user']['deleted_date'] , null);
		$this->assertEquals($datas['result']['list'][0]['mail_user']['read_date'] , null);
		$this->assertEquals(count($datas['result']['list'][0]['mail_document']) , 2);
		$this->assertEquals(count($datas['result']['list'][0]['mail_document'][0]) , 4);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][0]['id'] , 14);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][0]['token'] , "token1");
		$this->assertEquals($datas['result']['list'][0]['mail_document'][0]['mail_id'] , 6);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_document'][0]['created_date']) , true);
		$this->assertEquals(count($datas['result']['list'][0]['mail_document'][1]) , 4);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][1]['id'] , 15);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][1]['token'] , "token2");
		$this->assertEquals($datas['result']['list'][0]['mail_document'][1]['mail_id'] , 6);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_document'][1]['created_date']) , true);
		$this->assertEquals($datas['result']['list'][0]['id'] , 6);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_group_id']) , true);
		$this->assertEquals($datas['result']['list'][0]['suject'] , "sujectG3m2");
		$this->assertEquals($datas['result']['list'][0]['content'] , "contentG3m2");
		$this->assertEquals(!empty($datas['result']['list'][0]['created_date']) , true);
		$this->assertEquals(count($datas['result']['list'][1]) , 8);
		$this->assertEquals(count($datas['result']['list'][1]['receiver']) , 4);
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][0]) , 4);
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['id'] , 21);
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['type'] , "to");
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['user_id'] , 2);
		$this->assertEquals($datas['result']['list'][1]['receiver'][0]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][1]) , 4);
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['id'] , 22);
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['type'] , "cc");
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['user_id'] , 3);
		$this->assertEquals($datas['result']['list'][1]['receiver'][1]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][2]) , 4);
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['id'] , 23);
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['type'] , "cci");
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['user_id'] , 4);
		$this->assertEquals($datas['result']['list'][1]['receiver'][2]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][1]['receiver'][3]) , 4);
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['id'] , 24);
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['type'] , "from");
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['user_id'] , 1);
		$this->assertEquals($datas['result']['list'][1]['receiver'][3]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][1]['mail_user']) , 4);
		$this->assertEquals($datas['result']['list'][1]['mail_user']['id'] , 19);
		$this->assertEquals(!empty($datas['result']['list'][1]['mail_user']['created_date']) , true);
		$this->assertEquals($datas['result']['list'][1]['mail_user']['deleted_date'] , null);
		$this->assertEquals($datas['result']['list'][1]['mail_user']['read_date'] , null);
		$this->assertEquals(count($datas['result']['list'][1]['mail_document']) , 2);
		$this->assertEquals(count($datas['result']['list'][1]['mail_document'][0]) , 4);
		$this->assertEquals($datas['result']['list'][1]['mail_document'][0]['id'] , 12);
		$this->assertEquals($datas['result']['list'][1]['mail_document'][0]['token'] , "token1");
		$this->assertEquals($datas['result']['list'][1]['mail_document'][0]['mail_id'] , 5);
		$this->assertEquals(!empty($datas['result']['list'][1]['mail_document'][0]['created_date']) , true);
		$this->assertEquals(count($datas['result']['list'][1]['mail_document'][1]) , 4);
		$this->assertEquals($datas['result']['list'][1]['mail_document'][1]['id'] , 13);
		$this->assertEquals($datas['result']['list'][1]['mail_document'][1]['token'] , "token2");
		$this->assertEquals($datas['result']['list'][1]['mail_document'][1]['mail_id'] , 5);
		$this->assertEquals(!empty($datas['result']['list'][1]['mail_document'][1]['created_date']) , true);
		$this->assertEquals($datas['result']['list'][1]['id'] , 5);
		$this->assertEquals(!empty($datas['result']['list'][1]['mail_group_id']) , true);
		$this->assertEquals($datas['result']['list'][1]['suject'] , "sujectG3");
		$this->assertEquals($datas['result']['list'][1]['content'] , "contentG3");
		$this->assertEquals(!empty($datas['result']['list'][1]['created_date']) , true);
		$this->assertEquals($datas['id'] , 1);
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	/**
	 * 
	 * @depends testCanGetGroup
	 */
	public function testCanDeleteMessage($mess)
	{
		$this->setIdentity(1);
		$datas = $this->jsonRpc('mail.delete', array('id' => $mess['mail']));
		
		$this->assertEquals(count($datas) , 3); 
		$this->assertEquals($datas['result'] , 1); 
		$this->assertEquals($datas['id'] , 1); 
		$this->assertEquals($datas['jsonrpc'] , 2.0);
	}
	
	/**
	 * @depends testCanGetGroup
	 * @depends testCanDeleteMessage
	 */
	public function testCanGetListByGroupAfterDeleteMesage($mess)
	{
		$this->setIdentity(1);
		$datas = $this->jsonRpc('mail.getListByGroup', array('group' => $mess['group']));
	
		$this->assertEquals(count($datas) , 3);
		$this->assertEquals(count($datas['result']) , 2);
		$this->assertEquals($datas['result']['count'] , 1);
		$this->assertEquals(count($datas['result']['list']) , 1);
		$this->assertEquals(count($datas['result']['list'][0]) , 8);
		$this->assertEquals(count($datas['result']['list'][0]['receiver']) , 4);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][0]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['id'] , 21);
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['type'] , "to");
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['user_id'] , 2);
		$this->assertEquals($datas['result']['list'][0]['receiver'][0]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][1]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['id'] , 22);
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['type'] , "cc");
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['user_id'] , 3);
		$this->assertEquals($datas['result']['list'][0]['receiver'][1]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][2]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['id'] , 23);
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['type'] , "cci");
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['user_id'] , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][2]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][0]['receiver'][3]) , 4);
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['id'] , 24);
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['type'] , "from");
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['user_id'] , 1);
		$this->assertEquals($datas['result']['list'][0]['receiver'][3]['mail_id'] , 5);
		$this->assertEquals(count($datas['result']['list'][0]['mail_user']) , 4);
		$this->assertEquals($datas['result']['list'][0]['mail_user']['id'] , 19);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_user']['created_date']) , true);
		$this->assertEquals($datas['result']['list'][0]['mail_user']['deleted_date'] , null);
		$this->assertEquals($datas['result']['list'][0]['mail_user']['read_date'] , null);
		$this->assertEquals(count($datas['result']['list'][0]['mail_document']) , 2);
		$this->assertEquals(count($datas['result']['list'][0]['mail_document'][0]) , 4);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][0]['id'] , 12);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][0]['token'] , "token1");
		$this->assertEquals($datas['result']['list'][0]['mail_document'][0]['mail_id'] , 5);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_document'][0]['created_date']) , true);
		$this->assertEquals(count($datas['result']['list'][0]['mail_document'][1]) , 4);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][1]['id'] , 13);
		$this->assertEquals($datas['result']['list'][0]['mail_document'][1]['token'] , "token2");
		$this->assertEquals($datas['result']['list'][0]['mail_document'][1]['mail_id'] , 5);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_document'][1]['created_date']) , true);
		$this->assertEquals($datas['result']['list'][0]['id'] , 5);
		$this->assertEquals(!empty($datas['result']['list'][0]['mail_group_id']) , true);
		$this->assertEquals($datas['result']['list'][0]['suject'] , "sujectG3");
		$this->assertEquals($datas['result']['list'][0]['content'] , "contentG3");
		$this->assertEquals(!empty($datas['result']['list'][0]['created_date']) , true);
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
	
		$identityMock->expects($this->any())
		             ->method('toArray')
		             ->will($this->returnValue(['id' => $id, 'token' => ''+$id+'token']));
		
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