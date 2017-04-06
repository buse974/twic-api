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
        $data = $this->jsonRpc('message.send', ['to' =>[2,3],'text' => 'super message qwerty']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['message_id'] , 1);
        $this->assertEquals($data['result']['conversation_id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['result'];
    }

    public function testCanSendMessageTwo()
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('message.send', ['to' => 3,'text' => 'super message deux qwerty 1']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['message_id'] , 2);
        $this->assertEquals($data['result']['conversation_id'] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['result'];
    }

    public function testCanSendMessagethree()
    {
        $this->setIdentity(3);
        $data = $this->jsonRpc('message.send', ['to' => 2,'text' => 'super message un azerty 2']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['message_id'] , 3);
        $this->assertEquals($data['result']['conversation_id'] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['result'];
    }

    /**
     * @depends testCanSendMessageTwo
     */
    public function testCanSendMessagethreebis($conv)
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('message.send', [
          'conversation_id' => $conv['conversation_id'], 'text' => 'dernier message']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['message_id'] , 4);
        $this->assertEquals($data['result']['conversation_id'] , 2);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanSendMessageTwo
     */
    public function testCanGetIdConv($conv)
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('conversation.getIdByUser', [
          'user' => [3]
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , $conv['conversation_id']);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanSendMessageTwo
     */
    public function testCanGet($conv)
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('conversation.get', [
          'id' => $conv
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][2]) , 5);
        $this->assertEquals(count($data['result'][2]['message']) , 4);
        $this->assertEquals($data['result'][2]['message']['id'] , 4);
        $this->assertEquals($data['result'][2]['message']['text'] , "dernier message");
        $this->assertEquals($data['result'][2]['message']['token'] , null);
        $this->assertEquals(!empty($data['result'][2]['message']['created_date']) , true);
        $this->assertEquals(count($data['result'][2]['users']) , 2);
        $this->assertEquals($data['result'][2]['users'][0] , 2);
        $this->assertEquals($data['result'][2]['users'][1] , 3);
        $this->assertEquals($data['result'][2]['id'] , 2);
        $this->assertEquals($data['result'][2]['type'] , 2);
        $this->assertEquals(!empty($data['result'][2]['created_date']) , true);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanSendMessageTwo
     */
    public function testCanGetUnread($conv)
    {
      $this->setIdentity(2);
      $data = $this->jsonRpc('conversation.getListId', [
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 2);
      $this->assertEquals(count($data['result'][0]) , 4);
      $this->assertEquals(count($data['result'][0]['message']) , 1);
      $this->assertEquals($data['result'][0]['message']['id'] , 4);
      $this->assertEquals(count($data['result'][0]['users']) , 2);
      $this->assertEquals($data['result'][0]['users'][0] , 2);
      $this->assertEquals($data['result'][0]['users'][1] , 3);
      $this->assertEquals($data['result'][0]['id'] , 2);
      $this->assertEquals($data['result'][0]['type'] , 2);
      $this->assertEquals(count($data['result'][1]) , 4);
      $this->assertEquals(count($data['result'][1]['message']) , 1);
      $this->assertEquals($data['result'][1]['message']['id'] , 1);
      $this->assertEquals(count($data['result'][1]['users']) , 3);
      $this->assertEquals($data['result'][1]['users'][0] , 1);
      $this->assertEquals($data['result'][1]['users'][1] , 2);
      $this->assertEquals($data['result'][1]['users'][2] , 3);
      $this->assertEquals($data['result'][1]['id'] , 1);
      $this->assertEquals($data['result'][1]['type'] , 2);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testCanSendMessageunadeux()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('message.send', ['to' => 2,'text' => 'super message un azerty 2']);
    }

    /**
     * @depends testCanSendMessageunadeux
     */
    public function testCanGetUnreadContact()
    {
      $this->setIdentity(2);
      $data = $this->jsonRpc('conversation.getListId', [
        'contact' => true
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 1);
      $this->assertEquals(count($data['result'][0]) , 4);
      $this->assertEquals(count($data['result'][0]['message']) , 1);
      $this->assertEquals($data['result'][0]['message']['id'] , 5);
      $this->assertEquals(count($data['result'][0]['users']) , 2);
      $this->assertEquals($data['result'][0]['users'][0] , 1);
      $this->assertEquals($data['result'][0]['users'][1] , 2);
      $this->assertEquals($data['result'][0]['id'] , 3);
      $this->assertEquals($data['result'][0]['type'] , 2);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanSendMessageunadeux
     */
    public function testCanGetUnreadContactFalse()
    {
      $this->setIdentity(2);
      $data = $this->jsonRpc('conversation.getListId', [
        'contact' => false,
      ]);

      $this->assertEquals(count($data) , 3);
      $this->assertEquals($data['id'] , 1);
      $this->assertEquals(count($data['result']) , 2);
      $this->assertEquals(count($data['result'][0]) , 4);
      $this->assertEquals(count($data['result'][0]['message']) , 1);
      $this->assertEquals($data['result'][0]['message']['id'] , 4);
      $this->assertEquals(count($data['result'][0]['users']) , 2);
      $this->assertEquals($data['result'][0]['users'][0] , 2);
      $this->assertEquals($data['result'][0]['users'][1] , 3);
      $this->assertEquals($data['result'][0]['id'] , 2);
      $this->assertEquals($data['result'][0]['type'] , 2);
      $this->assertEquals(count($data['result'][1]) , 4);
      $this->assertEquals(count($data['result'][1]['message']) , 1);
      $this->assertEquals($data['result'][1]['message']['id'] , 1);
      $this->assertEquals(count($data['result'][1]['users']) , 3);
      $this->assertEquals($data['result'][1]['users'][0] , 1);
      $this->assertEquals($data['result'][1]['users'][1] , 2);
      $this->assertEquals($data['result'][1]['users'][2] , 3);
      $this->assertEquals($data['result'][1]['id'] , 1);
      $this->assertEquals($data['result'][1]['type'] , 2);
      $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanSendMessageTwo
     */
    public function testCanGetList($conv)
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.getList', ['conversation_id' => $conv['conversation_id']]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 3);
        $this->assertEquals(count($data['result']['list'][4]) , 5);
        $this->assertEquals(count($data['result']['list'][4]['message_user']) , 1);
        $this->assertEquals(!empty($data['result']['list'][4]['message_user']['read_date']) , true);
        $this->assertEquals($data['result']['list'][4]['id'] , 4);
        $this->assertEquals($data['result']['list'][4]['text'] , "dernier message");
        $this->assertEquals($data['result']['list'][4]['token'] , null);
        $this->assertEquals(!empty($data['result']['list'][4]['created_date']) , true);
        $this->assertEquals(count($data['result']['list'][3]) , 5);
        $this->assertEquals(count($data['result']['list'][3]['message_user']) , 1);
        $this->assertEquals($data['result']['list'][3]['message_user']['read_date'] , null);
        $this->assertEquals($data['result']['list'][3]['id'] , 3);
        $this->assertEquals($data['result']['list'][3]['text'] , "super message un azerty 2");
        $this->assertEquals($data['result']['list'][3]['token'] , null);
        $this->assertEquals(!empty($data['result']['list'][3]['created_date']) , true);
        $this->assertEquals(count($data['result']['list'][2]) , 5);
        $this->assertEquals(count($data['result']['list'][2]['message_user']) , 1);
        $this->assertEquals(!empty($data['result']['list'][2]['message_user']['read_date']) , true);
        $this->assertEquals($data['result']['list'][2]['id'] , 2);
        $this->assertEquals($data['result']['list'][2]['text'] , "super message deux qwerty 1");
        $this->assertEquals($data['result']['list'][2]['token'] , null);
        $this->assertEquals(!empty($data['result']['list'][2]['created_date']) , true);
        $this->assertEquals($data['result']['count'] , 3);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testCanSendMessageTwo
     */
    /*public function testGetFullList($conv)
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.getFullList', array('conversation_id' => $conv['conversation_id']));

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals(count($data['result'][0]), 9);
        $this->assertEquals(count($data['result'][0]['message_user']), 1);
        $this->assertEquals($data['result'][0]['message_user']['from_id'], 2);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['title'], null);
        $this->assertEquals($data['result'][0]['text'], "super message deux qwerty 1");
        $this->assertEquals($data['result'][0]['token'], null);
        $this->assertEquals($data['result'][0]['is_draft'], 0);
        $this->assertEquals($data['result'][0]['type'], 2);
        $this->assertEquals($data['result'][0]['conversation_id'], 2);
        $this->assertEquals(!empty($data['result'][0]['created_date']), true);
        $this->assertEquals(count($data['result'][1]), 9);
        $this->assertEquals(count($data['result'][1]['message_user']), 1);
        $this->assertEquals($data['result'][1]['message_user']['from_id'], 3);
        $this->assertEquals($data['result'][1]['id'], 3);
        $this->assertEquals($data['result'][1]['title'], null);
        $this->assertEquals($data['result'][1]['text'], "super message un azerty 2");
        $this->assertEquals($data['result'][1]['token'], null);
        $this->assertEquals($data['result'][1]['is_draft'], 0);
        $this->assertEquals($data['result'][1]['type'], 2);
        $this->assertEquals($data['result'][1]['conversation_id'], 2);
        $this->assertEquals(!empty($data['result'][1]['created_date']), true);
        $this->assertEquals(count($data['result'][2]), 9);
        $this->assertEquals(count($data['result'][2]['message_user']), 1);
        $this->assertEquals($data['result'][2]['message_user']['from_id'], 2);
        $this->assertEquals($data['result'][2]['id'], 4);
        $this->assertEquals($data['result'][2]['title'], null);
        $this->assertEquals($data['result'][2]['text'], "dernier message");
        $this->assertEquals($data['result'][2]['token'], null);
        $this->assertEquals($data['result'][2]['is_draft'], 0);
        $this->assertEquals($data['result'][2]['type'], 2);
        $this->assertEquals($data['result'][2]['conversation_id'], 2);
        $this->assertEquals(!empty($data['result'][2]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testCanSendMessageTwo
     */
    /*public function testCanGetList($conv)
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.getList', array('conversation' => $conv['conversation_id']));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 3);
        $this->assertEquals(count($data['result']['list'][0]), 9);
        $this->assertEquals(count($data['result']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 4);
        $this->assertEquals($data['result']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['list'][0]['message']['text'], "dernier message");
        $this->assertEquals($data['result']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'], 2);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 8);
        $this->assertEquals($data['result']['list'][0]['message_id'], 4);
        $this->assertEquals($data['result']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][0]['from_id'], 2);
        $this->assertEquals($data['result']['list'][0]['user_id'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['read_date']), true);
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]), 9);
        $this->assertEquals(count($data['result']['list'][1]['message']), 10);
        $this->assertEquals(count($data['result']['list'][1]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][1]['message']['id'], 3);
        $this->assertEquals($data['result']['list'][1]['message']['title'], null);
        $this->assertEquals($data['result']['list'][1]['message']['text'], "super message un azerty 2");
        $this->assertEquals($data['result']['list'][1]['message']['token'], null);
        $this->assertEquals($data['result']['list'][1]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][1]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]['user']), 5);
        $this->assertEquals($data['result']['list'][1]['user']['id'], 3);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][1]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['id'], 6);
        $this->assertEquals($data['result']['list'][1]['message_id'], 3);
        $this->assertEquals($data['result']['list'][1]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][1]['from_id'], 3);
        $this->assertEquals($data['result']['list'][1]['user_id'], 2);
        $this->assertEquals($data['result']['list'][1]['read_date'], null);
        $this->assertEquals(!empty($data['result']['list'][1]['created_date']), true);
        $this->assertEquals(count($data['result']['list'][2]), 9);
        $this->assertEquals(count($data['result']['list'][2]['message']), 10);
        $this->assertEquals(count($data['result']['list'][2]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][2]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][2]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][2]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][2]['message']['from'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][2]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][2]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][2]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][2]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][2]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][2]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][2]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][2]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][2]['message']['id'], 2);
        $this->assertEquals($data['result']['list'][2]['message']['title'], null);
        $this->assertEquals($data['result']['list'][2]['message']['text'], "super message deux qwerty 1");
        $this->assertEquals($data['result']['list'][2]['message']['token'], null);
        $this->assertEquals($data['result']['list'][2]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][2]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][2]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][2]['user']), 5);
        $this->assertEquals($data['result']['list'][2]['user']['id'], 2);
        $this->assertEquals($data['result']['list'][2]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][2]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][2]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][2]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][2]['id'], 4);
        $this->assertEquals($data['result']['list'][2]['message_id'], 2);
        $this->assertEquals($data['result']['list'][2]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][2]['from_id'], 2);
        $this->assertEquals($data['result']['list'][2]['user_id'], 2);
        $this->assertEquals(!empty($data['result']['list'][2]['read_date']), true);
        $this->assertEquals(!empty($data['result']['list'][2]['created_date']), true);
        $this->assertEquals($data['result']['count'], 3);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanConversationGet()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('conversation.get', [
            'id' => 1,
        ]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 9);
        $this->assertEquals(count($data['result']['messages']), 2);
        $this->assertEquals(count($data['result']['messages']['list']), 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]), 9);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contact_state'], 3);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['firstname'], "Paul");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['from'][0]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to']), 2);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]), 15);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['contact_state'], 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]['school']), 5);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['school']['background'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['id'], 2);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['lastname'], "Hoang");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['nickname'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['birth_date'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['position'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['interest'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['avatar'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['ambassador'], null);
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]['roles']), 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['to'][1]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['messages']['list'][0]['message']['to'][1]['program']), 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['text'], "super message qwerty");
        $this->assertEquals($data['result']['messages']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['messages']['list'][0]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['messages']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['messages']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['messages']['list'][0]['id'], 2);
        $this->assertEquals($data['result']['messages']['list'][0]['message_id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['conversation_id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['from_id'], 1);
        $this->assertEquals($data['result']['messages']['list'][0]['user_id'], 2);
        $this->assertEquals($data['result']['messages']['list'][0]['read_date'], null);
        $this->assertEquals(!empty($data['result']['messages']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['messages']['count'], 1);
        $this->assertEquals(count($data['result']['users']), 3);
        $this->assertEquals(count($data['result']['users'][1]), 6);
        $this->assertEquals(count($data['result']['users'][1]['roles']), 1);
        $this->assertEquals($data['result']['users'][1]['roles'][0], "super_admin");
        $this->assertEquals($data['result']['users'][1]['id'], 1);
        $this->assertEquals($data['result']['users'][1]['firstname'], "Paul");
        $this->assertEquals($data['result']['users'][1]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['users'][1]['nickname'], null);
        $this->assertEquals($data['result']['users'][1]['avatar'], null);
        $this->assertEquals(count($data['result']['users'][2]), 6);
        $this->assertEquals(count($data['result']['users'][2]['roles']), 1);
        $this->assertEquals($data['result']['users'][2]['roles'][0], "admin");
        $this->assertEquals($data['result']['users'][2]['id'], 2);
        $this->assertEquals($data['result']['users'][2]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['users'][2]['lastname'], "Hoang");
        $this->assertEquals($data['result']['users'][2]['nickname'], null);
        $this->assertEquals($data['result']['users'][2]['avatar'], null);
        $this->assertEquals(count($data['result']['users'][3]), 6);
        $this->assertEquals(count($data['result']['users'][3]['roles']), 1);
        $this->assertEquals($data['result']['users'][3]['roles'][0], "academic");
        $this->assertEquals($data['result']['users'][3]['id'], 3);
        $this->assertEquals($data['result']['users'][3]['firstname'], "Christophe");
        $this->assertEquals($data['result']['users'][3]['lastname'], "Robert");
        $this->assertEquals($data['result']['users'][3]['nickname'], null);
        $this->assertEquals($data['result']['users'][3]['avatar'], null);
        $this->assertEquals($data['result']['id'], 1);
        $this->assertEquals($data['result']['name'], "Chat");
        $this->assertEquals($data['result']['type'], 2);
        $this->assertEquals($data['result']['token'], null);
        $this->assertEquals($data['result']['conversation_opt_id'], 1);
        $this->assertEquals(!empty($data['result']['created_date']), true);
        $this->assertEquals(count($data['result']['conversation_opt']), 9);
        $this->assertEquals($data['result']['conversation_opt']['id'], 1);
        $this->assertEquals($data['result']['conversation_opt']['item_id'], null);
        $this->assertEquals($data['result']['conversation_opt']['record'], 0);
        $this->assertEquals($data['result']['conversation_opt']['nb_user_autorecord'], 0);
        $this->assertEquals($data['result']['conversation_opt']['allow_intructor'], 0);
        $this->assertEquals($data['result']['conversation_opt']['has_eqcq'], 0);
        $this->assertEquals($data['result']['conversation_opt']['start_date'], null);
        $this->assertEquals($data['result']['conversation_opt']['duration'], null);
        $this->assertEquals($data['result']['conversation_opt']['rules'], null);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanGetListConversation()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.getListConversation', array());

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 2);
        $this->assertEquals(count($data['result']['list'][0]), 9);
        $this->assertEquals(count($data['result']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 4);
        $this->assertEquals($data['result']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['list'][0]['message']['text'], "dernier message");
        $this->assertEquals($data['result']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'], 2);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 8);
        $this->assertEquals($data['result']['list'][0]['message_id'], 4);
        $this->assertEquals($data['result']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][0]['from_id'], 2);
        $this->assertEquals($data['result']['list'][0]['user_id'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['read_date']), true);
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]), 9);
        $this->assertEquals(count($data['result']['list'][1]['message']), 10);
        $this->assertEquals(count($data['result']['list'][1]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['contact_state'], 3);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to']), 2);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]['program']), 0);
        $this->assertEquals($data['result']['list'][1]['message']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['title'], null);
        $this->assertEquals($data['result']['list'][1]['message']['text'], "super message qwerty");
        $this->assertEquals($data['result']['list'][1]['message']['token'], null);
        $this->assertEquals($data['result']['list'][1]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][1]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]['user']), 5);
        $this->assertEquals($data['result']['list'][1]['user']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][1]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['message_id'], 1);
        $this->assertEquals($data['result']['list'][1]['conversation_id'], 1);
        $this->assertEquals($data['result']['list'][1]['from_id'], 1);
        $this->assertEquals($data['result']['list'][1]['user_id'], 2);
        $this->assertEquals($data['result']['list'][1]['read_date'], null);
        $this->assertEquals(!empty($data['result']['list'][1]['created_date']), true);
        $this->assertEquals($data['result']['count'], 2);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanReadMessage()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.read', array('message' => 1));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanReadConversation()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('conversation.read', array('conversation' => 1));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanDeleteConversation()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('conversation.delete', array('conversation' => 1));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanAddNewConversation()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('conversation.add', array('users' => array()));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 4);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanAddSendMail()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('message.sendMail', array('document' => array(array('token' => 'token1','name' => 'name1'),array('token' => 'token2','name' => 'name2')),'title' => 'objet mail','to' => array(4,5),'text' => 'super message qwerty','draft' => true));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 9);
        $this->assertEquals(count($data['result']['message']), 10);
        $this->assertEquals(count($data['result']['message']['from']), 1);
        $this->assertEquals(count($data['result']['message']['from'][0]), 15);
        $this->assertEquals($data['result']['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['from'][0]['id'], 3);
        $this->assertEquals($data['result']['message']['from'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['message']['from'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['message']['from'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['from'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['message']['document']), 2);
        $this->assertEquals(count($data['result']['message']['document'][0]), 12);
        $this->assertEquals($data['result']['message']['document'][0]['id'], 5);
        $this->assertEquals($data['result']['message']['document'][0]['name'], "name1");
        $this->assertEquals($data['result']['message']['document'][0]['link'], null);
        $this->assertEquals($data['result']['message']['document'][0]['token'], "token1");
        $this->assertEquals($data['result']['message']['document'][0]['type'], null);
        $this->assertEquals(!empty($data['result']['message']['document'][0]['created_date']), true);
        $this->assertEquals($data['result']['message']['document'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['message']['document'][0]['updated_date'], null);
        $this->assertEquals($data['result']['message']['document'][0]['folder_id'], 1);
        $this->assertEquals($data['result']['message']['document'][0]['owner_id'], 3);
        $this->assertEquals($data['result']['message']['document'][0]['box_id'], null);
        $this->assertEquals($data['result']['message']['document'][0]['global'], 0);
        $this->assertEquals(count($data['result']['message']['document'][1]), 12);
        $this->assertEquals($data['result']['message']['document'][1]['id'], 6);
        $this->assertEquals($data['result']['message']['document'][1]['name'], "name2");
        $this->assertEquals($data['result']['message']['document'][1]['link'], null);
        $this->assertEquals($data['result']['message']['document'][1]['token'], "token2");
        $this->assertEquals($data['result']['message']['document'][1]['type'], null);
        $this->assertEquals(!empty($data['result']['message']['document'][1]['created_date']), true);
        $this->assertEquals($data['result']['message']['document'][1]['deleted_date'], null);
        $this->assertEquals($data['result']['message']['document'][1]['updated_date'], null);
        $this->assertEquals($data['result']['message']['document'][1]['folder_id'], 1);
        $this->assertEquals($data['result']['message']['document'][1]['owner_id'], 3);
        $this->assertEquals($data['result']['message']['document'][1]['box_id'], null);
        $this->assertEquals($data['result']['message']['document'][1]['global'], 0);
        $this->assertEquals(count($data['result']['message']['to']), 2);
        $this->assertEquals(count($data['result']['message']['to'][0]), 15);
        $this->assertEquals($data['result']['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['to'][0]['id'], 5);
        $this->assertEquals($data['result']['message']['to'][0]['firstname'], "Sébastien");
        $this->assertEquals($data['result']['message']['to'][0]['lastname'], "Sayegh");
        $this->assertEquals($data['result']['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['message']['to'][0]['email'], "ssayegh@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['to'][0]['roles'][0], "instructor");
        $this->assertEquals(count($data['result']['message']['to'][0]['program']), 0);
        $this->assertEquals(count($data['result']['message']['to'][1]), 15);
        $this->assertEquals($data['result']['message']['to'][1]['contact_state'], 0);
        $this->assertEquals($data['result']['message']['to'][1]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['to'][1]['school']), 5);
        $this->assertEquals($data['result']['message']['to'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['to'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['to'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['to'][1]['school']['background'], null);
        $this->assertEquals($data['result']['message']['to'][1]['id'], 4);
        $this->assertEquals($data['result']['message']['to'][1]['firstname'], "Salim");
        $this->assertEquals($data['result']['message']['to'][1]['lastname'], "Bendacha");
        $this->assertEquals($data['result']['message']['to'][1]['nickname'], null);
        $this->assertEquals($data['result']['message']['to'][1]['email'], "sbendacha@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][1]['birth_date'], null);
        $this->assertEquals($data['result']['message']['to'][1]['position'], null);
        $this->assertEquals($data['result']['message']['to'][1]['interest'], null);
        $this->assertEquals($data['result']['message']['to'][1]['avatar'], null);
        $this->assertEquals($data['result']['message']['to'][1]['ambassador'], null);
        $this->assertEquals(count($data['result']['message']['to'][1]['roles']), 1);
        $this->assertEquals($data['result']['message']['to'][1]['roles'][0], "student");
        $this->assertEquals(count($data['result']['message']['to'][1]['program']), 0);
        $this->assertEquals($data['result']['message']['id'], 6);
        $this->assertEquals($data['result']['message']['title'], "objet mail");
        $this->assertEquals($data['result']['message']['text'], "super message qwerty");
        $this->assertEquals($data['result']['message']['token'], null);
        $this->assertEquals($data['result']['message']['is_draft'], 1);
        $this->assertEquals($data['result']['message']['type'], 1);
        $this->assertEquals(!empty($data['result']['message']['created_date']), true);
        $this->assertEquals(count($data['result']['user']), 5);
        $this->assertEquals($data['result']['user']['id'], 3);
        $this->assertEquals($data['result']['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['user']['nickname'], null);
        $this->assertEquals($data['result']['user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 14);
        $this->assertEquals($data['result']['message_id'], 6);
        $this->assertEquals($data['result']['conversation_id'], 5);
        $this->assertEquals($data['result']['from_id'], 3);
        $this->assertEquals($data['result']['user_id'], 3);
        $this->assertEquals(!empty($data['result']['read_date']), true);
        $this->assertEquals(!empty($data['result']['created_date']), true);
        $this->assertEquals($data['jsonrpc'], 2.0);

        return $data['result']['message']['id'];
    }

    /**
     * @depends testCanAddSendMail
     */
    /*public function testCanAddSendMailUpdate($message_id)
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('message.sendMail', array('id' => $message_id,'to' => array(2,1),'title' => 'objet mail update','text' => 'super message qwerty','draft' => true));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 9);
        $this->assertEquals(count($data['result']['message']), 10);
        $this->assertEquals(count($data['result']['message']['from']), 1);
        $this->assertEquals(count($data['result']['message']['from'][0]), 15);
        $this->assertEquals($data['result']['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['from'][0]['id'], 3);
        $this->assertEquals($data['result']['message']['from'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['message']['from'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['message']['from'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['from'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['message']['document']), 2);
        $this->assertEquals(count($data['result']['message']['document'][0]), 12);
        $this->assertEquals($data['result']['message']['document'][0]['id'], 5);
        $this->assertEquals($data['result']['message']['document'][0]['name'], "name1");
        $this->assertEquals($data['result']['message']['document'][0]['link'], null);
        $this->assertEquals($data['result']['message']['document'][0]['token'], "token1");
        $this->assertEquals($data['result']['message']['document'][0]['type'], null);
        $this->assertEquals(!empty($data['result']['message']['document'][0]['created_date']), true);
        $this->assertEquals($data['result']['message']['document'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['message']['document'][0]['updated_date'], null);
        $this->assertEquals($data['result']['message']['document'][0]['folder_id'], 1);
        $this->assertEquals($data['result']['message']['document'][0]['owner_id'], 3);
        $this->assertEquals($data['result']['message']['document'][0]['box_id'], null);
        $this->assertEquals($data['result']['message']['document'][0]['global'], 0);
        $this->assertEquals(count($data['result']['message']['document'][1]), 12);
        $this->assertEquals($data['result']['message']['document'][1]['id'], 6);
        $this->assertEquals($data['result']['message']['document'][1]['name'], "name2");
        $this->assertEquals($data['result']['message']['document'][1]['link'], null);
        $this->assertEquals($data['result']['message']['document'][1]['token'], "token2");
        $this->assertEquals($data['result']['message']['document'][1]['type'], null);
        $this->assertEquals(!empty($data['result']['message']['document'][1]['created_date']), true);
        $this->assertEquals($data['result']['message']['document'][1]['deleted_date'], null);
        $this->assertEquals($data['result']['message']['document'][1]['updated_date'], null);
        $this->assertEquals($data['result']['message']['document'][1]['folder_id'], 1);
        $this->assertEquals($data['result']['message']['document'][1]['owner_id'], 3);
        $this->assertEquals($data['result']['message']['document'][1]['box_id'], null);
        $this->assertEquals($data['result']['message']['document'][1]['global'], 0);
        $this->assertEquals(count($data['result']['message']['to']), 2);
        $this->assertEquals(count($data['result']['message']['to'][0]), 15);
        $this->assertEquals($data['result']['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['message']['to'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['message']['to'][0]['id'], 2);
        $this->assertEquals($data['result']['message']['to'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['message']['to'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['message']['to'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['message']['to'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['message']['to'][0]['program']), 0);
        $this->assertEquals(count($data['result']['message']['to'][1]), 15);
        $this->assertEquals($data['result']['message']['to'][1]['contact_state'], 0);
        $this->assertEquals($data['result']['message']['to'][1]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['message']['to'][1]['school']), 5);
        $this->assertEquals($data['result']['message']['to'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['message']['to'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['message']['to'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['message']['to'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['message']['to'][1]['school']['background'], null);
        $this->assertEquals($data['result']['message']['to'][1]['id'], 1);
        $this->assertEquals($data['result']['message']['to'][1]['firstname'], "Paul");
        $this->assertEquals($data['result']['message']['to'][1]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['message']['to'][1]['nickname'], null);
        $this->assertEquals($data['result']['message']['to'][1]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['message']['to'][1]['birth_date'], null);
        $this->assertEquals($data['result']['message']['to'][1]['position'], null);
        $this->assertEquals($data['result']['message']['to'][1]['interest'], null);
        $this->assertEquals($data['result']['message']['to'][1]['avatar'], null);
        $this->assertEquals($data['result']['message']['to'][1]['ambassador'], null);
        $this->assertEquals(count($data['result']['message']['to'][1]['roles']), 1);
        $this->assertEquals($data['result']['message']['to'][1]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['message']['to'][1]['program']), 0);
        $this->assertEquals($data['result']['message']['id'], 6);
        $this->assertEquals($data['result']['message']['title'], "objet mail update");
        $this->assertEquals($data['result']['message']['text'], "super message qwerty");
        $this->assertEquals($data['result']['message']['token'], null);
        $this->assertEquals($data['result']['message']['is_draft'], 1);
        $this->assertEquals($data['result']['message']['type'], 1);
        $this->assertEquals(!empty($data['result']['message']['created_date']), true);
        $this->assertEquals(count($data['result']['user']), 5);
        $this->assertEquals($data['result']['user']['id'], 3);
        $this->assertEquals($data['result']['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['user']['nickname'], null);
        $this->assertEquals($data['result']['user']['avatar'], null);
        $this->assertEquals($data['result']['id'], 17);
        $this->assertEquals($data['result']['message_id'], 6);
        $this->assertEquals($data['result']['conversation_id'], 5);
        $this->assertEquals($data['result']['from_id'], 3);
        $this->assertEquals($data['result']['user_id'], 3);
        $this->assertEquals(!empty($data['result']['read_date']), true);
        $this->assertEquals(!empty($data['result']['created_date']), true);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanJoinConversation()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('conversation.join', array('conversation' => 5));

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals($data['result'][2], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanGetListTag()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.getListTag', array());

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals(count($data['result'][0]), 2);
        $this->assertEquals($data['result'][0]['tag'], "INBOX");
        $this->assertEquals($data['result'][0]['count'], 0);
        $this->assertEquals(count($data['result'][1]), 2);
        $this->assertEquals($data['result'][1]['tag'], "SENT");
        $this->assertEquals($data['result'][1]['count'], 0);
        $this->assertEquals(count($data['result'][2]), 2);
        $this->assertEquals($data['result'][2]['tag'], "DRAFT");
        $this->assertEquals($data['result'][2]['count'], 0);
        $this->assertEquals(count($data['result'][3]), 2);
        $this->assertEquals($data['result'][3]['tag'], "CHAT");
        $this->assertEquals($data['result'][3]['count'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testGetNbrMessageBySchool()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('message.getNbrMessage', array('school' => 1, 'day' => 30));

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals($data['result']['d'], 5);
        $this->assertEquals($data['result']['w'], 5);
        $this->assertEquals($data['result']['m'], 5);
        $this->assertEquals($data['result']['a'], 5);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanAddDeleteMessage()
    {
        $this->setIdentity(5);

        $data = $this->jsonRpc('message.delete', array('id' => 5));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanMessageGetListByType()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.getListConversation', array('tag' => 'INBOX'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 2);
        $this->assertEquals(count($data['result']['list'][0]), 9);
        $this->assertEquals(count($data['result']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['list'][0]['message']['text'], "super message un azerty 2");
        $this->assertEquals($data['result']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'], 3);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 6);
        $this->assertEquals($data['result']['list'][0]['message_id'], 3);
        $this->assertEquals($data['result']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][0]['from_id'], 3);
        $this->assertEquals($data['result']['list'][0]['user_id'], 2);
        $this->assertEquals($data['result']['list'][0]['read_date'], null);
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]), 9);
        $this->assertEquals(count($data['result']['list'][1]['message']), 10);
        $this->assertEquals(count($data['result']['list'][1]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['contact_state'], 3);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['from'][0]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['list'][1]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to']), 2);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]), 15);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]['school']), 5);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['school']['background'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['position'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['message']['to'][1]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][1]['message']['to'][1]['program']), 0);
        $this->assertEquals($data['result']['list'][1]['message']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['message']['title'], null);
        $this->assertEquals($data['result']['list'][1]['message']['text'], "super message qwerty");
        $this->assertEquals($data['result']['list'][1]['message']['token'], null);
        $this->assertEquals($data['result']['list'][1]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][1]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][1]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][1]['user']), 5);
        $this->assertEquals($data['result']['list'][1]['user']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][1]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][1]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['message_id'], 1);
        $this->assertEquals($data['result']['list'][1]['conversation_id'], 1);
        $this->assertEquals($data['result']['list'][1]['from_id'], 1);
        $this->assertEquals($data['result']['list'][1]['user_id'], 2);
        $this->assertEquals(!empty($data['result']['list'][1]['read_date']), true);
        $this->assertEquals(!empty($data['result']['list'][1]['created_date']), true);
        $this->assertEquals($data['result']['count'], 2);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanMessageGetListByTypeDraft()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('message.getListConversation', array('tag' => 'DRAFT'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 9);
        $this->assertEquals(count($data['result']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document']), 2);
        $this->assertEquals(count($data['result']['list'][0]['message']['document'][0]), 12);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['name'], "name1");
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['link'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['token'], "token1");
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['type'], null);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['document'][0]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['updated_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['folder_id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['owner_id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['box_id'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][0]['global'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document'][1]), 12);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['id'], 6);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['name'], "name2");
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['link'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['token'], "token2");
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['type'], null);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['document'][1]['created_date']), true);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['deleted_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['updated_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['folder_id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['owner_id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['box_id'], null);
        $this->assertEquals($data['result']['list'][0]['message']['document'][1]['global'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to']), 2);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][1]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][1]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][1]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][1]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][1]['program']), 0);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 6);
        $this->assertEquals($data['result']['list'][0]['message']['title'], "objet mail update");
        $this->assertEquals($data['result']['list'][0]['message']['text'], "super message qwerty");
        $this->assertEquals($data['result']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['list'][0]['message']['is_draft'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['type'], 1);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'], 3);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 17);
        $this->assertEquals($data['result']['list'][0]['message_id'], 6);
        $this->assertEquals($data['result']['list'][0]['conversation_id'], 5);
        $this->assertEquals($data['result']['list'][0]['from_id'], 3);
        $this->assertEquals($data['result']['list'][0]['user_id'], 3);
        $this->assertEquals(!empty($data['result']['list'][0]['read_date']), true);
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanMessageGetListByTypeSent()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('message.getListConversation', array('tag' => 'SENT'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 9);
        $this->assertEquals(count($data['result']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['list'][0]['message']['text'], "super message un azerty 2");
        $this->assertEquals($data['result']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'], 3);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 7);
        $this->assertEquals($data['result']['list'][0]['message_id'], 3);
        $this->assertEquals($data['result']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][0]['from_id'], 3);
        $this->assertEquals($data['result']['list'][0]['user_id'], 3);
        $this->assertEquals(!empty($data['result']['list'][0]['read_date']), true);
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanMessageGetListBySearch()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('message.getListConversation', array('search' => 'uan'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 1);
        $this->assertEquals(count($data['result']['list'][0]), 9);
        $this->assertEquals(count($data['result']['list'][0]['message']), 10);
        $this->assertEquals(count($data['result']['list'][0]['message']['from']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['contacts_count'], 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['id'], 2);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['from'][0]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][0]['message']['from'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['document']), 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to']), 1);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]), 15);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contact_state'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['contacts_count'], 0);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['school']), 5);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['school']['background'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['ambassador'], null);
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['message']['to'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['message']['to'][0]['program']), 0);
        $this->assertEquals($data['result']['list'][0]['message']['id'], 4);
        $this->assertEquals($data['result']['list'][0]['message']['title'], null);
        $this->assertEquals($data['result']['list'][0]['message']['text'], "dernier message");
        $this->assertEquals($data['result']['list'][0]['message']['token'], null);
        $this->assertEquals($data['result']['list'][0]['message']['is_draft'], 0);
        $this->assertEquals($data['result']['list'][0]['message']['type'], 2);
        $this->assertEquals(!empty($data['result']['list'][0]['message']['created_date']), true);
        $this->assertEquals(count($data['result']['list'][0]['user']), 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'], 2);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][0]['user']['nickname'], null);
        $this->assertEquals($data['result']['list'][0]['user']['avatar'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 9);
        $this->assertEquals($data['result']['list'][0]['message_id'], 4);
        $this->assertEquals($data['result']['list'][0]['conversation_id'], 2);
        $this->assertEquals($data['result']['list'][0]['from_id'], 2);
        $this->assertEquals($data['result']['list'][0]['user_id'], 3);
        $this->assertEquals($data['result']['list'][0]['read_date'], null);
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']), true);
        $this->assertEquals($data['result']['count'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanUnReadMessage()
    {
        $this->setIdentity(2);

        $data = $this->jsonRpc('message.unRead', array('message' => 1));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanUnReadConversation()
    {
        $this->setIdentity(3);

        $data = $this->jsonRpc('conversation.unRead', array('conversation' => 1));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }*/
}
