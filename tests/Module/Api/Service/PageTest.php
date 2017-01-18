<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class PageTest extends AbstractService
{
    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testPageAdd()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('page.add', [
            'title' => 'super title',
            'logo' => 'logo',
            'background' => 'background',
            'description' => 'description',
            'confidentiality' => 1,
            'type' => 'group',
            'admission' => 'free',
            'start_date' => '2015-00-00 00:00:00',
            'end_date' => '2016-00-00 00:00:00',
            'location' => 'location',
            'organization_id' => 1,
            'users' => [
                ['user_id' => 1,'role' => 'admin', 'state' => 'member'],
                ['user_id' => 2,'role' => 'admin', 'state' => 'member'],
                ['user_id' => 3,'role' => 'admin', 'state' => 'member'],
            ],
            'tags' => [
                'toto', 'tata', 'tutu'
            ],
            'docs' => [
                ['name' => 'name', 'link' => 'link', 'type' => 'type']
            ]
        ]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);

        return $data['id'];
    }

    /**
     * @depends testPageAdd
     */
    public function testPageGet($page_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('page.get', ['id' => $page_id]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 25);
        $this->assertEquals(count($data['result']['owner']) , 4);
        $this->assertEquals($data['result']['owner']['id'] , 1);
        $this->assertEquals($data['result']['owner']['text'] , "Morbi Corporation");
        $this->assertEquals($data['result']['owner']['img'] , null);
        $this->assertEquals($data['result']['owner']['type'] , "organization");
        $this->assertEquals(count($data['result']['organization']) , 3);
        $this->assertEquals($data['result']['organization']['id'] , 1);
        $this->assertEquals($data['result']['organization']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['organization']['logo'] , null);
        $this->assertEquals(count($data['result']['page']) , 3);
        $this->assertEquals($data['result']['page']['id'] , null);
        $this->assertEquals($data['result']['page']['title'] , null);
        $this->assertEquals($data['result']['page']['logo'] , null);
        $this->assertEquals(count($data['result']['user']) , 5);
        $this->assertEquals($data['result']['user']['id'] , 1);
        $this->assertEquals($data['result']['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['user']['avatar'] , null);
        $this->assertEquals($data['result']['user']['ambassador'] , null);
        $this->assertEquals(count($data['result']['users']) , 3);
        $this->assertEquals(count($data['result']['users'][0]) , 4);
        $this->assertEquals($data['result']['users'][0]['page_id'] , 1);
        $this->assertEquals($data['result']['users'][0]['user_id'] , 1);
        $this->assertEquals($data['result']['users'][0]['role'] , "admin");
        $this->assertEquals($data['result']['users'][0]['state'] , "member");
        $this->assertEquals(count($data['result']['users'][1]) , 4);
        $this->assertEquals($data['result']['users'][1]['page_id'] , 1);
        $this->assertEquals($data['result']['users'][1]['user_id'] , 2);
        $this->assertEquals($data['result']['users'][1]['role'] , "admin");
        $this->assertEquals($data['result']['users'][1]['state'] , "member");
        $this->assertEquals(count($data['result']['users'][2]) , 4);
        $this->assertEquals($data['result']['users'][2]['page_id'] , 1);
        $this->assertEquals($data['result']['users'][2]['user_id'] , 3);
        $this->assertEquals($data['result']['users'][2]['role'] , "admin");
        $this->assertEquals($data['result']['users'][2]['state'] , "member");
        $this->assertEquals(count($data['result']['tags']) , 3);
        $this->assertEquals(count($data['result']['tags'][0]) , 2);
        $this->assertEquals($data['result']['tags'][0]['name'] , "toto");
        $this->assertEquals($data['result']['tags'][0]['weight'] , 1);
        $this->assertEquals(count($data['result']['tags'][1]) , 2);
        $this->assertEquals($data['result']['tags'][1]['name'] , "tata");
        $this->assertEquals($data['result']['tags'][1]['weight'] , 1);
        $this->assertEquals(count($data['result']['tags'][2]) , 2);
        $this->assertEquals($data['result']['tags'][2]['name'] , "tutu");
        $this->assertEquals($data['result']['tags'][2]['weight'] , 1);
        $this->assertEquals(count($data['result']['docs']) , 1);
        $this->assertEquals(count($data['result']['docs'][0]) , 11);
        $this->assertEquals($data['result']['docs'][0]['id'] , 5);
        $this->assertEquals($data['result']['docs'][0]['name'] , "name");
        $this->assertEquals($data['result']['docs'][0]['link'] , "link");
        $this->assertEquals($data['result']['docs'][0]['token'] , null);
        $this->assertEquals($data['result']['docs'][0]['type'] , "type");
        $this->assertEquals(!empty($data['result']['docs'][0]['created_date']) , true);
        $this->assertEquals($data['result']['docs'][0]['deleted_date'] , null);
        $this->assertEquals($data['result']['docs'][0]['updated_date'] , null);
        $this->assertEquals($data['result']['docs'][0]['folder_id'] , null);
        $this->assertEquals($data['result']['docs'][0]['owner_id'] , 1);
        $this->assertEquals($data['result']['docs'][0]['box_id'] , null);
        $this->assertEquals(count($data['result']['events']) , 2);
        $this->assertEquals($data['result']['events']['count'] , 0);
        $this->assertEquals(count($data['result']['events']['list']) , 0);
        $this->assertEquals($data['result']['role'] , "admin");
        $this->assertEquals($data['result']['state'] , "member");
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "super title");
        $this->assertEquals($data['result']['logo'] , "logo");
        $this->assertEquals($data['result']['background'] , "background");
        $this->assertEquals($data['result']['description'] , "description");
        $this->assertEquals($data['result']['confidentiality'] , 1);
        $this->assertEquals($data['result']['admission'] , "free");
        $this->assertEquals($data['result']['start_date'] , "2015-00-00T00:00:00Z");
        $this->assertEquals($data['result']['end_date'] , "2016-00-00T00:00:00Z");
        $this->assertEquals($data['result']['location'] , "location");
        $this->assertEquals($data['result']['type'] , "group");
        $this->assertEquals($data['result']['user_id'] , 1);
        $this->assertEquals($data['result']['organization_id'] , 1);
        $this->assertEquals($data['result']['page_id'] , null);
        $this->assertEquals($data['result']['owner_id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);

    }

    /**
     * @depends testPageAdd
     */
    public function testPageUpdate($page_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('page.update', ['id' => $page_id,
            'title' => 'super title upt',
            'logo' => 'logo upt',
            'background' => 'background upt',
            'description' => 'description upt',
            'confidentiality' => 2,
            'type' => 'event',
            'admission' => 'free',
            'start_date' => '2018-00-00 00:00:00',
            'end_date' => '2019-00-00 00:00:00',
            'location' => 'location upt',
            'organization_id' => 1,
            'users' => [
                ['user_id' => 1,'role' => 'admin', 'state' => 'admin'],
                ['user_id' => 2,'role' => 'admin', 'state' => 'member'],
            ],
            'tags' => [
                'toto', 'tata', 'tutu', 'toutou'
            ],
            'docs' => [
                ['name' => 'name', 'link' => 'link', 'type' => 'type']
            ]
        ]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testPageAdd
     */
    public function testPageGetList()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('page.getList', ['user_id' => 1]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 24);
        $this->assertEquals(count($data['result']['list'][0]['owner']) , 4);
        $this->assertEquals($data['result']['list'][0]['owner']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['owner']['text'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['owner']['img'] , null);
        $this->assertEquals($data['result']['list'][0]['owner']['type'] , "organization");
        $this->assertEquals(count($data['result']['list'][0]['organization']) , 3);
        $this->assertEquals($data['result']['list'][0]['organization']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['organization']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['organization']['logo'] , null);
        $this->assertEquals(count($data['result']['list'][0]['page']) , 3);
        $this->assertEquals($data['result']['list'][0]['page']['id'] , null);
        $this->assertEquals($data['result']['list'][0]['page']['title'] , null);
        $this->assertEquals($data['result']['list'][0]['page']['logo'] , null);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['user']['ambassador'] , null);
        $this->assertEquals(count($data['result']['list'][0]['users']) , 2);
        $this->assertEquals(count($data['result']['list'][0]['users'][0]) , 4);
        $this->assertEquals($data['result']['list'][0]['users'][0]['page_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['user_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][0]['role'] , "admin");
        $this->assertEquals($data['result']['list'][0]['users'][0]['state'] , "");
        $this->assertEquals(count($data['result']['list'][0]['users'][1]) , 4);
        $this->assertEquals($data['result']['list'][0]['users'][1]['page_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['users'][1]['user_id'] , 2);
        $this->assertEquals($data['result']['list'][0]['users'][1]['role'] , "admin");
        $this->assertEquals($data['result']['list'][0]['users'][1]['state'] , "member");
        $this->assertEquals(count($data['result']['list'][0]['tags']) , 4);
        $this->assertEquals(count($data['result']['list'][0]['tags'][0]) , 2);
        $this->assertEquals($data['result']['list'][0]['tags'][0]['name'] , "toto");
        $this->assertEquals($data['result']['list'][0]['tags'][0]['weight'] , 2);
        $this->assertEquals(count($data['result']['list'][0]['tags'][1]) , 2);
        $this->assertEquals($data['result']['list'][0]['tags'][1]['name'] , "tata");
        $this->assertEquals($data['result']['list'][0]['tags'][1]['weight'] , 2);
        $this->assertEquals(count($data['result']['list'][0]['tags'][2]) , 2);
        $this->assertEquals($data['result']['list'][0]['tags'][2]['name'] , "tutu");
        $this->assertEquals($data['result']['list'][0]['tags'][2]['weight'] , 2);
        $this->assertEquals(count($data['result']['list'][0]['tags'][3]) , 2);
        $this->assertEquals($data['result']['list'][0]['tags'][3]['name'] , "toutou");
        $this->assertEquals($data['result']['list'][0]['tags'][3]['weight'] , 1);
        $this->assertEquals(count($data['result']['list'][0]['docs']) , 1);
        $this->assertEquals(count($data['result']['list'][0]['docs'][0]) , 11);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['id'] , 6);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['name'] , "name");
        $this->assertEquals($data['result']['list'][0]['docs'][0]['link'] , "link");
        $this->assertEquals($data['result']['list'][0]['docs'][0]['token'] , null);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['type'] , "type");
        $this->assertEquals(!empty($data['result']['list'][0]['docs'][0]['created_date']) , true);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['deleted_date'] , null);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['updated_date'] , null);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['folder_id'] , null);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['owner_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['docs'][0]['box_id'] , null);
        $this->assertEquals($data['result']['list'][0]['role'] , "admin");
        $this->assertEquals($data['result']['list'][0]['state'] , "");
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['title'] , "super title upt");
        $this->assertEquals($data['result']['list'][0]['logo'] , "logo upt");
        $this->assertEquals($data['result']['list'][0]['background'] , "background upt");
        $this->assertEquals($data['result']['list'][0]['description'] , "description upt");
        $this->assertEquals($data['result']['list'][0]['confidentiality'] , 2);
        $this->assertEquals($data['result']['list'][0]['admission'] , "free");
        $this->assertEquals($data['result']['list'][0]['start_date'] , "2018-00-00T00:00:00Z");
        $this->assertEquals($data['result']['list'][0]['end_date'] , "2019-00-00T00:00:00Z");
        $this->assertEquals($data['result']['list'][0]['location'] , "location upt");
        $this->assertEquals($data['result']['list'][0]['type'] , "event");
        $this->assertEquals($data['result']['list'][0]['user_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['organization_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['page_id'] , null);
        $this->assertEquals($data['result']['list'][0]['owner_id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    *
    * @depends testPageAdd
    **/
    public function testAddUserPage($page_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.add', [
          'page_id' => $page_id,
          'user_id' => 3,
          'state' => 'pending' ,
          'role' => 'user']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testMGetApplicationListByUser()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.m_getApplicationListByUser', ['users' => [3]]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][3]) , 1);
        $this->assertEquals($data['result'][3][0] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    *
    * @depends testPageAdd
    **/
    public function testAddUserPageInvited($page_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.update', [
          'page_id' => $page_id,
          'user_id' => 3,
          'state' => 'invited' ,
          'role' => 'user']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testMGetInvitationListByUser()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.m_getInvitationListByUser', ['users' => [3]]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][3]) , 1);
        $this->assertEquals($data['result'][3][0] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
    *
    * @depends testPageAdd
    **/
    public function testAddUserPageMember($page_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.update', [
          'page_id' => $page_id,
          'user_id' => 3,
          'state' => 'member' ,
          'role' => 'user']);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testMGetListByUser()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('pageuser.m_getListByUser', ['users' => [3]]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][3]) , 1);
        $this->assertEquals($data['result'][3][0] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testPageAdd
     */
    public function testPageDelete($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('page.delete', ['id' => $id]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
}
