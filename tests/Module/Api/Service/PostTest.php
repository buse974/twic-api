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
        
        print_r($data);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['id'];
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
    
        print_r($data);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['id'];
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
        $this->assertEquals(count($data['result']) , 19);
        $this->assertEquals(count($data['result']['docs']) , 2);
        $this->assertEquals(count($data['result']['docs'][0]) , 11);
        $this->assertEquals($data['result']['docs'][0]['id'] , 5);
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
        $this->assertEquals($data['result']['docs'][1]['id'] , 6);
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
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['key'] , null);
        $this->assertEquals($data['result']['content'] , "contentUpt");
        $this->assertEquals($data['result']['user_id'] , 1);
        $this->assertEquals($data['result']['link'] , "linkUpt");
        $this->assertEquals($data['result']['video'] , null);
        $this->assertEquals($data['result']['picture'] , "pictureUpt");
        $this->assertEquals($data['result']['name_picture'] , "name_pictureUpt");
        $this->assertEquals($data['result']['link_title'] , "link_titleUpt");
        $this->assertEquals($data['result']['link_desc'] , "link_descUpt");
        $this->assertEquals(!empty($data['result']['created_date']) , true);
        $this->assertEquals($data['result']['deleted_date'] , null);
        $this->assertEquals(!empty($data['result']['updated_date']) , true);
        $this->assertEquals($data['result']['parent_id'] , null);
        $this->assertEquals($data['result']['t_page_id'] , 1);
        $this->assertEquals($data['result']['t_organization_id'] , 1);
        $this->assertEquals($data['result']['t_user_id'] , 1);
        $this->assertEquals($data['result']['t_course_id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testPostGetList()
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('post.getList', []);
        
        print_r($data);
    }
    
    
    
}