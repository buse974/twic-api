<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class PollTest extends AbstractService
{

    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');
        
        parent::setUpBeforeClass();
    }

    public function testAddCourse()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('program.add', array('name' => 'program name','school_id' => 1,'level' => 'emba','sis' => 'sis'));
    
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    
        $program_id = $data['result'];

        $this->reset();
        
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('course.add', array('title' => 'IMERIR','abstract' => 'un_token','description' => 'description','objectives' => 'objectives',
            'teaching' => 'teaching','attendance' => 'attendance','duration' => 18,'notes' => 'notes',
            'learning_outcomes' => 'learning_outcomes','video_link' => 'http://google.fr','video_token' => 'video_token',
            'material_document' => array(array('type' => 'link','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token',
                'date' => '2011-01-01')),'program_id' => $program_id));

        return $data['result']['id'];
    }
    
    /**
     * @depends testAddCourse
     */
    public function testCanQuestionAdd($course)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('bankquestion.add', [ 
            'course_id' => $course,
            'data' => [
                [
                    'question' => 'Ma question',
                    'name' => 'name',
                    'bank_question_type' => 3,
                    'bank_question_tag' => ['maquestion'],
                    'bank_question_media' => [
                        ['token' => 'token'],
                        ['link' => 'link']
                    ],
                    'point' => 99,
                    'bank_question_item' => [
                        [
                            'libelle' => 'oui',
                            'answer' => 'super pas cool',
                            'percent' => '100'
                        ],
                        ['libelle' => 'non']
                    ]
                ],
                [
                'question' => 'Ma question',
                'name' => 'name',
                'bank_question_type' => 3,
                'bank_question_tag' => ['maquestion'],
                'bank_question_media' => [
                    ['token' => 'token'],
                    ['link' => 'link']
                ],
                'point' => 99,
                'bank_question_item' => [
                    [
                        'libelle' => 'oui',
                        'answer' => 'super pas cool',
                        'percent' => '100'
                    ],
                    ['libelle' => 'non']
                ]
                ],
                [
                'question' => 'Ma question',
                'name' => 'name',
                'bank_question_type' => 3,
                'bank_question_tag' => ['maquestion'],
                'bank_question_media' => [
                    ['token' => 'token'],
                    ['link' => 'link']
                ],
                'point' => 99,
                'bank_question_item' => [
                    [
                        'libelle' => 'oui',
                        'answer' => 'super pas cool',
                        'percent' => '100'
                    ],
                    ['libelle' => 'non']
                ]
                ],
                [
                'question' => 'Ma question',
                'name' => 'name',
                'bank_question_type' => 3,
                'bank_question_tag' => ['maquestion'],
                'bank_question_media' => [
                    ['token' => 'token'],
                    ['link' => 'link']
                ],
                'point' => 99,
                'bank_question_item' => [
                    [
                        'libelle' => 'oui',
                        'answer' => 'super pas cool',
                        'percent' => '100'
                    ],
                    ['libelle' => 'non']
                ]
                ]
            ]
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals($data['result'][0] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanPollAdd()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.add', [
            'title' => 'un titre',
            'expiration' => '2107-10-10',
            'time_limit' => 10,
            'poll_item' => [
                [
                    'nb_point' => 99,
                    'bank_question_id' => 1
                ],
                [
                    'nb_point' => 99,
                    'nb' => 10,
                    'group_question' => [1,2,3],
                ]
            ]
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 5);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "un titre");
        $this->assertEquals(!empty($data['result']['expiration_date']) , true);
        $this->assertEquals($data['result']['time_limit'] , 10);
        $this->assertEquals($data['result']['item_id'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /*public function testCanPollAdd()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.add', [
            'title' => 'un titre',
            'poll_questions' => [ 
                [
                    'question' => 'Ma question',
                    'poll_question_type' => 3,
                    'poll_question_items' => [
                        ['libelle' => 'oui'],
                        [ 'libelle' => 'non']
                    ]
                ]
            ]
        ]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result']['poll_questions']) , 1);
        $this->assertEquals(count($data['result']['poll_questions'][0]) , 7);
        $this->assertEquals(count($data['result']['poll_questions'][0]['poll_question_items']) , 2);
        $this->assertEquals(count($data['result']['poll_questions'][0]['poll_question_items'][0]) , 4);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['libelle'] , "oui");
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['poll_question_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['parent_id'] , null);
        $this->assertEquals(count($data['result']['poll_questions'][0]['poll_question_items'][1]) , 4);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['id'] , 2);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['libelle'] , "non");
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['poll_question_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['parent_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['question'] , "Ma question");
        $this->assertEquals($data['result']['poll_questions'][0]['poll_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_type_id'] , 3);
        $this->assertEquals($data['result']['poll_questions'][0]['is_mandatory'] , 0);
        $this->assertEquals($data['result']['poll_questions'][0]['parent_id'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "un titre");
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testCanPollGet()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.get', ['id' => 1]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result']['poll_questions']) , 1);
        $this->assertEquals(count($data['result']['poll_questions'][0]) , 7);
        $this->assertEquals(count($data['result']['poll_questions'][0]['poll_question_items']) , 2);
        $this->assertEquals(count($data['result']['poll_questions'][0]['poll_question_items'][0]) , 4);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['libelle'] , "oui");
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['poll_question_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][0]['parent_id'] , null);
        $this->assertEquals(count($data['result']['poll_questions'][0]['poll_question_items'][1]) , 4);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['id'] , 2);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['libelle'] , "non");
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['poll_question_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_items'][1]['parent_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['question'] , "Ma question");
        $this->assertEquals($data['result']['poll_questions'][0]['poll_id'] , 1);
        $this->assertEquals($data['result']['poll_questions'][0]['poll_question_type_id'] , 3);
        $this->assertEquals($data['result']['poll_questions'][0]['is_mandatory'] , 0);
        $this->assertEquals($data['result']['poll_questions'][0]['parent_id'] , null);
        $this->assertEquals($data['result']['id'] , 1);
        $this->assertEquals($data['result']['title'] , "un titre");
        $this->assertEquals($data['result']['expiration_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testVote()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.vote', [
            'poll' => 1, 
            'poll_question' => 1, 
            'items' => [[
                'item' => 1,
                'answer' => 'response',
                'date' => '2016',
                'time' => '10:10:10'
        ]]]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testDelete()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.delete', [
            'id' => 1
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }*/
    
    
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
            ->will($this->returnValue(['id' => $id, 'token' => ''+$id+'token']));
        
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
