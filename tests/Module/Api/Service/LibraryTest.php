<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class LibraryTest extends AbstractService
{
    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testCanAddDocument()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('library.add', array('name' => 'monfile','link' => 'http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 12);
        $this->assertEquals($data['result']['id'], 5);
        $this->assertEquals($data['result']['name'], "monfile");
        $this->assertEquals($data['result']['link'], "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf");
        $this->assertEquals($data['result']['token'], null);
        $this->assertEquals($data['result']['type'], null);
        $this->assertEquals(!empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals($data['result']['updated_date'], null);
        $this->assertEquals($data['result']['folder_id'], null);
        $this->assertEquals($data['result']['owner_id'], 1);
        $this->assertEquals($data['result']['box_id'], null);
        $this->assertEquals($data['result']['global'], 0);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result']['id'];
    }
    
    /**
     * @depends testCanAddDocument
     */
    public function testCanUpdateDocument($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('library.update', array('id' => $id, 'name' => 'monfileupt'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 12);
        $this->assertEquals($data['result']['id'], 5);
        $this->assertEquals($data['result']['name'], "monfileupt");
        $this->assertEquals($data['result']['link'], "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf");
        $this->assertEquals($data['result']['token'], null);
        $this->assertEquals($data['result']['type'], null);
        $this->assertEquals(!empty($data['result']['created_date']), true);
        $this->assertEquals($data['result']['deleted_date'], null);
        $this->assertEquals(!empty($data['result']['updated_date']), true);
        $this->assertEquals($data['result']['folder_id'], null);
        $this->assertEquals($data['result']['owner_id'], 1);
        $this->assertEquals($data['result']['box_id'], null);
        $this->assertEquals($data['result']['global'], 0);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddDocument
     */
    public function testCanGetListDocument($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('library.getList', []);

        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals($data['result']['count'], 2);
        $this->assertEquals(count($data['result']['documents']), 2);
        $this->assertEquals(count($data['result']['documents'][0]), 12);
        $this->assertEquals($data['result']['documents'][0]['id'], 2);
        $this->assertEquals($data['result']['documents'][0]['name'], "toto");
        $this->assertEquals($data['result']['documents'][0]['link'], "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf");
        $this->assertEquals($data['result']['documents'][0]['token'], null);
        $this->assertEquals($data['result']['documents'][0]['type'], null);
        $this->assertEquals($data['result']['documents'][0]['created_date'], null);
        $this->assertEquals($data['result']['documents'][0]['deleted_date'], null);
        $this->assertEquals($data['result']['documents'][0]['updated_date'], null);
        $this->assertEquals($data['result']['documents'][0]['folder_id'], null);
        $this->assertEquals($data['result']['documents'][0]['owner_id'], 1);
        $this->assertEquals($data['result']['documents'][0]['box_id'], "66bbb5cb2ef149f7989f33d8513cc058");
        $this->assertEquals($data['result']['documents'][0]['global'], 0);
        $this->assertEquals(count($data['result']['documents'][1]), 12);
        $this->assertEquals($data['result']['documents'][1]['id'], 5);
        $this->assertEquals($data['result']['documents'][1]['name'], "monfileupt");
        $this->assertEquals($data['result']['documents'][1]['link'], "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf");
        $this->assertEquals($data['result']['documents'][1]['token'], null);
        $this->assertEquals($data['result']['documents'][1]['type'], null);
        $this->assertEquals(!empty($data['result']['documents'][1]['created_date']), true);
        $this->assertEquals($data['result']['documents'][1]['deleted_date'], null);
        $this->assertEquals(!empty($data['result']['documents'][1]['updated_date']), true);
        $this->assertEquals($data['result']['documents'][1]['folder_id'], null);
        $this->assertEquals($data['result']['documents'][1]['owner_id'], 1);
        $this->assertEquals($data['result']['documents'][1]['box_id'], null);
        $this->assertEquals($data['result']['documents'][1]['global'], 0);
        $this->assertEquals($data['result']['folder'], null);
        $this->assertEquals($data['result']['parent'], null);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddDocument

    public function testCanGetSession($id)
    {
        $this->setIdentity(1);

        $data = $this->jsonRpc('library.getSession', [
            'id' => $id
        ]);

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['error']) , 3);
        $this->assertEquals($data['error']['code'] , 202);
        $this->assertEquals($data['error']['message'] , "Retry-After: 2");
        $this->assertEquals($data['error']['data'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }*/

    /**
     * @depends testCanAddDocument
     */
    public function testCanGetSession2()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('library.getSession', [
            'id' => 2
        ]);
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals($data['result']['expiresAt'], null);
        $this->assertEquals(!empty($data['result']['id']), true);
        $this->assertEquals(count($data['result']['urls']), 3);
        $this->assertEquals(!empty($data['result']['urls']['view']), true);
        $this->assertEquals(!empty($data['result']['urls']['assets']), true);
        $this->assertEquals(!empty($data['result']['urls']['realtime']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddDocument
     */
    public function testCanDeleteDocument($id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('library.delete', array('id' => $id));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('library.delete', array('id' => 2));
         
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
    /**
     * @depends testCanAddDocument
     */
    public function testCanGetList2Document($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('library.getList', array());
        
        $this->assertEquals($data['id'], 1);
        $this->assertEquals(count($data['result']), 4);
        $this->assertEquals($data['result']['count'], 0);
        $this->assertEquals(count($data['result']['documents']), 0);
        $this->assertEquals($data['result']['folder'], null);
        $this->assertEquals($data['result']['parent'], null);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
}
