<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class PostTest extends AbstractService
{
    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testPostAdd()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.add', [
            'content' => 'content #toto @[\'U\',1]',
            'link' => 'link',
            'picture' => 'picture',
            'name_picture' => 'name_picture',
            'link_title' => 'link_title',
            'link_desc' => 'link_desc',
            //'t_page_id' => 1,
            //'t_organization_id' => 1,
            //'t_user_id' => 1,
            //'t_course_id' => 1,
            'docs' => [
                ['name' => 'name', 'link' => 'link', 'type' => 'type'],
                ['name' => 'name2', 'link' => 'link2', 'type' => 'type2'],
            ]
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 25);
        $this->assertEquals(count($data['result']['subscription']) , 6);
        $this->assertEquals(count($data['result']['subscription']['post']) , 4);
        $this->assertEquals($data['result']['subscription']['post']['id'] , null);
        $this->assertEquals($data['result']['subscription']['post']['content'] , null);
        $this->assertEquals($data['result']['subscription']['post']['organization_id'] , null);
        $this->assertEquals($data['result']['subscription']['post']['page_id'] , null);
        $this->assertEquals(!empty($data['result']['subscription']['last_date']) , true);
        $this->assertEquals($data['result']['subscription']['action'] , "create");
        $this->assertEquals($data['result']['subscription']['sub_post_id'] , null);
        $this->assertEquals($data['result']['subscription']['user_id'] , 1);
        $this->assertEquals(count($data['result']['subscription']['data']) , 3);
        $this->assertEquals($data['result']['subscription']['data']['id'] , 1);
        $this->assertEquals($data['result']['subscription']['data']['parent_id'] , null);
        $this->assertEquals($data['result']['subscription']['data']['origin_id'] , null);
        $this->assertEquals(count($data['result']['docs']) , 2);
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
        $this->assertEquals(count($data['result']['docs'][1]) , 11);
        $this->assertEquals($data['result']['docs'][1]['id'] , 6);
        $this->assertEquals($data['result']['docs'][1]['name'] , "name2");
        $this->assertEquals($data['result']['docs'][1]['link'] , "link2");
        $this->assertEquals($data['result']['docs'][1]['token'] , null);
        $this->assertEquals($data['result']['docs'][1]['type'] , "type2");
        $this->assertEquals(!empty($data['result']['docs'][1]['created_date']) , true);
        $this->assertEquals($data['result']['docs'][1]['deleted_date'] , null);
        $this->assertEquals($data['result']['docs'][1]['updated_date'] , null);
        $this->assertEquals($data['result']['docs'][1]['folder_id'] , null);
        $this->assertEquals($data['result']['docs'][1]['owner_id'] , 1);
        $this->assertEquals($data['result']['docs'][1]['box_id'] , null);
        $this->assertEquals($data['result']['nbr_comments'] , 0);
        $this->assertEquals($data['result']['nbr_likes'] , 0);
        $this->assertEquals($data['result']['is_liked'] , 0);
        $this->assertEquals(count($data['result']['user']) , 7);
        $this->assertEquals(count($data['result']['user']['school']) , 3);
        $this->assertEquals($data['result']['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['user']['id'] , 1);
        $this->assertEquals($data['result']['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['user']['nickname'] , null);
        $this->assertEquals($data['result']['user']['avatar'] , null);
        $this->assertEquals($data['result']['user']['ambassador'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['content'] , "content #toto @['U',1]");
        $this->assertEquals($data['result']['user_id'] , 1);
        $this->assertEquals($data['result']['organization_id'] , null);
        $this->assertEquals($data['result']['page_id'] , null);
        $this->assertEquals($data['result']['link'] , "link");
        $this->assertEquals($data['result']['picture'] , "picture");
        $this->assertEquals($data['result']['name_picture'] , "name_picture");
        $this->assertEquals($data['result']['link_title'] , "link_title");
        $this->assertEquals($data['result']['link_desc'] , "link_desc");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['updated_date'] , null);
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['t_page_id'] , null);
        $this->assertEquals($data['result']['t_organization_id'] , null);
        $this->assertEquals($data['result']['t_user_id'] , 1);
        $this->assertEquals($data['result']['t_course_id'] , null);
        $this->assertEquals($data['result']['type'] , "post");
        $this->assertEquals($data['result']['data'] , null);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['result']['id'];
    }

    /**
     * @depends testPostAdd
     */
    public function testPostUpdate($post_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.update', [
            'id' => $post_id,
            'content' => 'content  #toto @[\'U\',1]',
            'link' => 'linkUpt',
            'picture' => 'pictureUpt',
            'name_picture' => 'name_pictureUpt',
            'link_title' => 'link_titleUpt',
            'link_desc' => 'link_descUpt',
            'docs' => [
                ['name' => 'nameUpt', 'link' => 'linkUpt', 'type' => 'typeUpt'],
                ['name' => 'name2Upt', 'link' => 'link2Upt', 'type' => 'type2Upt'],
            ]
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 24);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['content'] , "content  #toto @['U',1]");
        $this->assertEquals($data['result']['user_id'] , 1);
        $this->assertEquals($data['result']['organization_id'] , null);
        $this->assertEquals($data['result']['page_id'] , null);
        $this->assertEquals($data['result']['link'] , "linkUpt");
        $this->assertEquals($data['result']['picture'] , "pictureUpt");
        $this->assertEquals($data['result']['name_picture'] , "name_pictureUpt");
        $this->assertEquals($data['result']['link_title'] , "link_titleUpt");
        $this->assertEquals($data['result']['link_desc'] , "link_descUpt");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals(!empty($data['result']['updated_date']) , true);
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['origin_id'] , null);
        $this->assertEquals($data['result']['t_page_id'] , null);
        $this->assertEquals($data['result']['t_organization_id'] , null);
        $this->assertEquals($data['result']['t_user_id'] , 1);
        $this->assertEquals($data['result']['t_course_id'] , null);
        $this->assertEquals($data['result']['lat'] , null);
        $this->assertEquals($data['result']['lng'] , null);
        $this->assertEquals($data['result']['uid'] , 0);
        $this->assertEquals($data['result']['type'] , "post");
        $this->assertEquals($data['result']['data'] , null);
        $this->assertEquals($data['jsonrpc'] , 2.0);

        return $data['id'];
    }

    /**
     * @depends testPostAdd
     */
    public function testPostMobileGet($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.m_get', [
          'id' => $id
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , false);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    /**
     * @depends testPostAdd
     */
    public function testPostGet($post_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.get', [
            'id' => $post_id,
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 25);
        $this->assertEquals(count($data['result']['subscription']) , 6);
        $this->assertEquals(count($data['result']['subscription']['post']) , 4);
        $this->assertEquals($data['result']['subscription']['post']['id'] , null);
        $this->assertEquals($data['result']['subscription']['post']['content'] , null);
        $this->assertEquals($data['result']['subscription']['post']['organization_id'] , null);
        $this->assertEquals($data['result']['subscription']['post']['page_id'] , null);
        $this->assertEquals(!empty($data['result']['subscription']['last_date']) , true);
        $this->assertEquals($data['result']['subscription']['action'] , "create");
        $this->assertEquals($data['result']['subscription']['sub_post_id'] , null);
        $this->assertEquals($data['result']['subscription']['user_id'] , 1);
        $this->assertEquals(count($data['result']['subscription']['data']) , 3);
        $this->assertEquals($data['result']['subscription']['data']['id'] , 1);
        $this->assertEquals($data['result']['subscription']['data']['parent_id'] , null);
        $this->assertEquals($data['result']['subscription']['data']['origin_id'] , null);
        $this->assertEquals(count($data['result']['docs']) , 2);
        $this->assertEquals(count($data['result']['docs'][0]) , 11);
        $this->assertEquals($data['result']['docs'][0]['id'] , 7);
        $this->assertEquals($data['result']['docs'][0]['name'] , "nameUpt");
        $this->assertEquals($data['result']['docs'][0]['link'] , "linkUpt");
        $this->assertEquals($data['result']['docs'][0]['token'] , null);
        $this->assertEquals($data['result']['docs'][0]['type'] , "typeUpt");
        $this->assertEquals(!empty($data['result']['docs'][0]['created_date']) , true);
        $this->assertEquals($data['result']['docs'][0]['deleted_date'] , null);
        $this->assertEquals($data['result']['docs'][0]['updated_date'] , null);
        $this->assertEquals($data['result']['docs'][0]['folder_id'] , null);
        $this->assertEquals($data['result']['docs'][0]['owner_id'] , 1);
        $this->assertEquals($data['result']['docs'][0]['box_id'] , null);
        $this->assertEquals(count($data['result']['docs'][1]) , 11);
        $this->assertEquals($data['result']['docs'][1]['id'] , 8);
        $this->assertEquals($data['result']['docs'][1]['name'] , "name2Upt");
        $this->assertEquals($data['result']['docs'][1]['link'] , "link2Upt");
        $this->assertEquals($data['result']['docs'][1]['token'] , null);
        $this->assertEquals($data['result']['docs'][1]['type'] , "type2Upt");
        $this->assertEquals(!empty($data['result']['docs'][1]['created_date']) , true);
        $this->assertEquals($data['result']['docs'][1]['deleted_date'] , null);
        $this->assertEquals($data['result']['docs'][1]['updated_date'] , null);
        $this->assertEquals($data['result']['docs'][1]['folder_id'] , null);
        $this->assertEquals($data['result']['docs'][1]['owner_id'] , 1);
        $this->assertEquals($data['result']['docs'][1]['box_id'] , null);
        $this->assertEquals($data['result']['nbr_comments'] , 0);
        $this->assertEquals($data['result']['nbr_likes'] , 0);
        $this->assertEquals($data['result']['is_liked'] , 0);
        $this->assertEquals(count($data['result']['user']) , 7);
        $this->assertEquals(count($data['result']['user']['school']) , 3);
        $this->assertEquals($data['result']['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['user']['id'] , 1);
        $this->assertEquals($data['result']['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['user']['nickname'] , null);
        $this->assertEquals($data['result']['user']['avatar'] , null);
        $this->assertEquals($data['result']['user']['ambassador'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['content'] , "content  #toto @['U',1]");
        $this->assertEquals($data['result']['user_id'] , 1);
        $this->assertEquals($data['result']['organization_id'] , null);
        $this->assertEquals($data['result']['page_id'] , null);
        $this->assertEquals($data['result']['link'] , "linkUpt");
        $this->assertEquals($data['result']['picture'] , "pictureUpt");
        $this->assertEquals($data['result']['name_picture'] , "name_pictureUpt");
        $this->assertEquals($data['result']['link_title'] , "link_titleUpt");
        $this->assertEquals($data['result']['link_desc'] , "link_descUpt");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals(!empty($data['result']['updated_date']) , true);
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['t_page_id'] , null);
        $this->assertEquals($data['result']['t_organization_id'] , null);
        $this->assertEquals($data['result']['t_user_id'] , 1);
        $this->assertEquals($data['result']['t_course_id'] , null);
        $this->assertEquals($data['result']['type'] , "post");
        $this->assertEquals($data['result']['data'] , null);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testPostAdd
     */
    public function testPostLike($post_id)
    {
        $this->setIdentity(2);
        $data = $this->jsonRpc('post.like', [
            'id' => $post_id,
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testPostGetList()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.getList', []);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 26);
        $this->assertEquals(count($data['result'][0]['subscription']) , 6);
        $this->assertEquals(count($data['result'][0]['subscription']['post']) , 4);
        $this->assertEquals($data['result'][0]['subscription']['post']['id'] , null);
        $this->assertEquals($data['result'][0]['subscription']['post']['content'] , null);
        $this->assertEquals($data['result'][0]['subscription']['post']['organization_id'] , null);
        $this->assertEquals($data['result'][0]['subscription']['post']['page_id'] , null);
        $this->assertEquals(!empty($data['result'][0]['subscription']['last_date']) , true);
        $this->assertEquals($data['result'][0]['subscription']['action'] , "like");
        $this->assertEquals($data['result'][0]['subscription']['sub_post_id'] , null);
        $this->assertEquals($data['result'][0]['subscription']['user_id'] , 2);
        $this->assertEquals(count($data['result'][0]['subscription']['data']) , 3);
        $this->assertEquals($data['result'][0]['subscription']['data']['id'] , 1);
        $this->assertEquals(count($data['result'][0]['subscription']['data']['parent_id']) , 0);
        $this->assertEquals(count($data['result'][0]['subscription']['data']['origin_id']) , 0);
        $this->assertEquals(count($data['result'][0]['docs']) , 2);
        $this->assertEquals(count($data['result'][0]['docs'][0]) , 11);
        $this->assertEquals($data['result'][0]['docs'][0]['id'] , 7);
        $this->assertEquals($data['result'][0]['docs'][0]['name'] , "nameUpt");
        $this->assertEquals($data['result'][0]['docs'][0]['link'] , "linkUpt");
        $this->assertEquals($data['result'][0]['docs'][0]['token'] , null);
        $this->assertEquals($data['result'][0]['docs'][0]['type'] , "typeUpt");
        $this->assertEquals(!empty($data['result'][0]['docs'][0]['created_date']) , true);
        $this->assertEquals($data['result'][0]['docs'][0]['deleted_date'] , null);
        $this->assertEquals($data['result'][0]['docs'][0]['updated_date'] , null);
        $this->assertEquals($data['result'][0]['docs'][0]['folder_id'] , null);
        $this->assertEquals($data['result'][0]['docs'][0]['owner_id'] , 1);
        $this->assertEquals($data['result'][0]['docs'][0]['box_id'] , null);
        $this->assertEquals(count($data['result'][0]['docs'][1]) , 11);
        $this->assertEquals($data['result'][0]['docs'][1]['id'] , 8);
        $this->assertEquals($data['result'][0]['docs'][1]['name'] , "name2Upt");
        $this->assertEquals($data['result'][0]['docs'][1]['link'] , "link2Upt");
        $this->assertEquals($data['result'][0]['docs'][1]['token'] , null);
        $this->assertEquals($data['result'][0]['docs'][1]['type'] , "type2Upt");
        $this->assertEquals(!empty($data['result'][0]['docs'][1]['created_date']) , true);
        $this->assertEquals($data['result'][0]['docs'][1]['deleted_date'] , null);
        $this->assertEquals($data['result'][0]['docs'][1]['updated_date'] , null);
        $this->assertEquals($data['result'][0]['docs'][1]['folder_id'] , null);
        $this->assertEquals($data['result'][0]['docs'][1]['owner_id'] , 1);
        $this->assertEquals($data['result'][0]['docs'][1]['box_id'] , null);
        $this->assertEquals(!empty($data['result'][0]['last_date']) , true);
        $this->assertEquals($data['result'][0]['nbr_comments'] , 0);
        $this->assertEquals($data['result'][0]['nbr_likes'] , 1);
        $this->assertEquals($data['result'][0]['is_liked'] , 0);
        $this->assertEquals(count($data['result'][0]['user']) , 7);
        $this->assertEquals(count($data['result'][0]['user']['school']) , 3);
        $this->assertEquals($data['result'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['user']['id'] , 1);
        $this->assertEquals($data['result'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result'][0]['user']['nickname'] , null);
        $this->assertEquals($data['result'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result'][0]['user']['ambassador'] , null);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['content'] , "content  #toto @['U',1]");
        $this->assertEquals($data['result'][0]['user_id'] , 1);
        $this->assertEquals($data['result'][0]['organization_id'] , null);
        $this->assertEquals($data['result'][0]['page_id'] , null);
        $this->assertEquals($data['result'][0]['link'] , "linkUpt");
        $this->assertEquals($data['result'][0]['picture'] , "pictureUpt");
        $this->assertEquals($data['result'][0]['name_picture'] , "name_pictureUpt");
        $this->assertEquals($data['result'][0]['link_title'] , "link_titleUpt");
        $this->assertEquals($data['result'][0]['link_desc'] , "link_descUpt");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals(!empty($data['result'][0]['updated_date']) , true);
        $this->assertEquals($data['result'][0]['parent_id'] , null);
        $this->assertEquals($data['result'][0]['t_page_id'] , null);
        $this->assertEquals($data['result'][0]['t_organization_id'] , null);
        $this->assertEquals($data['result'][0]['t_user_id'] , 1);
        $this->assertEquals($data['result'][0]['t_course_id'] , null);
        $this->assertEquals($data['result'][0]['type'] , "post");
        $this->assertEquals($data['result'][0]['data'] , null);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testPostGetListId()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.getListId', []);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 2);
        $this->assertEquals(!empty($data['result'][0]['last_date']) , true);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
}
