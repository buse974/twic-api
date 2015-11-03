<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class FeedTest extends AbstractService
{

    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testAdd()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.add', array('content' => 'UN FEED','link' => 'link','video' => 'token','name_picture' => 'tokenp name','picture' => 'tokenp','name_document' => 'tokend name','document' => 'tokend'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testAddTwo()
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.add', array('content' => 'UN FEED2','link' => 'link2','video' => 'token2','name_picture' => 'namep2','name_document' => 'named2','picture' => 'tokenp2','document' => 'tokend2'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testgetList()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.getList', array());
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 13);
        $this->assertEquals(count($data['result'][0]['user']), 5);
        $this->assertEquals(count($data['result'][0]['user']['school']), 4);
        $this->assertEquals($data['result'][0]['user']['school']['id'], 1);
        $this->assertEquals($data['result'][0]['user']['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result'][0]['user']['school']['short_name'], "turpis");
        $this->assertEquals($data['result'][0]['user']['school']['logo'], null);
        $this->assertEquals($data['result'][0]['user']['id'], 2);
        $this->assertEquals($data['result'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result'][0]['user']['avatar'], null);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['content'], "UN FEED2");
        $this->assertEquals($data['result'][0]['user_id'], 2);
        $this->assertEquals($data['result'][0]['link'], "link2");
        $this->assertEquals($data['result'][0]['video'], "token2");
        $this->assertEquals($data['result'][0]['picture'], "tokenp2");
        $this->assertEquals($data['result'][0]['name_picture'], "namep2");
        $this->assertEquals($data['result'][0]['document'], "tokend2");
        $this->assertEquals($data['result'][0]['name_document'], "named2");
        $this->assertEquals($data['result'][0]['link_title'], null);
        $this->assertEquals($data['result'][0]['link_desc'], null);
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals(count($data['result'][1]), 13);
        $this->assertEquals(count($data['result'][1]['user']), 5);
        $this->assertEquals(count($data['result'][1]['user']['school']), 4);
        $this->assertEquals($data['result'][1]['user']['school']['id'], 1);
        $this->assertEquals($data['result'][1]['user']['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result'][1]['user']['school']['short_name'], "turpis");
        $this->assertEquals($data['result'][1]['user']['school']['logo'], null);
        $this->assertEquals($data['result'][1]['user']['id'], 1);
        $this->assertEquals($data['result'][1]['user']['firstname'], "Paul");
        $this->assertEquals($data['result'][1]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result'][1]['user']['avatar'], null);
        $this->assertEquals($data['result'][1]['id'], 1);
        $this->assertEquals($data['result'][1]['content'], "UN FEED");
        $this->assertEquals($data['result'][1]['user_id'], 1);
        $this->assertEquals($data['result'][1]['link'], "link");
        $this->assertEquals($data['result'][1]['video'], "token");
        $this->assertEquals($data['result'][1]['picture'], "tokenp");
        $this->assertEquals($data['result'][1]['name_picture'], "tokenp name");
        $this->assertEquals($data['result'][1]['document'], "tokend");
        $this->assertEquals($data['result'][1]['name_document'], "tokend name");
        $this->assertEquals($data['result'][1]['link_title'], null);
        $this->assertEquals($data['result'][1]['link_desc'], null);
        $this->assertEquals(! empty($data['result'][1]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddTwo
     */
    public function testUpdate($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.update', array('id' => $feed,'content' => 'UN FEED2 UPT','link' => 'link2UPT','video' => 'token2UPT','picture' => 'tokenp2UPT','document' => 'tokend2UPT','name_picture' => 'namep2UPT','name_document' => 'named2UPT'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testgetListTwo()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.getList', array());
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 13);
        $this->assertEquals(count($data['result'][0]['user']), 5);
        $this->assertEquals(count($data['result'][0]['user']['school']), 4);
        $this->assertEquals($data['result'][0]['user']['school']['id'], 1);
        $this->assertEquals($data['result'][0]['user']['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result'][0]['user']['school']['short_name'], "turpis");
        $this->assertEquals($data['result'][0]['user']['school']['logo'], null);
        $this->assertEquals($data['result'][0]['user']['id'], 2);
        $this->assertEquals($data['result'][0]['user']['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result'][0]['user']['lastname'], "Hoang");
        $this->assertEquals($data['result'][0]['user']['avatar'], null);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['content'], "UN FEED2 UPT");
        $this->assertEquals($data['result'][0]['user_id'], 2);
        $this->assertEquals($data['result'][0]['link'], "link2UPT");
        $this->assertEquals($data['result'][0]['video'], "token2UPT");
        $this->assertEquals($data['result'][0]['picture'], "tokenp2UPT");
        $this->assertEquals($data['result'][0]['name_picture'], "namep2UPT");
        $this->assertEquals($data['result'][0]['document'], "tokend2UPT");
        $this->assertEquals($data['result'][0]['name_document'], "named2UPT");
        $this->assertEquals($data['result'][0]['link_title'], null);
        $this->assertEquals($data['result'][0]['link_desc'], null);
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals(count($data['result'][1]), 13);
        $this->assertEquals(count($data['result'][1]['user']), 5);
        $this->assertEquals(count($data['result'][1]['user']['school']), 4);
        $this->assertEquals($data['result'][1]['user']['school']['id'], 1);
        $this->assertEquals($data['result'][1]['user']['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result'][1]['user']['school']['short_name'], "turpis");
        $this->assertEquals($data['result'][1]['user']['school']['logo'], null);
        $this->assertEquals($data['result'][1]['user']['id'], 1);
        $this->assertEquals($data['result'][1]['user']['firstname'], "Paul");
        $this->assertEquals($data['result'][1]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result'][1]['user']['avatar'], null);
        $this->assertEquals($data['result'][1]['id'], 1);
        $this->assertEquals($data['result'][1]['content'], "UN FEED");
        $this->assertEquals($data['result'][1]['user_id'], 1);
        $this->assertEquals($data['result'][1]['link'], "link");
        $this->assertEquals($data['result'][1]['video'], "token");
        $this->assertEquals($data['result'][1]['picture'], "tokenp");
        $this->assertEquals($data['result'][1]['name_picture'], "tokenp name");
        $this->assertEquals($data['result'][1]['document'], "tokend");
        $this->assertEquals($data['result'][1]['name_document'], "tokend name");
        $this->assertEquals($data['result'][1]['link_title'], null);
        $this->assertEquals($data['result'][1]['link_desc'], null);
        $this->assertEquals(! empty($data['result'][1]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testLinkPreview()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.linkPreview', array('url' => 'http://www.lapresse.ca/international/etats-unis/201508/23/01-4894779-manifestation-seins-nus-sur-times-square.php'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 3);
        $this->assertEquals(count($data['result']['meta']), 12);
        $this->assertEquals($data['result']['meta']['description'], "Des dizaines de femmes aux seins nus ont participé, dimanche, au défilé de la «fierté topless» à New York pour contrer les critiques de ceux qui se plaignent de la...");
        $this->assertEquals($data['result']['meta']['keywords'], "Actualités internationales, barack obama, george w bush");
        $this->assertEquals($data['result']['meta']['twitter:card'], "summary_large_image");
        $this->assertEquals($data['result']['meta']['twitter:site'], "@LP_LaPresse");
        $this->assertEquals($data['result']['meta']['twitter:title'], "Manifestation seins nus sur Times Square | Verena Dobnik | États-Unis");
        $this->assertEquals($data['result']['meta']['twitter:description'], "Des dizaines de femmes aux seins nus ont participé, dimanche, au défilé de la «fierté topless» à New York pour contrer les critiques de ceux qui se plaignent de la...");
        $this->assertEquals($data['result']['meta']['twitter:image:src'], "http://images.lpcdn.ca/924x615/201508/23/1048015-porte-parole-evenement-rachel-jessee.jpg");
        $this->assertEquals($data['result']['meta']['apple-itunes-app'], "app-id=451829003, app-argument=cyberpresse://article/4894779");
        $this->assertEquals($data['result']['meta']['fb:app_id'], 166995983353903);
        $this->assertEquals($data['result']['meta']['article:publisher'], "https://www.facebook.com/LaPresseFB");
        $this->assertEquals($data['result']['meta']['fb:admins'], 100001148387127);
        $this->assertEquals($data['result']['meta']['verify-v1'], "xehmvg8QtpAZLH2hipmw6o0J7SCD+4Z+S6+jk/Y9QuE=");
        $this->assertEquals(count($data['result']['open_graph']), 7);
        $this->assertEquals($data['result']['open_graph']['type'], "article");
        $this->assertEquals($data['result']['open_graph']['title'], "Manifestation seins nus sur Times Square | Verena Dobnik | États-Unis");
        $this->assertEquals($data['result']['open_graph']['description'], "Des dizaines de femmes aux seins nus ont participé, dimanche, au défilé de la «fierté topless» à New York pour contrer les critiques de ceux qui se plaignent de la...");
        $this->assertEquals($data['result']['open_graph']['site_name'], "La Presse");
        $this->assertEquals($data['result']['open_graph']['locale'], "fr_CA");
        $this->assertEquals($data['result']['open_graph']['url'], "http://www.lapresse.ca/international/etats-unis/201508/23/01-4894779-manifestation-seins-nus-sur-times-square.php");
        $this->assertEquals($data['result']['open_graph']['image'], "http://images.lpcdn.ca/924x615/201508/23/1048015-porte-parole-evenement-rachel-jessee.jpg");
        $this->assertEquals(count($data['result']['images']), 31);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testgetListByIds()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.getList', array('ids' => array(1)));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 13);
        $this->assertEquals(count($data['result'][0]['user']), 5);
        $this->assertEquals(count($data['result'][0]['user']['school']), 4);
        $this->assertEquals($data['result'][0]['user']['school']['id'], 1);
        $this->assertEquals($data['result'][0]['user']['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result'][0]['user']['school']['short_name'], "turpis");
        $this->assertEquals($data['result'][0]['user']['school']['logo'], null);
        $this->assertEquals($data['result'][0]['user']['id'], 1);
        $this->assertEquals($data['result'][0]['user']['firstname'], "Paul");
        $this->assertEquals($data['result'][0]['user']['lastname'], "Boussekey");
        $this->assertEquals($data['result'][0]['user']['avatar'], null);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['content'], "UN FEED");
        $this->assertEquals($data['result'][0]['user_id'], 1);
        $this->assertEquals($data['result'][0]['link'], "link");
        $this->assertEquals($data['result'][0]['video'], "token");
        $this->assertEquals($data['result'][0]['picture'], "tokenp");
        $this->assertEquals($data['result'][0]['name_picture'], "tokenp name");
        $this->assertEquals($data['result'][0]['document'], "tokend");
        $this->assertEquals($data['result'][0]['name_document'], "tokend name");
        $this->assertEquals($data['result'][0]['link_title'], null);
        $this->assertEquals($data['result'][0]['link_desc'], null);
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testgetListByIds
     */
    public function testCanAddLikeEvent()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('like.add', array('event' => 1));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanAddEventComment()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('eventcomment.add', array('event' => 1,'content' => 'un commentaire'));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanUpdateEventComment()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('eventcomment.update', array('comment' => 1,'content' => 'un commentaire UPT'));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanGetListEventComment()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('eventcomment.getList', array('event' => 1));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 1);
        $this->assertEquals(count($data['result'][0]) , 4);
        $this->assertEquals(count($data['result'][0]['user']) , 5);
        $this->assertEquals(count($data['result'][0]['user']['school']) , 3);
        $this->assertEquals($data['result'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result'][0]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result'][0]['user']['id'] , 1);
        $this->assertEquals($data['result'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['content'] , "un commentaire UPT");
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanDeleteEventComment()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('eventcomment.delete', array('comment' => 1));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testgetListByIds
     */
    public function testCanDeleteLikeEvent()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('like.delete', array('event' => 1));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    // DELETE
    
    /**
     * @depends testAdd
     */
    public function testdelete($feed)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.delete', array('id' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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
