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

    public function testCreateInit()
    {
        // ADD SCHOOL
        $this->setIdentity(1);
        $data = $this->jsonRpc('school.add', array('name' => 'université de monaco','next_name' => 'buisness school','short_name' => 'IUM','logo' => 'token','describe' => 'une description','website' => 'www.ium.com','programme' => 'super programme','background' => 'background','phone' => '+33480547852','contact' => 'contact@ium.com','contact_id' => 1,'address' => array("street_no" => 12,"street_type" => "rue","street_name" => "du stade","city" => array("name" => "Monaco"),"country" => array("name" => "Monaco"))));
        $school_id = $data['result']['id'];
        $this->reset();
    
        // ADD SCHOOL USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.update', array('school_id' => $school_id));
        $this->reset();
    
        // ADD PROGRAM
        $this->setIdentity(1);
        $data = $this->jsonRpc('program.add', array('name' => 'program name','school_id' => $school_id,'level' => 'emba','sis' => 'sis'));
        $program_id = $data['result'];
        $this->reset();
         
        // ADD COURSE
        $this->setIdentity(1);
        $data = $this->jsonRpc('course.add', array('title' => 'IMERIR','abstract' => 'un_token','description' => 'description','objectives' => 'objectives','teaching' => 'teaching','attendance' => 'attendance','duration' => 18,'notes' => 'notes','learning_outcomes' => 'learning_outcomes','video_link' => 'http://google.fr','video_token' => 'video_token','material_document' => array(array('type' => 'link','title' => 'title','author' => 'author','link' => 'link','source' => 'source','token' => 'token','date' => '2011-01-01')),'program_id' => $program_id));
        $course_id = $data['result']['id'];
        $this->reset();
    
        // ADD COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 1,'course' => $course_id));
        $this->reset();
    
        // ADD SET
        $this->setIdentity(4);
        $data = $this->jsonRpc('set.add', ['course' => $course_id,'name' => 'nameset','uid' => 'suid','groups'=>[['name' =>'namegroup','uid'=>'guid','users'=>[1,3,4]]]]);
        $set_id = $data['result']['id'];
        $this->reset();
    
        return [
            'school_id' => $school_id,
            'set_id' => $set_id,
            'course_id' => $course_id
        ];
    }
     
    /**
     * @depends testCreateInit
     */
    public function testAddItem($data)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('item.add',
            [
                'course' => (int)$data['course_id'],
                'grading_policy_id' => 6,
                'title' => 'quiz',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'POLL',
                'set_id' => $data['set_id'],
                'ct' => [
                    'date'  => [
                        ['date' => '2016-01-01', 'after' => true],
                    ],
                ],
                'opt' => [
                    'grading' => [
                        'mode' => 'average',
                        'has_pg' => true,
                        'pg_nb' => 2,
                        'pg_auto' => true,
                        'pg_due_date' =>
                        '2016-10-10',
                        'pg_can_view' => true,
                        'user_can_view' => true,
                        'pg_stars' => true
                    ],
                ],
                'parent' => null,
                'order' => null,
            ]);
         
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['result'];
    }
    
    /**
     * @depends testCreateInit
     */
    public function testCanQuestionAdd($data)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('bankquestion.add', [ 
            'course_id' => $data['course_id'],
            'data' => [
                [
                    'question' => 'Ma question',
                    'name' => 'name',
                    'bank_question_type_id' => 3,
                    'bank_question_tag' => ['maquestion'],
                    'bank_question_media' => [
                        ['link' => 'http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf']
                    ],
                    'point' => 99,
                    'bank_question_item' => [
                        [
                            'libelle' => 'oui',
                            'answer' => 'super pas cool',
                            'percent' => '100'
                        ],
                        ['libelle' => 'non']
                    ],
                ],
                [
                'question' => 'Ma question',
                'name' => 'name',
                'bank_question_type_id' => 3,
                'bank_question_tag' => ['maquestion'],
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
                'bank_question_type_id' => 3,
                'bank_question_tag' => ['maquestion'],
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
                'bank_question_type_id' => 3,
                'bank_question_tag' => ['maquestion'],
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
    
    /**
     * @depends testCanQuestionAdd
     */
    public function testCanQuestionUpdate($bankquestion)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('bankquestion.update', [
            'id' => 1,
            'question' => 'Ma question upt 1',
            'name' => 'name',
            'bank_question_type_id' => 3,
            'bank_question_tag' => ['maquestion upt 1'],
            'bank_question_media' => [
                ['token' => 'token2'],
                ['link' => 'http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf']
            ],
            'point' => 88,
            'bank_question_item' => [
                [
                    'libelle' => 'non',
                    'answer' => 'super pas cool upt',
                    'percent' => '99',
                ],
                ['libelle' => 'peu etre']
            ]
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * 
     * @depends testAddItem
     */
    public function testCanPollAdd($item_id)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.add', [
            'title' => 'un titre',
            'expiration' => '2107-10-10',
            'time_limit' => 10,
            'item_id' => $item_id,
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
        $this->assertEquals(count($data['result']) , 6); 
        $this->assertEquals(count($data['result']['poll_item']) , 2); 
        $this->assertEquals(count($data['result']['poll_item'][0]) , 7); 
        $this->assertEquals($data['result']['poll_item'][0]['id'] , 1); 
        $this->assertEquals($data['result']['poll_item'][0]['poll_id'] , 1); 
        $this->assertEquals($data['result']['poll_item'][0]['bank_question_id'] , 1); 
        $this->assertEquals($data['result']['poll_item'][0]['group_question_id'] , null); 
        $this->assertEquals($data['result']['poll_item'][0]['order_id'] , null); 
        $this->assertEquals($data['result']['poll_item'][0]['is_mandatory'] , 0); 
        $this->assertEquals($data['result']['poll_item'][0]['nb_point'] , 99); 
        $this->assertEquals(count($data['result']['poll_item'][1]) , 8); 
        $this->assertEquals(count($data['result']['poll_item'][1]['group_question']) , 3); 
        $this->assertEquals(count($data['result']['poll_item'][1]['group_question']['bank_question']) , 3); 
        $this->assertEquals($data['result']['poll_item'][1]['group_question']['bank_question'][0] , 1); 
        $this->assertEquals($data['result']['poll_item'][1]['group_question']['bank_question'][1] , 2); 
        $this->assertEquals($data['result']['poll_item'][1]['group_question']['bank_question'][2] , 3); 
        $this->assertEquals($data['result']['poll_item'][1]['group_question']['id'] , 1); 
        $this->assertEquals($data['result']['poll_item'][1]['group_question']['nb'] , 10); 
        $this->assertEquals($data['result']['poll_item'][1]['id'] , 2); 
        $this->assertEquals($data['result']['poll_item'][1]['poll_id'] , 1); 
        $this->assertEquals($data['result']['poll_item'][1]['bank_question_id'] , null); 
        $this->assertEquals($data['result']['poll_item'][1]['group_question_id'] , 1); 
        $this->assertEquals($data['result']['poll_item'][1]['order_id'] , null); 
        $this->assertEquals($data['result']['poll_item'][1]['is_mandatory'] , 0); 
        $this->assertEquals($data['result']['poll_item'][1]['nb_point'] , 99); 
        $this->assertEquals($data['result']['id'] , 1); 
        $this->assertEquals($data['result']['title'] , "un titre"); 
        $this->assertEquals(!empty($data['result']['expiration_date']) , true); 
        $this->assertEquals($data['result']['time_limit'] , 10); 
        $this->assertEquals($data['result']['item_id'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0); 
        
        return $data['result']['id'];
    }
    
    /**
     * @depends testCanQuestionAdd
     */
    public function testCanQuestionUpdateAfter($bankquestion)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('bankquestion.update', [
            'id' => 1,
            'question' => 'Ma question upt 2 ',
            'name' => 'name',
            'bank_question_type_id' => 3,
            'bank_question_tag' => ['maquestion upt'],
            'bank_question_media' => [
                ['token' => 'token3'],
                ['link' => 'http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf']
            ],
            'point' => 89,
            'bank_question_item' => [
                [
                    'libelle' => 'nonupt2',
                    'answer' => 'super pas cool upt2',
                    'percent' => '9999',
                ],
                ['libelle' => 'peu etre2']
            ]
        ]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanPollAdd
     */
    public function testCanPollReplace($poll)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('pollitem.replace', [
            'poll_id' => $poll,
            'data' => [
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
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result'][0] , 3);
        $this->assertEquals($data['result'][1] , 4);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    
    /**
     * @depends testCreateInit
     */
    public function testCanBankQuestionGetList($course)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('bankquestion.getList', ['course_id' => $course['course_id']]);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 4);
        $this->assertEquals(count($data['result'][0]) , 10);
        $this->assertEquals(count($data['result'][0]['bank_question_tag']) , 1);
        $this->assertEquals($data['result'][0]['bank_question_tag'][0] , "maquestion");
        $this->assertEquals(count($data['result'][0]['bank_question_item']) , 2);
        $this->assertEquals(count($data['result'][0]['bank_question_item'][0]) , 5);
        $this->assertEquals(count($data['result'][0]['bank_question_item'][0]['bank_answer_item']) , 5);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['bank_answer_item']['bank_question_item_id'] , 3);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['bank_answer_item']['percent'] , 100);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['bank_answer_item']['answer'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['bank_answer_item']['date'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['bank_answer_item']['time'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['id'] , 3);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['libelle'] , "oui");
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['bank_question_id'] , 2);
        $this->assertEquals($data['result'][0]['bank_question_item'][0]['order_id'] , null);
        $this->assertEquals(count($data['result'][0]['bank_question_item'][1]) , 5);
        $this->assertEquals(count($data['result'][0]['bank_question_item'][1]['bank_answer_item']) , 5);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['bank_answer_item']['bank_question_item_id'] , 4);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['bank_answer_item']['percent'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['bank_answer_item']['answer'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['bank_answer_item']['date'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['bank_answer_item']['time'] , null);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['id'] , 4);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['libelle'] , "non");
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['bank_question_id'] , 2);
        $this->assertEquals($data['result'][0]['bank_question_item'][1]['order_id'] , null);
        $this->assertEquals($data['result'][0]['id'] , 2);
        $this->assertEquals($data['result'][0]['name'] , "name");
        $this->assertEquals($data['result'][0]['question'] , "Ma question");
        $this->assertEquals($data['result'][0]['bank_question_type_id'] , 3);
        $this->assertEquals($data['result'][0]['course_id'] , 1);
        $this->assertEquals($data['result'][0]['point'] , 99);
        $this->assertEquals($data['result'][0]['older'] , null);
        $this->assertEquals(!empty($data['result'][0]['created_date']) , true);
        $this->assertEquals(count($data['result'][1]) , 10);
        $this->assertEquals(count($data['result'][1]['bank_question_tag']) , 1);
        $this->assertEquals($data['result'][1]['bank_question_tag'][0] , "maquestion");
        $this->assertEquals(count($data['result'][1]['bank_question_item']) , 2);
        $this->assertEquals(count($data['result'][1]['bank_question_item'][0]) , 5);
        $this->assertEquals(count($data['result'][1]['bank_question_item'][0]['bank_answer_item']) , 5);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['bank_answer_item']['bank_question_item_id'] , 5);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['bank_answer_item']['percent'] , 100);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['bank_answer_item']['answer'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['bank_answer_item']['date'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['bank_answer_item']['time'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['id'] , 5);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['libelle'] , "oui");
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['bank_question_id'] , 3);
        $this->assertEquals($data['result'][1]['bank_question_item'][0]['order_id'] , null);
        $this->assertEquals(count($data['result'][1]['bank_question_item'][1]) , 5);
        $this->assertEquals(count($data['result'][1]['bank_question_item'][1]['bank_answer_item']) , 5);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['bank_answer_item']['bank_question_item_id'] , 6);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['bank_answer_item']['percent'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['bank_answer_item']['answer'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['bank_answer_item']['date'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['bank_answer_item']['time'] , null);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['id'] , 6);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['libelle'] , "non");
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['bank_question_id'] , 3);
        $this->assertEquals($data['result'][1]['bank_question_item'][1]['order_id'] , null);
        $this->assertEquals($data['result'][1]['id'] , 3);
        $this->assertEquals($data['result'][1]['name'] , "name");
        $this->assertEquals($data['result'][1]['question'] , "Ma question");
        $this->assertEquals($data['result'][1]['bank_question_type_id'] , 3);
        $this->assertEquals($data['result'][1]['course_id'] , 1);
        $this->assertEquals($data['result'][1]['point'] , 99);
        $this->assertEquals($data['result'][1]['older'] , null);
        $this->assertEquals(!empty($data['result'][1]['created_date']) , true);
        $this->assertEquals(count($data['result'][2]) , 10);
        $this->assertEquals(count($data['result'][2]['bank_question_tag']) , 1);
        $this->assertEquals($data['result'][2]['bank_question_tag'][0] , "maquestion");
        $this->assertEquals(count($data['result'][2]['bank_question_item']) , 2);
        $this->assertEquals(count($data['result'][2]['bank_question_item'][0]) , 5);
        $this->assertEquals(count($data['result'][2]['bank_question_item'][0]['bank_answer_item']) , 5);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['bank_answer_item']['bank_question_item_id'] , 7);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['bank_answer_item']['percent'] , 100);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['bank_answer_item']['answer'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['bank_answer_item']['date'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['bank_answer_item']['time'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['id'] , 7);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['libelle'] , "oui");
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['bank_question_id'] , 4);
        $this->assertEquals($data['result'][2]['bank_question_item'][0]['order_id'] , null);
        $this->assertEquals(count($data['result'][2]['bank_question_item'][1]) , 5);
        $this->assertEquals(count($data['result'][2]['bank_question_item'][1]['bank_answer_item']) , 5);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['bank_answer_item']['bank_question_item_id'] , 8);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['bank_answer_item']['percent'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['bank_answer_item']['answer'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['bank_answer_item']['date'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['bank_answer_item']['time'] , null);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['id'] , 8);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['libelle'] , "non");
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['bank_question_id'] , 4);
        $this->assertEquals($data['result'][2]['bank_question_item'][1]['order_id'] , null);
        $this->assertEquals($data['result'][2]['id'] , 4);
        $this->assertEquals($data['result'][2]['name'] , "name");
        $this->assertEquals($data['result'][2]['question'] , "Ma question");
        $this->assertEquals($data['result'][2]['bank_question_type_id'] , 3);
        $this->assertEquals($data['result'][2]['course_id'] , 1);
        $this->assertEquals($data['result'][2]['point'] , 99);
        $this->assertEquals($data['result'][2]['older'] , null);
        $this->assertEquals(!empty($data['result'][2]['created_date']) , true);
        $this->assertEquals(count($data['result'][3]) , 11);
        $this->assertEquals(count($data['result'][3]['bank_question_tag']) , 1);
        $this->assertEquals($data['result'][3]['bank_question_tag'][0] , "maquestion upt 1");
        $this->assertEquals(count($data['result'][3]['bank_question_item']) , 2);
        $this->assertEquals(count($data['result'][3]['bank_question_item'][0]) , 4);
        $this->assertEquals($data['result'][3]['bank_question_item'][0]['id'] , 11);
        $this->assertEquals($data['result'][3]['bank_question_item'][0]['libelle'] , "non");
        $this->assertEquals($data['result'][3]['bank_question_item'][0]['bank_question_id'] , 5);
        $this->assertEquals($data['result'][3]['bank_question_item'][0]['order_id'] , null);
        $this->assertEquals(count($data['result'][3]['bank_question_item'][1]) , 4);
        $this->assertEquals($data['result'][3]['bank_question_item'][1]['id'] , 12);
        $this->assertEquals($data['result'][3]['bank_question_item'][1]['libelle'] , "peu etre");
        $this->assertEquals($data['result'][3]['bank_question_item'][1]['bank_question_id'] , 5);
        $this->assertEquals($data['result'][3]['bank_question_item'][1]['order_id'] , null);
        $this->assertEquals(count($data['result'][3]['bank_question_media']) , 2);
        $this->assertEquals(count($data['result'][3]['bank_question_media'][0]) , 11);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['id'] , 4);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['name'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['link'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['token'] , "token2");
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['type'] , null);
        $this->assertEquals(!empty($data['result'][3]['bank_question_media'][0]['created_date']) , true);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['deleted_date'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['updated_date'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['folder_id'] , 1);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['owner_id'] , 4);
        $this->assertEquals($data['result'][3]['bank_question_media'][0]['box_id'] , null);
        $this->assertEquals(count($data['result'][3]['bank_question_media'][1]) , 11);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['id'] , 5);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['name'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['link'] , "http://www.droit-technologie.org/upload/dossier/doc/183-1.pdf");
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['token'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['type'] , null);
        $this->assertEquals(!empty($data['result'][3]['bank_question_media'][1]['created_date']) , true);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['deleted_date'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['updated_date'] , null);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['folder_id'] , 1);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['owner_id'] , 4);
        $this->assertEquals($data['result'][3]['bank_question_media'][1]['box_id'] , null);
        $this->assertEquals($data['result'][3]['id'] , 5);
        $this->assertEquals($data['result'][3]['name'] , "name");
        $this->assertEquals($data['result'][3]['question'] , "Ma question upt 2 ");
        $this->assertEquals($data['result'][3]['bank_question_type_id'] , 3);
        $this->assertEquals($data['result'][3]['course_id'] , 1);
        $this->assertEquals($data['result'][3]['point'] , 89);
        $this->assertEquals($data['result'][3]['older'] , null);
        $this->assertEquals(!empty($data['result'][3]['created_date']) , true);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testCanPollAdd
     */
    public function testCanPollUpadte($poll)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('poll.update', [
            'id' => $poll,
            'title' => 'un titre upd',
            'expiration' => '2107-10-11',
            'time_limit' => 11,
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
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddItem
     */
    public function testCanGetSubmission($item_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.get', [
            'item_id' => $item_id
        ]);
    
        return $data['result']['id'];
    }
    
    /**
     * @depends testCanGetSubmission
     */
    public function testCanGetSubmissionContent($submission_id)
    {
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.getContent', ['submission_id' => $submission_id]);
        
        print_r($data);
    }
    
    /**
     * @depends testCreateInit
     */
    public function testCanBankQuestionDelete($init)
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('bankquestion.delete', ['id' => $init['course_id']]);
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
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
