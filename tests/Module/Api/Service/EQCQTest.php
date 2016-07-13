<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class EQCQTest extends AbstractService
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
        
        // ADD COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 2,'course' => $course_id));
        $this->reset();
        
        // ADD COURSE USER
        $this->setIdentity(1);
        $data = $this->jsonRpc('user.addCourse', array('user' => 3,'course' => $course_id));
        $this->reset();
         
        return [
            'school_id' => $school_id,
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
                'title' => 'title',
                'describe' => 'description',
                'duration' => 234,
                'type' => 'EQCQ',
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
                'data' => null,
                'parent' => null,
                'order' => null,
                'submission' => [
                    [ 'submission_user' => [1,2,3]]
                ],
                'has_all_student' => false,
                'is_grouped' => true,
                'is_complete' => true
            ]);
         
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['result'];
    }
    
    public function testGetSub()
    {
        // USER 1
        $this->setIdentity(1);
        $data = $this->jsonRpc('submission.getByItem',
            ['item_id' => 1]);
        $sub = $data['result']['id'];
        $this->reset();
        $this->setIdentity(1);
        $data = $this->jsonRpc('submissionuser.start',
            ['submission' => $sub]);
        $this->reset();
        // USER 2
        $this->setIdentity(2);
        $data = $this->jsonRpc('submissionuser.start',
            ['submission' => $sub]);
        $this->reset();
        
        $this->setIdentity(1);
        $data = $this->jsonRpc('questionnaire.getByItem',['item' => 1]);
        $fd = $data;
        foreach ($fd['result']['questions'] as $d) {
            $this->setIdentity(1);
            $data = $this->jsonRpc('questionnaire.answer',
                [ 'item' => 1, 'user' => 2, 'question' => $d['id'], 'scale' => rand(1,6)]);
            $this->reset();
            $this->setIdentity(1);
            $data = $this->jsonRpc('questionnaire.answer',
                [ 'item' => 1, 'user' => 1, 'question' => $d['id'], 'scale' => rand(1,6)]);
            $this->reset();
            $this->setIdentity(2);
            $data = $this->jsonRpc('questionnaire.answer',
                [ 'item' => 1, 'user' => 1, 'question' => $d['id'], 'scale' => rand(1,6)]);
            $this->reset();
            $this->setIdentity(2);
            $data = $this->jsonRpc('questionnaire.answer',
                [ 'item' => 1, 'user' => 2, 'question' => $d['id'], 'scale' => rand(1,6)]);
            $this->reset();
        }
        
    }
    
    public function testAddDimension()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('dimension.add', array('name' => 'Dimension','describe' => 'une super dimension '));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
        
        return $data['result'];
    }

    /**
     * @depends testAddDimension
     */
    public function testUpdateDimension($dimension)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('dimension.update', array('id' => $dimension,'name' => 'Dimension UPT','describe' => 'une super dimension UPT'));
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals($data['result'], 1);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    /**
     * @depends testAddDimension
     */
    public function testGetListDimension($dimension)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc('dimension.getList', array());

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 3);
        $this->assertEquals(count($data['result']['list']) , 3);
        $this->assertEquals(count($data['result']['list'][0]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['component']) , 3);
        $this->assertEquals(count($data['result']['list'][0]['component'][0]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['component'][0]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['component'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][0]['name'] , "Multicultural awareness");
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][0]['describe'] , "Individuals with multicultural awareness recognize that communication, negotiation and leadership styles may vary based on country of origin and other cultural factors. They are interested by this observation, and seek ways to learn more about it.");
        $this->assertEquals(count($data['result']['list'][0]['component'][1]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['component'][1]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['component'][1]['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['component'][1]['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][1]['describe'] , "Individuals with multicultural sensitivity are not only aware of and interested in potential differences in communication, negotiation and leadership styles, but also make an effort to adapt their own communication and leadership styles accordingly.");
        $this->assertEquals(count($data['result']['list'][0]['component'][2]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['component'][2]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['component'][2]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][2]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['component'][2]['id'] , 3);
        $this->assertEquals($data['result']['list'][0]['component'][2]['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result']['list'][0]['component'][2]['dimension_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][2]['describe'] , "Communicating with people from different cultures requires even more active listening and questioning styles to ensure that what you interpret is indeed aligned with the intent of the speaker. Accordingly the communication style needs to be interactive and accommodating.");
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['name'] , "CQ");
        $this->assertEquals(!empty($data['result']['list'][0]['describe']) , true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'] , null);
        $this->assertEquals(count($data['result']['list'][1]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component']) , 6);
        $this->assertEquals(count($data['result']['list'][1]['component'][0]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component'][0]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['component'][0]['dimension']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['component'][0]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result']['list'][1]['component'][0]['id'] , 4);
        $this->assertEquals($data['result']['list'][1]['component'][0]['name'] , "Communication & presentation");
        $this->assertEquals($data['result']['list'][1]['component'][0]['dimension_id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][1]['component'][0]['describe']) , true);
        $this->assertEquals(count($data['result']['list'][1]['component'][1]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component'][1]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['component'][1]['dimension']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['component'][1]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result']['list'][1]['component'][1]['id'] , 5);
        $this->assertEquals($data['result']['list'][1]['component'][1]['name'] , "Team player");
        $this->assertEquals($data['result']['list'][1]['component'][1]['dimension_id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][1]['component'][1]['describe']) , true);
        $this->assertEquals(count($data['result']['list'][1]['component'][2]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component'][2]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['component'][2]['dimension']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['component'][2]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result']['list'][1]['component'][2]['id'] , 6);
        $this->assertEquals($data['result']['list'][1]['component'][2]['name'] , "Leadership");
        $this->assertEquals($data['result']['list'][1]['component'][2]['dimension_id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][1]['component'][2]['describe']) , true);
        $this->assertEquals(count($data['result']['list'][1]['component'][3]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component'][3]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['component'][3]['dimension']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['component'][3]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result']['list'][1]['component'][3]['id'] , 7);
        $this->assertEquals($data['result']['list'][1]['component'][3]['name'] , "Critical thinking");
        $this->assertEquals($data['result']['list'][1]['component'][3]['dimension_id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][1]['component'][3]['describe']) , true);
        $this->assertEquals(count($data['result']['list'][1]['component'][4]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component'][4]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['component'][4]['dimension']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['component'][4]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result']['list'][1]['component'][4]['id'] , 8);
        $this->assertEquals($data['result']['list'][1]['component'][4]['name'] , "Empathy");
        $this->assertEquals($data['result']['list'][1]['component'][4]['dimension_id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][1]['component'][4]['describe']) , true);
        $this->assertEquals(count($data['result']['list'][1]['component'][5]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['component'][5]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['component'][5]['dimension']['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['component'][5]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result']['list'][1]['component'][5]['id'] , 9);
        $this->assertEquals($data['result']['list'][1]['component'][5]['name'] , "Enthusiasm");
        $this->assertEquals($data['result']['list'][1]['component'][5]['dimension_id'] , 2);
        $this->assertEquals(!empty($data['result']['list'][1]['component'][5]['describe']) , true);
        $this->assertEquals($data['result']['list'][1]['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['name'] , "EQ");
        $this->assertEquals(!empty($data['result']['list'][1]['describe']) , true);
        $this->assertEquals($data['result']['list'][1]['deleted_date'] , null);
        $this->assertEquals(count($data['result']['list'][2]) , 5);
        $this->assertEquals(count($data['result']['list'][2]['component']) , 0);
        $this->assertEquals($data['result']['list'][2]['id'] , 3);
        $this->assertEquals($data['result']['list'][2]['name'] , null);
        $this->assertEquals($data['result']['list'][2]['describe'] , "une super dimension UPT");
        $this->assertEquals($data['result']['list'][2]['deleted_date'] , null);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testAddQuestion()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc(
            'question.add', array(
                'text' => 'super texte de fou',
                'component' => 1
        ));
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 60);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
        
    }
    
    /**
     * @depends testAddQuestion
     */
    public function testUpdateQuestion($question)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'question.update', array(
                'id' => $question,
                'text' => 'super texte de fou deux',
                'component' => 2
            ));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddQuestion
     */
    public function testDeleteQuestion($question)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'question.delete', array(
                'id' => $question,
            ));
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddQuestion
     */
    public function testGetListQuestion($question)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'question.getList', array()
            );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 59);
        $this->assertEquals(count($data['result'][0]) , 3);
        $this->assertEquals(count($data['result'][0]['component']) , 2);
        $this->assertEquals($data['result'][0]['component']['id'] , 1);
        $this->assertEquals($data['result'][0]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['text'] , "When working with others in a multicultural group, \$subject like(s) to know about the historical, legal, political and economic conditions of their different countries.");
        $this->assertEquals(count($data['result'][1]) , 3);
        $this->assertEquals(count($data['result'][1]['component']) , 2);
        $this->assertEquals($data['result'][1]['component']['id'] , 1);
        $this->assertEquals($data['result'][1]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['text'] , "In group meetings \$subject assume(s) that cultural values will differ amongst participants.");
        $this->assertEquals(count($data['result'][2]) , 3);
        $this->assertEquals(count($data['result'][2]['component']) , 2);
        $this->assertEquals($data['result'][2]['component']['id'] , 1);
        $this->assertEquals($data['result'][2]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][2]['id'] , 3);
        $this->assertEquals($data['result'][2]['text'] , "\$subject believe(s) that gender roles may vary amongst people from different countries and/or cultures.");
        $this->assertEquals(count($data['result'][3]) , 3);
        $this->assertEquals(count($data['result'][3]['component']) , 2);
        $this->assertEquals($data['result'][3]['component']['id'] , 2);
        $this->assertEquals($data['result'][3]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][3]['id'] , 4);
        $this->assertEquals($data['result'][3]['text'] , "\$subject believe(s) that gender roles may vary amongst people from different countries and/or cultures.");
        $this->assertEquals(count($data['result'][4]) , 3);
        $this->assertEquals(count($data['result'][4]['component']) , 2);
        $this->assertEquals($data['result'][4]['component']['id'] , 2);
        $this->assertEquals($data['result'][4]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][4]['id'] , 5);
        $this->assertEquals($data['result'][4]['text'] , "\$subject realize(s) that because of different cultural backgrounds in our team, our perspectives may not be the same as those of team members from other regions.");
        $this->assertEquals(count($data['result'][5]) , 3);
        $this->assertEquals(count($data['result'][5]['component']) , 2);
        $this->assertEquals($data['result'][5]['component']['id'] , 2);
        $this->assertEquals($data['result'][5]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][5]['id'] , 6);
        $this->assertEquals($data['result'][5]['text'] , "\$subject believe(s)  that different cultural norms and values can affect business decisions in group work.");
        $this->assertEquals(count($data['result'][6]) , 3);
        $this->assertEquals(count($data['result'][6]['component']) , 2);
        $this->assertEquals($data['result'][6]['component']['id'] , 3);
        $this->assertEquals($data['result'][6]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][6]['id'] , 7);
        $this->assertEquals($data['result'][6]['text'] , "\$subject adapt(s) my/his/her communication style to the cultural sensitivities in the group.");
        $this->assertEquals(count($data['result'][7]) , 3);
        $this->assertEquals(count($data['result'][7]['component']) , 2);
        $this->assertEquals($data['result'][7]['component']['id'] , 3);
        $this->assertEquals($data['result'][7]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][7]['id'] , 8);
        $this->assertEquals($data['result'][7]['text'] , "When interacting with team members from different countries, \$subject ask(s) questions to avoid misunderstanding.");
        $this->assertEquals(count($data['result'][8]) , 3);
        $this->assertEquals(count($data['result'][8]['component']) , 2);
        $this->assertEquals($data['result'][8]['component']['id'] , 3);
        $this->assertEquals($data['result'][8]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][8]['id'] , 9);
        $this->assertEquals($data['result'][8]['text'] , "In group meetings with people from different countries, \$subject never lose(s) patience and stop listening.");
        $this->assertEquals(count($data['result'][9]) , 3);
        $this->assertEquals(count($data['result'][9]['component']) , 2);
        $this->assertEquals($data['result'][9]['component']['id'] , 4);
        $this->assertEquals($data['result'][9]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][9]['id'] , 10);
        $this->assertEquals($data['result'][9]['text'] , "\$subject am/is a good listener and pay(s) attention to what is being said by others.");
        $this->assertEquals(count($data['result'][10]) , 3);
        $this->assertEquals(count($data['result'][10]['component']) , 2);
        $this->assertEquals($data['result'][10]['component']['id'] , 4);
        $this->assertEquals($data['result'][10]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][10]['id'] , 11);
        $this->assertEquals($data['result'][10]['text'] , "\$subject am/is able to easily persuade other team members and win them over to my/his/her perspective.");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddQuestion
     */
    public function testGetListQuestionDeux($question)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'question.getList', array(
                'filter' => ['n'=>5,'p'=>2], 
                'dimension' => 'CQ', 
                'search' => '$subject'
            )
        );

        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 9);
        $this->assertEquals(count($data['result']['list']) , 4);
        $this->assertEquals(count($data['result']['list'][0]) , 3);
        $this->assertEquals(count($data['result']['list'][0]['component']) , 2);
        $this->assertEquals($data['result']['list'][0]['component']['id'] , 2);
        $this->assertEquals($data['result']['list'][0]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result']['list'][0]['id'] , 6);
        $this->assertEquals($data['result']['list'][0]['text'] , "\$subject believe(s)  that different cultural norms and values can affect business decisions in group work.");
        $this->assertEquals(count($data['result']['list'][1]) , 3);
        $this->assertEquals(count($data['result']['list'][1]['component']) , 2);
        $this->assertEquals($data['result']['list'][1]['component']['id'] , 3);
        $this->assertEquals($data['result']['list'][1]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result']['list'][1]['id'] , 7);
        $this->assertEquals($data['result']['list'][1]['text'] , "\$subject adapt(s) my/his/her communication style to the cultural sensitivities in the group.");
        $this->assertEquals(count($data['result']['list'][2]) , 3);
        $this->assertEquals(count($data['result']['list'][2]['component']) , 2);
        $this->assertEquals($data['result']['list'][2]['component']['id'] , 3);
        $this->assertEquals($data['result']['list'][2]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result']['list'][2]['id'] , 8);
        $this->assertEquals($data['result']['list'][2]['text'] , "When interacting with team members from different countries, \$subject ask(s) questions to avoid misunderstanding.");
        $this->assertEquals(count($data['result']['list'][3]) , 3);
        $this->assertEquals(count($data['result']['list'][3]['component']) , 2);
        $this->assertEquals($data['result']['list'][3]['component']['id'] , 3);
        $this->assertEquals($data['result']['list'][3]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result']['list'][3]['id'] , 9);
        $this->assertEquals($data['result']['list'][3]['text'] , "In group meetings with people from different countries, \$subject never lose(s) patience and stop listening.");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testAddComponent()
    {
        $this->setIdentity(1);

        $data = $this->jsonRpc(
            'component.add', array(
                'name' => 'supername',
                'dimension' => 1,
                'describe' => 'description'
            )
        );
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 10);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }
    
    /**
     * @depends testAddComponent
     */
    public function testUpdateComponent($component)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'component.update', array(
                'id' => $component,
                'name' => 'supername  updt',
                'dimension' => 1,
                'describe' => 'description updt'
            )
        );
    
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddComponent
     */
    public function testGetListComponent($component)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'component.getList', array('filter' => ['n' => 3], 'dimension' => 1, 'search' => 'multicultural')
        );
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 3);
        $this->assertEquals(count($data['result']['list']) , 3);
        $this->assertEquals(count($data['result']['list'][0]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['name'] , "Multicultural awareness");
        $this->assertEquals($data['result']['list'][0]['dimension_id'] , 1);
        $this->assertEquals($data['result']['list'][0]['describe'] , "Individuals with multicultural awareness recognize that communication, negotiation and leadership styles may vary based on country of origin and other cultural factors. They are interested by this observation, and seek ways to learn more about it.");
        $this->assertEquals(count($data['result']['list'][1]) , 5);
        $this->assertEquals(count($data['result']['list'][1]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][1]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][1]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][1]['id'] , 2);
        $this->assertEquals($data['result']['list'][1]['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result']['list'][1]['dimension_id'] , 1);
        $this->assertEquals($data['result']['list'][1]['describe'] , "Individuals with multicultural sensitivity are not only aware of and interested in potential differences in communication, negotiation and leadership styles, but also make an effort to adapt their own communication and leadership styles accordingly.");
        $this->assertEquals(count($data['result']['list'][2]) , 5);
        $this->assertEquals(count($data['result']['list'][2]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][2]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][2]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][2]['id'] , 3);
        $this->assertEquals($data['result']['list'][2]['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result']['list'][2]['dimension_id'] , 1);
        $this->assertEquals($data['result']['list'][2]['describe'] , "Communicating with people from different cultures requires even more active listening and questioning styles to ensure that what you interpret is indeed aligned with the intent of the speaker. Accordingly the communication style needs to be interactive and accommodating.");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    
    public function testScaleAdd()
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc(
            'scale.add', array(
                'name' => 'name',
                'value' => 55
            )
        );
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 7);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testScaleAddTwo()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'scale.add', array(
                'name' => 'name2',
                'value' => 552
            )
        );
    
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 8); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
        
        return $data['result'];
    }

    /**
     *
     * @depends testScaleAddTwo
     */
    public function testScaleUpdate($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc(
            'scale.update', array(
                'id' => $id,
                'name' => 'name2updt',
                'value' => 999
            )
        );
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals($data['result'] , 1); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testScaleGetList($filter = null)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc(
            'scale.getList', array(
            )
        );
        
        $this->assertEquals(count($data) , 3); 
        $this->assertEquals(count($data['result']) , 8); 
        $this->assertEquals(count($data['result'][0]) , 3); 
        $this->assertEquals($data['result'][0]['id'] , 1); 
        $this->assertEquals($data['result'][0]['name'] , "Strongly disagree"); 
        $this->assertEquals($data['result'][0]['value'] , 1); 
        $this->assertEquals(count($data['result'][1]) , 3); 
        $this->assertEquals($data['result'][1]['id'] , 2); 
        $this->assertEquals($data['result'][1]['name'] , "Disagree"); 
        $this->assertEquals($data['result'][1]['value'] , 2); 
        $this->assertEquals(count($data['result'][2]) , 3); 
        $this->assertEquals($data['result'][2]['id'] , 3); 
        $this->assertEquals($data['result'][2]['name'] , "Neither agree, nor disagree"); 
        $this->assertEquals($data['result'][2]['value'] , 3); 
        $this->assertEquals(count($data['result'][3]) , 3); 
        $this->assertEquals($data['result'][3]['id'] , 4); 
        $this->assertEquals($data['result'][3]['name'] , "Agree"); 
        $this->assertEquals($data['result'][3]['value'] , 4); 
        $this->assertEquals(count($data['result'][4]) , 3); 
        $this->assertEquals($data['result'][4]['id'] , 5); 
        $this->assertEquals($data['result'][4]['name'] , "Strongly agree"); 
        $this->assertEquals($data['result'][4]['value'] , 5); 
        $this->assertEquals(count($data['result'][5]) , 3); 
        $this->assertEquals($data['result'][5]['id'] , 6); 
        $this->assertEquals($data['result'][5]['name'] , "I don't Know"); 
        $this->assertEquals($data['result'][5]['value'] , 0); 
        $this->assertEquals(count($data['result'][6]) , 3); 
        $this->assertEquals($data['result'][6]['id'] , 7); 
        $this->assertEquals($data['result'][6]['name'] , "name"); 
        $this->assertEquals($data['result'][6]['value'] , 55); 
        $this->assertEquals(count($data['result'][7]) , 3); 
        $this->assertEquals($data['result'][7]['id'] , 8); 
        $this->assertEquals($data['result'][7]['name'] , "name2updt"); 
        $this->assertEquals($data['result'][7]['value'] , 999); 
        $this->assertEquals($data['id'] , 1); 
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * 
     * @depends testScaleAddTwo
     */
    public function testScaleDeltete($id)
    {
        $this->setIdentity(1);
        
        $data = $this->jsonRpc(
            'scale.delete', array(
                'id' => $id
            )
        );
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }

    public function testDimensionScaleAdd()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'dimensionscale.add', array(
                'dimension' => 1,
                'min' => 110,
                'max' => 120,
                'describe' => 'describe'
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testDimensionScaleAddTwo()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'dimensionscale.add', array(
                'dimension' => 2,
                'min' => 120,
                'max' => 130,
                'describe' => 'describe2'
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 2);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['result'];
    }
    
    /**
     *
     * @depends testDimensionScaleAddTwo
     */
    public function testDimensionScaleUpdate($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'dimensionscale.update', array(
                'id' => $id,
                'dimension' => 3,
                'min' => 140,
                'max' => 150,
                'describe' => 'describe2updt'
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testDimensionScaleGetList($filter = null)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('dimensionscale.getList', []);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals(count($data['result'][0]) , 6);
        $this->assertEquals(count($data['result'][0]['dimension']) , 2);
        $this->assertEquals($data['result'][0]['dimension']['id'] , 1);
        $this->assertEquals($data['result'][0]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['dimension_id'] , 1);
        $this->assertEquals($data['result'][0]['min'] , 110);
        $this->assertEquals($data['result'][0]['max'] , 120);
        $this->assertEquals($data['result'][0]['describe'] , "describe");
        $this->assertEquals(count($data['result'][1]) , 6);
        $this->assertEquals(count($data['result'][1]['dimension']) , 2);
        $this->assertEquals($data['result'][1]['dimension']['id'] , 3);
        $this->assertEquals($data['result'][1]['dimension']['name'] , null);
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['dimension_id'] , 3);
        $this->assertEquals($data['result'][1]['min'] , 140);
        $this->assertEquals($data['result'][1]['max'] , 150);
        $this->assertEquals($data['result'][1]['describe'] , "describe2updt");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
        
    /**
     *
     * @depends testDimensionScaleAddTwo
     */
    public function testDimensionScaleDeltete($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'dimensionscale.delete', array(
                'id' => $id
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
      
    ////////////////////////////////////////////////////////////::
    public function testComponentScaleAdd()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'componentscale.add', array(
                'component' => 1,
                'min' => 110,
                'max' => 120,
                'describe' => 'describe',
                'recommandation' => 'recommandation'
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 37);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testComponentScaleAddTwo()
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'componentscale.add', array(
                'component' => 2,
                'min' => 130,
                'max' => 140,
                'describe' => 'describe2',
                'recommandation' => 'recommandation2'
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 38);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    
        return $data['result'];
    }
    
    /**
     *
     * @depends testComponentScaleAddTwo
     */
    public function testComponentScaleUpdate($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'componentscale.update', array(
                'id' => $id,
                'component' => 3,
                'min' => 140,
                'max' => 150,
                'describe' => 'describe updt',
                'recommandation' => 'recommandation updt'
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    public function testComponentScaleGetList($filter = null)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc('componentscale.getList', []);
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 38);
        $this->assertEquals(count($data['result'][0]) , 7);
        $this->assertEquals(count($data['result'][0]['component']) , 2);
        $this->assertEquals($data['result'][0]['component']['id'] , 1);
        $this->assertEquals($data['result'][0]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['component_id'] , 1);
        $this->assertEquals($data['result'][0]['min'] , 0);
        $this->assertEquals($data['result'][0]['max'] , 25);
        $this->assertEquals(!empty($data['result'][0]['describe']) , true);
        $this->assertEquals($data['result'][0]['recommandation'] , "You are strongly advised to familiarize yourself with periodicals and newspapers which cover world events, and to also begin reading books about the history, politics and economic infrastructures of different regions.");
        $this->assertEquals(count($data['result'][1]) , 7);
        $this->assertEquals(count($data['result'][1]['component']) , 2);
        $this->assertEquals($data['result'][1]['component']['id'] , 1);
        $this->assertEquals($data['result'][1]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['component_id'] , 1);
        $this->assertEquals($data['result'][1]['min'] , 25);
        $this->assertEquals($data['result'][1]['max'] , 50);
        $this->assertEquals($data['result'][1]['describe'] , "You have an average appreciation of differences in communication, negotiation and leadership styles in different regions.");
        $this->assertEquals(!empty($data['result'][1]['recommandation']) , true);
        $this->assertEquals(count($data['result'][2]) , 7);
        $this->assertEquals(count($data['result'][2]['component']) , 2);
        $this->assertEquals($data['result'][2]['component']['id'] , 1);
        $this->assertEquals($data['result'][2]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][2]['id'] , 3);
        $this->assertEquals($data['result'][2]['component_id'] , 1);
        $this->assertEquals($data['result'][2]['min'] , 50);
        $this->assertEquals($data['result'][2]['max'] , 75);
        $this->assertEquals($data['result'][2]['describe'] , "You have a good appreciation of the differences amongst countries.");
        $this->assertEquals($data['result'][2]['recommandation'] , "This can be further improved not only by reading relevant journals, but also through travel abroad and associating personally and professionally as often as possible with people from different cultures.");
        $this->assertEquals(count($data['result'][3]) , 7);
        $this->assertEquals(count($data['result'][3]['component']) , 2);
        $this->assertEquals($data['result'][3]['component']['id'] , 1);
        $this->assertEquals($data['result'][3]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][3]['id'] , 4);
        $this->assertEquals($data['result'][3]['component_id'] , 1);
        $this->assertEquals($data['result'][3]['min'] , 75);
        $this->assertEquals($data['result'][3]['max'] , 100);
        $this->assertEquals($data['result'][3]['describe'] , "You have a thorough to excellent awareness of the differences amongst countries, and you are able to use this to your advantage in your professional negotiations with customers, suppliers, distributors, colleagues, and superiors.");
        $this->assertEquals($data['result'][3]['recommandation'] , "You can further improve your skills by learning (more) languages, travelling frequently, and associating with individuals from different countries.");
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     *
     * @depends testComponentScaleAddTwo
     */
    public function testComponentScaleDeltete($id)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'componentscale.delete', array(
                'id' => $id
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
    /**
     * @depends testAddComponent
     */
    public function testDeleteComponent($component)
    {
        $this->setIdentity(1);
    
        $data = $this->jsonRpc(
            'component.delete', array(
                'id' => $component
            )
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals($data['result'] , 1);
        $this->assertEquals($data['id'] , 1);
        $this->assertEquals($data['jsonrpc'] , 2.0);
    }
    
}
