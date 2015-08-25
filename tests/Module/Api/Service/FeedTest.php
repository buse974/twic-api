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
        
        $data = $this->jsonRpc('feed.add', array(
            'content' => 'UN FEED',
            'link' => 'link',
            'video' => 'token',
            'name_picture' => 'tokenp name',
            'picture' => 'tokenp',
            'name_document' => 'tokend name',
            'document' => 'tokend'
        ));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    public function testAddTwo()
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.add', array(
            'content' => 'UN FEED2',
            'link' => 'link2',
            'video' => 'token2',
            'name_picture' => 'namep2',
            'name_document' => 'named2',
            'picture' => 'tokenp2',
            'document' => 'tokend2',
        ));
        
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

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 2);
        $this->assertEquals(count($data['result']['list'][0]) , 13);
        $this->assertEquals($data['result']['list'][0]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][0]['nb_like'] , 0);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['content'] , "UN FEED2");
        $this->assertEquals($data['result']['list'][0]['user_id'] , 2);
        $this->assertEquals($data['result']['list'][0]['link'] , "link2");
        $this->assertEquals($data['result']['list'][0]['video'] , "token2");
        $this->assertEquals($data['result']['list'][0]['picture'] , "tokenp2");
        $this->assertEquals($data['result']['list'][0]['name_picture'] , "namep2");
        $this->assertEquals($data['result']['list'][0]['document'] , "tokend2");
        $this->assertEquals($data['result']['list'][0]['name_document'] , "named2");
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
        $this->assertEquals(count($data['result']['list'][1]) , 13);
        $this->assertEquals($data['result']['list'][1]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][1]['nb_like'] , 0);
        $this->assertEquals(count($data['result']['list'][1]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][1]['user']['school']) , 4);
        $this->assertEquals($data['result']['list'][1]['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][1]['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][1]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][1]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][1]['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['content'] , "UN FEED");
        $this->assertEquals($data['result']['list'][1]['user_id'] , 1);
        $this->assertEquals($data['result']['list'][1]['link'] , "link");
        $this->assertEquals($data['result']['list'][1]['video'] , "token");
        $this->assertEquals($data['result']['list'][1]['picture'] , "tokenp");
        $this->assertEquals($data['result']['list'][1]['name_picture'] , "tokenp name");
        $this->assertEquals($data['result']['list'][1]['document'] , "tokend");
        $this->assertEquals($data['result']['list'][1]['name_document'] , "tokend name");
        $this->assertEquals(!empty($data['result']['list'][1]['created_date']) , true);
        $this->assertEquals($data['result']['count'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddTwo
     */
    public function testAddComment($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.addComment', array('content' => 'UN FEED2','id' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddTwo
     */
    public function testUpdate($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.update', array(
            'id' => $feed, 
            'content' => 'UN FEED2 UPT',
            'link' => 'link2UPT',
            'video' => 'token2UPT',
            'picture' => 'tokenp2UPT',
            'document' => 'tokend2UPT',
            'name_picture' => 'namep2UPT',
            'name_document' => 'named2UPT',
        ));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    /**
     * @depends testAddTwo
     */
    public function testgetListComment($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('feed.GetListComment', array('id' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals(count($data['result'][0]), 4);
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
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['content'], "UN FEED2");
        $this->assertEquals(! empty($data['result'][0]['created_date']), true);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAdd
     */
    public function testdaddLike($feed)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('like.add', array('feed' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAdd
     */
    public function testdaddLikeTwo($feed)
    {
        $this->setIdentity(2);
        
        $data = $this->jsonRpc('like.add', array('feed' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAdd
     */
    public function testdaddLikeThree($feed)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('like.add', array('feed' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }
    
    public function testgetListTwo()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('feed.getList', array());

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 2);
        $this->assertEquals(count($data['result']['list'][0]) , 13);
        $this->assertEquals($data['result']['list'][0]['is_like'] , 0);
        $this->assertEquals($data['result']['list'][0]['nb_like'] , 0);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Xuan-Anh");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Hoang");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['content'] , "UN FEED2 UPT");
        $this->assertEquals($data['result']['list'][0]['user_id'] , 2);
        $this->assertEquals($data['result']['list'][0]['link'] , "link2UPT");
        $this->assertEquals($data['result']['list'][0]['video'] , "token2UPT");
        $this->assertEquals($data['result']['list'][0]['picture'] , "tokenp2UPT");
        $this->assertEquals($data['result']['list'][0]['name_picture'] , "namep2UPT");
        $this->assertEquals($data['result']['list'][0]['document'] , "tokend2UPT");
        $this->assertEquals($data['result']['list'][0]['name_document'] , "named2UPT");
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
        $this->assertEquals(count($data['result']['list'][1]) , 13);
        $this->assertEquals($data['result']['list'][1]['is_like'] , 1);
        $this->assertEquals($data['result']['list'][1]['nb_like'] , 3);
        $this->assertEquals(count($data['result']['list'][1]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][1]['user']['school']) , 4);
        $this->assertEquals($data['result']['list'][1]['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][1]['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][1]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][1]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][1]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][1]['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['content'] , "UN FEED");
        $this->assertEquals($data['result']['list'][1]['user_id'] , 1);
        $this->assertEquals($data['result']['list'][1]['link'] , "link");
        $this->assertEquals($data['result']['list'][1]['video'] , "token");
        $this->assertEquals($data['result']['list'][1]['picture'] , "tokenp");
        $this->assertEquals($data['result']['list'][1]['name_picture'] , "tokenp name");
        $this->assertEquals($data['result']['list'][1]['document'] , "tokend");
        $this->assertEquals($data['result']['list'][1]['name_document'] , "tokend name");
        $this->assertEquals(!empty($data['result']['list'][1]['created_date']) , true);
        $this->assertEquals($data['result']['count'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testLinkPreview() 
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('feed.linkPreview', array('url' => 'http://www.lapresse.ca/international/etats-unis/201508/23/01-4894779-manifestation-seins-nus-sur-times-square.php'));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['icons']) , 0);
        $this->assertEquals(count($data['result']['images']) , 31);
        $this->assertEquals($data['result']['images'][0] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359720/0/-1/ADTECH;loc=100;alias=lapresse_international_974x125_1;key=etats-unis;size=974x125");
        $this->assertEquals($data['result']['images'][1] , "http://static.lpcdn.ca/lpweb/img/visa/maPresseLogo.png");
        $this->assertEquals($data['result']['images'][2] , "http://static.lpcdn.ca/lpweb/img/visa/defaultAvatarTransparent.png");
        $this->assertEquals($data['result']['images'][3] , "http://static.lpcdn.ca/lpweb/img/visa/ou.png");
        $this->assertEquals($data['result']['images'][4] , "http://static.lpcdn.ca/lpweb/img/visa/fbInitiale.png");
        $this->assertEquals($data['result']['images'][5] , "http://static.lpcdn.ca/lpweb/img/visa/close.png");
        $this->assertEquals($data['result']['images'][6] , "http://static.lpcdn.ca/lpweb/img/visa/tuto1.png");
        $this->assertEquals($data['result']['images'][7] , "http://static.lpcdn.ca/lpweb/img/visa/tuto2.png");
        $this->assertEquals($data['result']['images'][8] , "http://static.lpcdn.ca/lpweb/img/visa/tuto3.png");
        $this->assertEquals($data['result']['images'][9] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359761/0/-1/ADTECH;loc=100;alias=lapresse_international_728x90_1;key=etats-unis;size=728x90");
        $this->assertEquals($data['result']['images'][10] , "http://static.lpcdn.ca/lpweb/img/meteo/icone_une/h.png");
        $this->assertEquals($data['result']['images'][11] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359706/0/-1/ADTECH;loc=100;alias=lapresse_international_954x30_1;key=etats-unis;size=954x30");
        $this->assertEquals($data['result']['images'][12] , "http://images.lpcdn.ca/641x427/201508/23/1048015-porte-parole-evenement-rachel-jessee.jpg");
        $this->assertEquals($data['result']['images'][13] , "http://static.lpcdn.ca/lpweb/img/actionBox/logoMaPresse.png");
        $this->assertEquals($data['result']['images'][14] , "http://static.lpcdn.ca/lpweb/img/actionBox/icoAdd.png");
        $this->assertEquals($data['result']['images'][15] , "http://static.lpcdn.ca/lpweb/img/actionBox/icoMail.png");
        $this->assertEquals($data['result']['images'][16] , "http://static.lpcdn.ca/lpweb/img/actionBox/icoFontsize.png");
        $this->assertEquals($data['result']['images'][17] , "http://static.lpcdn.ca/lpweb/img/actionBox/icoPrint.png");
        $this->assertEquals($data['result']['images'][18] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359863/0/-1/ADTECH;loc=100;alias=lapresse_international_160x90_1;key=etats-unis;size=160x90");
        $this->assertEquals($data['result']['images'][19] , "http://images.lpcdn.ca/130x87/201508/21/1047268.jpg");
        $this->assertEquals($data['result']['images'][20] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359795/0/-1/ADTECH;loc=100;alias=lapresse_international_634x90_1;key=etats-unis;size=634x90");
        $this->assertEquals($data['result']['images'][21] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359779/0/-1/ADTECH;loc=100;alias=lapresse_international_300x250_1;key=etats-unis;size=300x250");
        $this->assertEquals($data['result']['images'][22] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2360108/0/-1/ADTECH;loc=100;alias=lapresse_international_300x125_1;key=etats-unis;size=300x125");
        $this->assertEquals($data['result']['images'][23] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2359810/0/-1/ADTECH;loc=100;alias=lapresse_international_300x250_2;key=etats-unis;size=300x250");
        $this->assertEquals($data['result']['images'][24] , "http://adserver.adtechus.com/adserv/3.0/5214.1/2360018/0/-1/ADTECH;loc=100;alias=lapresse_international_300x225_1;key=etats-unis;size=300x225");
        $this->assertEquals($data['result']['images'][25] , "http://adserver.adtechus.com/adserv/3.0/5214.1/3431600/0/-1/ADTECH;loc=100;alias=lapresse_international_140x140_1;key=etats-unis;size=140x140");
        $this->assertEquals($data['result']['images'][26] , "http://images.lpcdn.ca/130x87/201508/24/1048355-etude-neurobiologiste-salah-el-mestikawy.jpg");
        $this->assertEquals($data['result']['images'][27] , "http://images.lpcdn.ca/130x87/201508/25/1048491-traitement-entraineur-chef-tom-higgins.png");
        $this->assertEquals($data['result']['images'][28] , "http://images.lpcdn.ca/130x87/201508/25/1048511.jpg");
        $this->assertEquals($data['result']['images'][29] , "http://images.lpcdn.ca/130x87/201508/25/1048453-panique-toujours-pire-conseiller-pour.cpt635760101149706088");
        $this->assertEquals($data['result']['images'][30] , "http://images.lpcdn.ca/130x87/201508/25/1048515-owen-wilson-incarne-pere-famille.jpg");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testgetListByIds()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('feed.getList', array('ids' => array(1)));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 13);
        $this->assertEquals($data['result']['list'][0]['is_like'] , 1);
        $this->assertEquals($data['result']['list'][0]['nb_like'] , 3);
        $this->assertEquals(count($data['result']['list'][0]['user']) , 5);
        $this->assertEquals(count($data['result']['list'][0]['user']['school']) , 4);
        $this->assertEquals($data['result']['list'][0]['user']['school']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['school']['name'] , "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['user']['school']['short_name'] , "turpis");
        $this->assertEquals($data['result']['list'][0]['user']['school']['logo'] , null);
        $this->assertEquals($data['result']['list'][0]['user']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['user']['firstname'] , "Paul");
        $this->assertEquals($data['result']['list'][0]['user']['lastname'] , "Boussekey");
        $this->assertEquals($data['result']['list'][0]['user']['avatar'] , null);
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['content'] , "UN FEED");
        $this->assertEquals($data['result']['list'][0]['user_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['link'] , "link");
        $this->assertEquals($data['result']['list'][0]['video'] , "token");
        $this->assertEquals($data['result']['list'][0]['picture'] , "tokenp");
        $this->assertEquals($data['result']['list'][0]['name_picture'] , "tokenp name");
        $this->assertEquals($data['result']['list'][0]['document'] , "tokend");
        $this->assertEquals($data['result']['list'][0]['name_document'] , "tokend name");
        $this->assertEquals(!empty($data['result']['list'][0]['created_date']) , true);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAdd
     */
    public function testdGetListLik($feed)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('like.getList', array('feed' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result']['list']), 3);
        $this->assertEquals(count($data['result']['list'][0]), 13);
        $this->assertEquals($data['result']['list'][0]['contact_state'], 0);
        $this->assertEquals(count($data['result']['list'][0]['school']), 4);
        $this->assertEquals($data['result']['list'][0]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][0]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][0]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][0]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][0]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['firstname'], "Christophe");
        $this->assertEquals($data['result']['list'][0]['lastname'], "Robert");
        $this->assertEquals($data['result']['list'][0]['email'], "crobert@thestudnet.com");
        $this->assertEquals($data['result']['list'][0]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][0]['birth_date'], null);
        $this->assertEquals($data['result']['list'][0]['position'], null);
        $this->assertEquals($data['result']['list'][0]['interest'], null);
        $this->assertEquals($data['result']['list'][0]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][0]['roles']), 1);
        $this->assertEquals($data['result']['list'][0]['roles'][0], "academic");
        $this->assertEquals(count($data['result']['list'][0]['program']), 0);
        $this->assertEquals(count($data['result']['list'][1]), 13);
        $this->assertEquals($data['result']['list'][1]['contact_state'], 0);
        $this->assertEquals(count($data['result']['list'][1]['school']), 4);
        $this->assertEquals($data['result']['list'][1]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][1]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][1]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][1]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['firstname'], "Xuan-Anh");
        $this->assertEquals($data['result']['list'][1]['lastname'], "Hoang");
        $this->assertEquals($data['result']['list'][1]['email'], "xhoang@thestudnet.com");
        $this->assertEquals($data['result']['list'][1]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][1]['birth_date'], null);
        $this->assertEquals($data['result']['list'][1]['position'], null);
        $this->assertEquals($data['result']['list'][1]['interest'], null);
        $this->assertEquals($data['result']['list'][1]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][1]['roles']), 1);
        $this->assertEquals($data['result']['list'][1]['roles'][0], "super_admin");
        $this->assertEquals(count($data['result']['list'][1]['program']), 0);
        $this->assertEquals(count($data['result']['list'][2]), 13);
        $this->assertEquals($data['result']['list'][2]['contact_state'], 0);
        $this->assertEquals(count($data['result']['list'][2]['school']), 4);
        $this->assertEquals($data['result']['list'][2]['school']['id'], 1);
        $this->assertEquals($data['result']['list'][2]['school']['name'], "Morbi Corporation");
        $this->assertEquals($data['result']['list'][2]['school']['short_name'], "turpis");
        $this->assertEquals($data['result']['list'][2]['school']['logo'], null);
        $this->assertEquals($data['result']['list'][2]['id'], 1);
        $this->assertEquals($data['result']['list'][2]['firstname'], "Paul");
        $this->assertEquals($data['result']['list'][2]['lastname'], "Boussekey");
        $this->assertEquals($data['result']['list'][2]['email'], "pboussekey@thestudnet.com");
        $this->assertEquals($data['result']['list'][2]['password'], "4ac91ac4cb1614b368e3dff3ac718f1d");
        $this->assertEquals($data['result']['list'][2]['birth_date'], null);
        $this->assertEquals($data['result']['list'][2]['position'], null);
        $this->assertEquals($data['result']['list'][2]['interest'], null);
        $this->assertEquals($data['result']['list'][2]['avatar'], null);
        $this->assertEquals(count($data['result']['list'][2]['roles']), 1);
        $this->assertEquals($data['result']['list'][2]['roles'][0], "admin");
        $this->assertEquals(count($data['result']['list'][2]['program']), 0);
        $this->assertEquals($data['result']['count'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAdd
     */
    public function testDeleteLike($feed)
    {
        $this->setIdentity(3);
        
        $data = $this->jsonRpc('like.delete', array('feed' => $feed));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    // DELETE
    
    /**
     * @depends testAddComment
     */
    public function testdeleteComment($feed_comment)
    {
        $this->setIdentity(2);
    
        $data = $this->jsonRpc('feed.deleteComment', array('id' => $feed_comment));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
    
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
