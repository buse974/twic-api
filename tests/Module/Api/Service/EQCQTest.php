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
        $this->assertEquals(count($data['result'][11]) , 3);
        $this->assertEquals(count($data['result'][11]['component']) , 2);
        $this->assertEquals($data['result'][11]['component']['id'] , 4);
        $this->assertEquals($data['result'][11]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][11]['id'] , 12);
        $this->assertEquals($data['result'][11]['text'] , "Whenever there is conflict in the group, \$subject seek(s) feedback from others regarding his/her/my ability to try to manage it.");
        $this->assertEquals(count($data['result'][12]) , 3);
        $this->assertEquals(count($data['result'][12]['component']) , 2);
        $this->assertEquals($data['result'][12]['component']['id'] , 4);
        $this->assertEquals($data['result'][12]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][12]['id'] , 13);
        $this->assertEquals($data['result'][12]['text'] , "When other team members become stressed or confrontational, \$subject make(s) an effort to keep the conversation calm.");
        $this->assertEquals(count($data['result'][13]) , 3);
        $this->assertEquals(count($data['result'][13]['component']) , 2);
        $this->assertEquals($data['result'][13]['component']['id'] , 4);
        $this->assertEquals($data['result'][13]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][13]['id'] , 14);
        $this->assertEquals($data['result'][13]['text'] , "\$subject understand(s) how my behavior can affect others’ work and emotions in group work.");
        $this->assertEquals(count($data['result'][14]) , 3);
        $this->assertEquals(count($data['result'][14]['component']) , 2);
        $this->assertEquals($data['result'][14]['component']['id'] , 4);
        $this->assertEquals($data['result'][14]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][14]['id'] , 15);
        $this->assertEquals($data['result'][14]['text'] , "Even during emotionally charged arguments, \$subject remains patient.");
        $this->assertEquals(count($data['result'][15]) , 3);
        $this->assertEquals(count($data['result'][15]['component']) , 2);
        $this->assertEquals($data['result'][15]['component']['id'] , 4);
        $this->assertEquals($data['result'][15]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][15]['id'] , 16);
        $this->assertEquals($data['result'][15]['text'] , "In stressful group work situations, \$subject never respond(s) too quickly or sharply.");
        $this->assertEquals(count($data['result'][16]) , 3);
        $this->assertEquals(count($data['result'][16]['component']) , 2);
        $this->assertEquals($data['result'][16]['component']['id'] , 4);
        $this->assertEquals($data['result'][16]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][16]['id'] , 17);
        $this->assertEquals($data['result'][16]['text'] , "\$subject am/is aware of my/his/her verbal outbursts during the team discussions.");
        $this->assertEquals(count($data['result'][17]) , 3);
        $this->assertEquals(count($data['result'][17]['component']) , 2);
        $this->assertEquals($data['result'][17]['component']['id'] , 4);
        $this->assertEquals($data['result'][17]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][17]['id'] , 18);
        $this->assertEquals($data['result'][17]['text'] , "\$subject adapt my/his/her communication style to how others in the group are feeling.");
        $this->assertEquals(count($data['result'][18]) , 3);
        $this->assertEquals(count($data['result'][18]['component']) , 2);
        $this->assertEquals($data['result'][18]['component']['id'] , 4);
        $this->assertEquals($data['result'][18]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][18]['id'] , 19);
        $this->assertEquals($data['result'][18]['text'] , "\$subject always try/tries to find the best way to communicate with other team members, even when there is resistance, confusion or conflict.");
        $this->assertEquals(count($data['result'][19]) , 3);
        $this->assertEquals(count($data['result'][19]['component']) , 2);
        $this->assertEquals($data['result'][19]['component']['id'] , 4);
        $this->assertEquals($data['result'][19]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][19]['id'] , 20);
        $this->assertEquals($data['result'][19]['text'] , "\$subject like(s) to express opinions and share ideas with others in the team.");
        $this->assertEquals(count($data['result'][20]) , 3);
        $this->assertEquals(count($data['result'][20]['component']) , 2);
        $this->assertEquals($data['result'][20]['component']['id'] , 4);
        $this->assertEquals($data['result'][20]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][20]['id'] , 21);
        $this->assertEquals($data['result'][20]['text'] , "\$subject enjoys being on stage and presenting the recommendations of our group work to others.");
        $this->assertEquals(count($data['result'][21]) , 3);
        $this->assertEquals(count($data['result'][21]['component']) , 2);
        $this->assertEquals($data['result'][21]['component']['id'] , 4);
        $this->assertEquals($data['result'][21]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][21]['id'] , 22);
        $this->assertEquals($data['result'][21]['text'] , "During group discussions, \$subject frequently use(s) stories and metaphors to get my/his/her ideas and recommendations across more persuasively.");
        $this->assertEquals(count($data['result'][22]) , 3);
        $this->assertEquals(count($data['result'][22]['component']) , 2);
        $this->assertEquals($data['result'][22]['component']['id'] , 5);
        $this->assertEquals($data['result'][22]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][22]['id'] , 23);
        $this->assertEquals($data['result'][22]['text'] , "\$subject incorporate other team members’ viewpoints into our group presentations.");
        $this->assertEquals(count($data['result'][23]) , 3);
        $this->assertEquals(count($data['result'][23]['component']) , 2);
        $this->assertEquals($data['result'][23]['component']['id'] , 5);
        $this->assertEquals($data['result'][23]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][23]['id'] , 24);
        $this->assertEquals($data['result'][23]['text'] , "\$subject never become(s) impatient when listening to team members.");
        $this->assertEquals(count($data['result'][24]) , 3);
        $this->assertEquals(count($data['result'][24]['component']) , 2);
        $this->assertEquals($data['result'][24]['component']['id'] , 5);
        $this->assertEquals($data['result'][24]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][24]['id'] , 25);
        $this->assertEquals($data['result'][24]['text'] , "During tense group discussions, \$subject never project(s) my/his/her stress and sense of urgency onto other people.");
        $this->assertEquals(count($data['result'][25]) , 3);
        $this->assertEquals(count($data['result'][25]['component']) , 2);
        $this->assertEquals($data['result'][25]['component']['id'] , 5);
        $this->assertEquals($data['result'][25]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][25]['id'] , 26);
        $this->assertEquals($data['result'][25]['text'] , "\$subject am/is an easy going and sociable person, and want(s) the team to work well together.");
        $this->assertEquals(count($data['result'][26]) , 3);
        $this->assertEquals(count($data['result'][26]['component']) , 2);
        $this->assertEquals($data['result'][26]['component']['id'] , 5);
        $this->assertEquals($data['result'][26]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][26]['id'] , 27);
        $this->assertEquals($data['result'][26]['text'] , "Whenever \$subject have/has a problem with the group assignment, \$subject will contact the other team members to find solutions.");
        $this->assertEquals(count($data['result'][27]) , 3);
        $this->assertEquals(count($data['result'][27]['component']) , 2);
        $this->assertEquals($data['result'][27]['component']['id'] , 5);
        $this->assertEquals($data['result'][27]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][27]['id'] , 28);
        $this->assertEquals($data['result'][27]['text'] , "Even when \$subject am/is angry or frustrated with the pace or quality of my group’s work, I/he/she remain(s) cool, calm and collected.");
        $this->assertEquals(count($data['result'][28]) , 3);
        $this->assertEquals(count($data['result'][28]['component']) , 2);
        $this->assertEquals($data['result'][28]['component']['id'] , 5);
        $this->assertEquals($data['result'][28]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][28]['id'] , 29);
        $this->assertEquals($data['result'][28]['text'] , "\$subject know(s) that I/he/she don’t/doesn’t work well under tight deadlines, so I/he/she get my/their part of the group work done well in advance.");
        $this->assertEquals(count($data['result'][29]) , 3);
        $this->assertEquals(count($data['result'][29]['component']) , 2);
        $this->assertEquals($data['result'][29]['component']['id'] , 5);
        $this->assertEquals($data['result'][29]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][29]['id'] , 30);
        $this->assertEquals($data['result'][29]['text'] , "When \$subject believe(s) that team members didn’t do a good job, I/he/she try/tries to understand the reasons for the failure and whether my/his/her actions or decisions had something to do with it.");
        $this->assertEquals(count($data['result'][30]) , 3);
        $this->assertEquals(count($data['result'][30]['component']) , 2);
        $this->assertEquals($data['result'][30]['component']['id'] , 5);
        $this->assertEquals($data['result'][30]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][30]['id'] , 31);
        $this->assertEquals($data['result'][30]['text'] , "During group work discussions, \$subject respectfully listen(s) to team members and then offer my/his/her opinions.");
        $this->assertEquals(count($data['result'][31]) , 3);
        $this->assertEquals(count($data['result'][31]['component']) , 2);
        $this->assertEquals($data['result'][31]['component']['id'] , 5);
        $this->assertEquals($data['result'][31]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][31]['id'] , 32);
        $this->assertEquals($data['result'][31]['text'] , "\$subject am/is interested in understanding team members’ points of view, and then offering some suggestions based on what they’re saying.");
        $this->assertEquals(count($data['result'][32]) , 3);
        $this->assertEquals(count($data['result'][32]['component']) , 2);
        $this->assertEquals($data['result'][32]['component']['id'] , 5);
        $this->assertEquals($data['result'][32]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][32]['id'] , 33);
        $this->assertEquals($data['result'][32]['text'] , "Even if \$subject thinks I/he/she have/has a better plan, I/he/she let others feel good about their ideas too");
        $this->assertEquals(count($data['result'][33]) , 3);
        $this->assertEquals(count($data['result'][33]['component']) , 2);
        $this->assertEquals($data['result'][33]['component']['id'] , 5);
        $this->assertEquals($data['result'][33]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][33]['id'] , 34);
        $this->assertEquals($data['result'][33]['text'] , "When our group project is criticized by the instructor, \$subject don’t/doesn’t blame others and instead try/tries to understand what we did wrong and how we can improve for the next round.");
        $this->assertEquals(count($data['result'][34]) , 3);
        $this->assertEquals(count($data['result'][34]['component']) , 2);
        $this->assertEquals($data['result'][34]['component']['id'] , 6);
        $this->assertEquals($data['result'][34]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][34]['id'] , 35);
        $this->assertEquals($data['result'][34]['text'] , "\$subject am/is able to manage my/his/her emotions in confrontational group work situations.");
        $this->assertEquals(count($data['result'][35]) , 3);
        $this->assertEquals(count($data['result'][35]['component']) , 2);
        $this->assertEquals($data['result'][35]['component']['id'] , 6);
        $this->assertEquals($data['result'][35]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][35]['id'] , 36);
        $this->assertEquals($data['result'][35]['text'] , "\$subject encourage(s) the participation of everyone on the team.");
        $this->assertEquals(count($data['result'][36]) , 3);
        $this->assertEquals(count($data['result'][36]['component']) , 2);
        $this->assertEquals($data['result'][36]['component']['id'] , 6);
        $this->assertEquals($data['result'][36]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][36]['id'] , 37);
        $this->assertEquals($data['result'][36]['text'] , "\$subject am/is able to build group pride, foster a positive emotional tone, and bring out the best in our team members.");
        $this->assertEquals(count($data['result'][37]) , 3);
        $this->assertEquals(count($data['result'][37]['component']) , 2);
        $this->assertEquals($data['result'][37]['component']['id'] , 6);
        $this->assertEquals($data['result'][37]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][37]['id'] , 38);
        $this->assertEquals($data['result'][37]['text'] , "\$subject always notice(s) when team members appear annoyed, frustrated or overwhelmed by my/his/her personality");
        $this->assertEquals(count($data['result'][38]) , 3);
        $this->assertEquals(count($data['result'][38]['component']) , 2);
        $this->assertEquals($data['result'][38]['component']['id'] , 6);
        $this->assertEquals($data['result'][38]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][38]['id'] , 39);
        $this->assertEquals($data['result'][38]['text'] , "Whenever team members don’t do what they were expected to do, \$subject deal(s) with it professionally and politely and explain(s) what had to be done differently.");
        $this->assertEquals(count($data['result'][39]) , 3);
        $this->assertEquals(count($data['result'][39]['component']) , 2);
        $this->assertEquals($data['result'][39]['component']['id'] , 6);
        $this->assertEquals($data['result'][39]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][39]['id'] , 40);
        $this->assertEquals($data['result'][39]['text'] , "\$subject can lead group discussions without team members feeling that they are being controlled.");
        $this->assertEquals(count($data['result'][40]) , 3);
        $this->assertEquals(count($data['result'][40]['component']) , 2);
        $this->assertEquals($data['result'][40]['component']['id'] , 6);
        $this->assertEquals($data['result'][40]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][40]['id'] , 41);
        $this->assertEquals($data['result'][40]['text'] , "\$subject always congratulate(s) team members for a job well-done.");
        $this->assertEquals(count($data['result'][41]) , 3);
        $this->assertEquals(count($data['result'][41]['component']) , 2);
        $this->assertEquals($data['result'][41]['component']['id'] , 6);
        $this->assertEquals($data['result'][41]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][41]['id'] , 42);
        $this->assertEquals($data['result'][41]['text'] , "Even if \$subject don’t/doesn’t like a team member, I/he/she will still work proactively with that person to get the job done.");
        $this->assertEquals(count($data['result'][42]) , 3);
        $this->assertEquals(count($data['result'][42]['component']) , 2);
        $this->assertEquals($data['result'][42]['component']['id'] , 6);
        $this->assertEquals($data['result'][42]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][42]['id'] , 43);
        $this->assertEquals($data['result'][42]['text'] , "\$subject take(s) other team members’ opinions into consideration when making decisions.");
        $this->assertEquals(count($data['result'][43]) , 3);
        $this->assertEquals(count($data['result'][43]['component']) , 2);
        $this->assertEquals($data['result'][43]['component']['id'] , 6);
        $this->assertEquals($data['result'][43]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][43]['id'] , 44);
        $this->assertEquals($data['result'][43]['text'] , "\$subject never blame(s) the team members when things go wrong.");
        $this->assertEquals(count($data['result'][44]) , 3);
        $this->assertEquals(count($data['result'][44]['component']) , 2);
        $this->assertEquals($data['result'][44]['component']['id'] , 6);
        $this->assertEquals($data['result'][44]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][44]['id'] , 45);
        $this->assertEquals($data['result'][44]['text'] , "\$subject use(s) “we” rather than “I” when describing our team’s accomplishments.");
        $this->assertEquals(count($data['result'][45]) , 3);
        $this->assertEquals(count($data['result'][45]['component']) , 2);
        $this->assertEquals($data['result'][45]['component']['id'] , 7);
        $this->assertEquals($data['result'][45]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][45]['id'] , 46);
        $this->assertEquals($data['result'][45]['text'] , "When discussing the problem to be solved within the group, \$subject am/is aware of how others perceive me/him/her.");
        $this->assertEquals(count($data['result'][46]) , 3);
        $this->assertEquals(count($data['result'][46]['component']) , 2);
        $this->assertEquals($data['result'][46]['component']['id'] , 7);
        $this->assertEquals($data['result'][46]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][46]['id'] , 47);
        $this->assertEquals($data['result'][46]['text'] , "In case work discussions on which managerial option to follow, \$subject am/is able to see the big picture and am/is not too narrowly focused.");
        $this->assertEquals(count($data['result'][47]) , 3);
        $this->assertEquals(count($data['result'][47]['component']) , 2);
        $this->assertEquals($data['result'][47]['component']['id'] , 7);
        $this->assertEquals($data['result'][47]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][47]['id'] , 48);
        $this->assertEquals($data['result'][47]['text'] , "During debate on which group decision to take, \$subject am/is able to consider issues from other team members’ perspectives.");
        $this->assertEquals(count($data['result'][48]) , 3);
        $this->assertEquals(count($data['result'][48]['component']) , 2);
        $this->assertEquals($data['result'][48]['component']['id'] , 7);
        $this->assertEquals($data['result'][48]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][48]['id'] , 49);
        $this->assertEquals($data['result'][48]['text'] , "In case study discussions, \$subject am/is easily able to identify the major issues confronting the organization and/or protagonists in the case.");
        $this->assertEquals(count($data['result'][49]) , 3);
        $this->assertEquals(count($data['result'][49]['component']) , 2);
        $this->assertEquals($data['result'][49]['component']['id'] , 7);
        $this->assertEquals($data['result'][49]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][49]['id'] , 50);
        $this->assertEquals($data['result'][49]['text'] , "When other team members suggest an alternative solution or recommendation to mine/his/her, \$subject listen(s) to them and weigh the pros and cons of the different options with all the team members.");
        $this->assertEquals(count($data['result'][50]) , 3);
        $this->assertEquals(count($data['result'][50]['component']) , 2);
        $this->assertEquals($data['result'][50]['component']['id'] , 8);
        $this->assertEquals($data['result'][50]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][50]['id'] , 51);
        $this->assertEquals($data['result'][50]['text'] , "\$subject am/is able to read the emotions of the team members.");
        $this->assertEquals(count($data['result'][51]) , 3);
        $this->assertEquals(count($data['result'][51]['component']) , 2);
        $this->assertEquals($data['result'][51]['component']['id'] , 8);
        $this->assertEquals($data['result'][51]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][51]['id'] , 52);
        $this->assertEquals($data['result'][51]['text'] , "\$subject easily build(s) relationships with almost everyone on the team.");
        $this->assertEquals(count($data['result'][52]) , 3);
        $this->assertEquals(count($data['result'][52]['component']) , 2);
        $this->assertEquals($data['result'][52]['component']['id'] , 8);
        $this->assertEquals($data['result'][52]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][52]['id'] , 53);
        $this->assertEquals($data['result'][52]['text'] , "In group-work, \$subject care about how others may feel when someone has been rude or condescending to them.");
        $this->assertEquals(count($data['result'][53]) , 3);
        $this->assertEquals(count($data['result'][53]['component']) , 2);
        $this->assertEquals($data['result'][53]['component']['id'] , 8);
        $this->assertEquals($data['result'][53]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][53]['id'] , 54);
        $this->assertEquals($data['result'][53]['text'] , "\$subject understand(s) what motivates our team members, even those from different backgrounds.");
        $this->assertEquals(count($data['result'][54]) , 3);
        $this->assertEquals(count($data['result'][54]['component']) , 2);
        $this->assertEquals($data['result'][54]['component']['id'] , 8);
        $this->assertEquals($data['result'][54]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][54]['id'] , 55);
        $this->assertEquals($data['result'][54]['text'] , "\$subject am/is able to read other team members’ emotions and use that insight to create a positive and participative forum for group work discussions.");
        $this->assertEquals(count($data['result'][55]) , 3);
        $this->assertEquals(count($data['result'][55]['component']) , 2);
        $this->assertEquals($data['result'][55]['component']['id'] , 9);
        $this->assertEquals($data['result'][55]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][55]['id'] , 56);
        $this->assertEquals($data['result'][55]['text'] , "\$subject always approach(es) a new group task, even if it means added work for me/him/her, enthusiastically.");
        $this->assertEquals(count($data['result'][56]) , 3);
        $this->assertEquals(count($data['result'][56]['component']) , 2);
        $this->assertEquals($data['result'][56]['component']['id'] , 9);
        $this->assertEquals($data['result'][56]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][56]['id'] , 57);
        $this->assertEquals($data['result'][56]['text'] , "\$subject really enjoy(s) it when the problem or issue that has to be solved as a team is a challenging and unexpected one.");
        $this->assertEquals(count($data['result'][57]) , 3);
        $this->assertEquals(count($data['result'][57]['component']) , 2);
        $this->assertEquals($data['result'][57]['component']['id'] , 9);
        $this->assertEquals($data['result'][57]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][57]['id'] , 58);
        $this->assertEquals($data['result'][57]['text'] , "Even when the instructor seemed harsh in the criticism of our group work, \$subject see(s) it as a way to improve for the next round.");
        $this->assertEquals(count($data['result'][58]) , 3);
        $this->assertEquals(count($data['result'][58]['component']) , 2);
        $this->assertEquals($data['result'][58]['component']['id'] , 9);
        $this->assertEquals($data['result'][58]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][58]['id'] , 59);
        $this->assertEquals($data['result'][58]['text'] , "\$subject never complain about the workload or the pace of work.");
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
        $this->assertEquals(count($data['result'][4]) , 7);
        $this->assertEquals(count($data['result'][4]['component']) , 2);
        $this->assertEquals($data['result'][4]['component']['id'] , 1);
        $this->assertEquals($data['result'][4]['component']['name'] , "Multicultural awareness");
        $this->assertEquals($data['result'][4]['id'] , 37);
        $this->assertEquals($data['result'][4]['component_id'] , 1);
        $this->assertEquals($data['result'][4]['min'] , 110);
        $this->assertEquals($data['result'][4]['max'] , 120);
        $this->assertEquals($data['result'][4]['describe'] , "describe");
        $this->assertEquals($data['result'][4]['recommandation'] , "recommandation");
        $this->assertEquals(count($data['result'][5]) , 7);
        $this->assertEquals(count($data['result'][5]['component']) , 2);
        $this->assertEquals($data['result'][5]['component']['id'] , 2);
        $this->assertEquals($data['result'][5]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][5]['id'] , 5);
        $this->assertEquals($data['result'][5]['component_id'] , 2);
        $this->assertEquals($data['result'][5]['min'] , 0);
        $this->assertEquals($data['result'][5]['max'] , 25);
        $this->assertEquals($data['result'][5]['describe'] , "You make no attempt to adapt your leadership style to cultural differences. This can have serious repercussions on your job search, and in your career progress.");
        $this->assertEquals($data['result'][5]['recommandation'] , "Try to become more aware of how you interact with people from different cultures, and why they may take offense at your behavior.");
        $this->assertEquals(count($data['result'][6]) , 7);
        $this->assertEquals(count($data['result'][6]['component']) , 2);
        $this->assertEquals($data['result'][6]['component']['id'] , 2);
        $this->assertEquals($data['result'][6]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][6]['id'] , 6);
        $this->assertEquals($data['result'][6]['component_id'] , 2);
        $this->assertEquals($data['result'][6]['min'] , 25);
        $this->assertEquals($data['result'][6]['max'] , 50);
        $this->assertEquals($data['result'][6]['describe'] , "You have an average sensitivity to multicultural differences.");
        $this->assertEquals($data['result'][6]['recommandation'] , "Seek out foreign friends and/or colleagues and ask them to be frank in their feedback to you concerning your sensitivity to cultural differences.");
        $this->assertEquals(count($data['result'][7]) , 7);
        $this->assertEquals(count($data['result'][7]['component']) , 2);
        $this->assertEquals($data['result'][7]['component']['id'] , 2);
        $this->assertEquals($data['result'][7]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][7]['id'] , 7);
        $this->assertEquals($data['result'][7]['component_id'] , 2);
        $this->assertEquals($data['result'][7]['min'] , 50);
        $this->assertEquals($data['result'][7]['max'] , 75);
        $this->assertEquals($data['result'][7]['describe'] , "You are fairly sensitive to multicultural differences, and adapt to them more often than not.");
        $this->assertEquals($data['result'][7]['recommandation'] , "Your score can be improved by becoming more aware of the kinds of issues which require adaptation on your part.");
        $this->assertEquals(count($data['result'][8]) , 7);
        $this->assertEquals(count($data['result'][8]['component']) , 2);
        $this->assertEquals($data['result'][8]['component']['id'] , 2);
        $this->assertEquals($data['result'][8]['component']['name'] , "Multicultural sensitivity");
        $this->assertEquals($data['result'][8]['id'] , 8);
        $this->assertEquals($data['result'][8]['component_id'] , 2);
        $this->assertEquals($data['result'][8]['min'] , 75);
        $this->assertEquals($data['result'][8]['max'] , 100);
        $this->assertEquals($data['result'][8]['describe'] , "You are not only very sensitive to cultural differences that can affect business and decision making, but are also adapt your leadership style accordingly.");
        $this->assertEquals($data['result'][8]['recommandation'] , "You can further improve your skills by learning (more) languages, travelling frequently, and associating with individuals from different countries.");
        $this->assertEquals(count($data['result'][9]) , 7);
        $this->assertEquals(count($data['result'][9]['component']) , 2);
        $this->assertEquals($data['result'][9]['component']['id'] , 3);
        $this->assertEquals($data['result'][9]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][9]['id'] , 9);
        $this->assertEquals($data['result'][9]['component_id'] , 3);
        $this->assertEquals($data['result'][9]['min'] , 0);
        $this->assertEquals($data['result'][9]['max'] , 25);
        $this->assertEquals($data['result'][9]['describe'] , "You lose patience easily when it comes to listening and understanding the difficulties that non-native speakers may have when they try to communicate with you.");
        $this->assertEquals($data['result'][9]['recommandation'] , "You need to become aware of this blockage on your part, and through this awareness attempt to minimize your reaction to it.");
        $this->assertEquals(count($data['result'][10]) , 7);
        $this->assertEquals(count($data['result'][10]['component']) , 2);
        $this->assertEquals($data['result'][10]['component']['id'] , 3);
        $this->assertEquals($data['result'][10]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][10]['id'] , 10);
        $this->assertEquals($data['result'][10]['component_id'] , 3);
        $this->assertEquals($data['result'][10]['min'] , 25);
        $this->assertEquals($data['result'][10]['max'] , 50);
        $this->assertEquals($data['result'][10]['describe'] , "Try to engage more empathetically with people from different cultures.");
        $this->assertEquals($data['result'][10]['recommandation'] , "Be more patient and, if you are not certain of a point being made by a team member, either ask politely or repeat the point as you understand, to ensure that the two of you agree on the real intent of the statement.");
        $this->assertEquals(count($data['result'][11]) , 7);
        $this->assertEquals(count($data['result'][11]['component']) , 2);
        $this->assertEquals($data['result'][11]['component']['id'] , 3);
        $this->assertEquals($data['result'][11]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][11]['id'] , 11);
        $this->assertEquals($data['result'][11]['component_id'] , 3);
        $this->assertEquals($data['result'][11]['min'] , 50);
        $this->assertEquals($data['result'][11]['max'] , 75);
        $this->assertEquals($data['result'][11]['describe'] , "You communicate well with people from different cultures and languages.");
        $this->assertEquals($data['result'][11]['recommandation'] , "You can improve this still more by beginning to focus on non-verbal communication and body language.");
        $this->assertEquals(count($data['result'][12]) , 7);
        $this->assertEquals(count($data['result'][12]['component']) , 2);
        $this->assertEquals($data['result'][12]['component']['id'] , 3);
        $this->assertEquals($data['result'][12]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][12]['id'] , 12);
        $this->assertEquals($data['result'][12]['component_id'] , 3);
        $this->assertEquals($data['result'][12]['min'] , 75);
        $this->assertEquals($data['result'][12]['max'] , 100);
        $this->assertEquals($data['result'][12]['describe'] , "You are an excellent multicultural communicator, able to listen proactively and ask questions in a manner which is neither threatening nor intimidating for people from different cultures.");
        $this->assertEquals($data['result'][12]['recommandation'] , "You can further improve your skills by learning (more) languages, travelling frequently, and associating with individuals from different countries.");
        $this->assertEquals(count($data['result'][13]) , 7);
        $this->assertEquals(count($data['result'][13]['component']) , 2);
        $this->assertEquals($data['result'][13]['component']['id'] , 3);
        $this->assertEquals($data['result'][13]['component']['name'] , "Multicultural communication skill");
        $this->assertEquals($data['result'][13]['id'] , 38);
        $this->assertEquals($data['result'][13]['component_id'] , 3);
        $this->assertEquals($data['result'][13]['min'] , 140);
        $this->assertEquals($data['result'][13]['max'] , 150);
        $this->assertEquals($data['result'][13]['describe'] , "describe updt");
        $this->assertEquals($data['result'][13]['recommandation'] , "recommandation updt");
        $this->assertEquals(count($data['result'][14]) , 7);
        $this->assertEquals(count($data['result'][14]['component']) , 2);
        $this->assertEquals($data['result'][14]['component']['id'] , 4);
        $this->assertEquals($data['result'][14]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][14]['id'] , 13);
        $this->assertEquals($data['result'][14]['component_id'] , 4);
        $this->assertEquals($data['result'][14]['min'] , 0);
        $this->assertEquals($data['result'][14]['max'] , 25);
        $this->assertEquals($data['result'][14]['describe'] , "Your communication and presentation skills are holding you back.");
        $this->assertEquals($data['result'][14]['recommandation'] , "Both in group work conversations, as well as in conversations with your friends, try to become more aware of how much you are proactively listening, as opposed to formulating your own opinions in your mind. Be aware of why you may have verbal outbursts, and what you can do to control them.");
        $this->assertEquals(count($data['result'][15]) , 7);
        $this->assertEquals(count($data['result'][15]['component']) , 2);
        $this->assertEquals($data['result'][15]['component']['id'] , 4);
        $this->assertEquals($data['result'][15]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][15]['id'] , 14);
        $this->assertEquals($data['result'][15]['component_id'] , 4);
        $this->assertEquals($data['result'][15]['min'] , 25);
        $this->assertEquals($data['result'][15]['max'] , 50);
        $this->assertEquals($data['result'][15]['describe'] , "Your communication and presentation skills need to be improved.");
        $this->assertEquals($data['result'][15]['recommandation'] , "Observe the characteristics of “good communicators”: how they listen, encourage others to speak, the tone of voice, the body language. Be more willing to share ideas with others in the group");
        $this->assertEquals(count($data['result'][16]) , 7);
        $this->assertEquals(count($data['result'][16]['component']) , 2);
        $this->assertEquals($data['result'][16]['component']['id'] , 4);
        $this->assertEquals($data['result'][16]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][16]['id'] , 15);
        $this->assertEquals($data['result'][16]['component_id'] , 4);
        $this->assertEquals($data['result'][16]['min'] , 50);
        $this->assertEquals($data['result'][16]['max'] , 75);
        $this->assertEquals($data['result'][16]['describe'] , "Your communication and presentation skills are good, but can be further enhanced.");
        $this->assertEquals($data['result'][16]['recommandation'] , "Listen more, keep your emotions in check, practice presentation skills by volunteering to present as often as possible, use more story telling and entertainment both in group discussions and in your presentations.");
        $this->assertEquals(count($data['result'][17]) , 7);
        $this->assertEquals(count($data['result'][17]['component']) , 2);
        $this->assertEquals($data['result'][17]['component']['id'] , 4);
        $this->assertEquals($data['result'][17]['component']['name'] , "Communication & presentation");
        $this->assertEquals($data['result'][17]['id'] , 16);
        $this->assertEquals($data['result'][17]['component_id'] , 4);
        $this->assertEquals($data['result'][17]['min'] , 75);
        $this->assertEquals($data['result'][17]['max'] , 100);
        $this->assertEquals($data['result'][17]['describe'] , "You are a very good communicator. You are able to adapt your communication style to the audience, are an active listener, and encourage others to communicate in the appropriate way. You are able to get your ideas across articulately and persuasively.");
        $this->assertEquals($data['result'][17]['recommandation'] , null);
        $this->assertEquals(count($data['result'][18]) , 7);
        $this->assertEquals(count($data['result'][18]['component']) , 2);
        $this->assertEquals($data['result'][18]['component']['id'] , 5);
        $this->assertEquals($data['result'][18]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][18]['id'] , 17);
        $this->assertEquals($data['result'][18]['component_id'] , 5);
        $this->assertEquals($data['result'][18]['min'] , 0);
        $this->assertEquals($data['result'][18]['max'] , 25);
        $this->assertEquals($data['result'][18]['describe'] , "You are not considered a good team player. This could be a very serious impediment to your job and professional prospects.");
        $this->assertEquals($data['result'][18]['recommandation'] , "Think less of yourself, and more of others. Recognize that ideas need to be shared by all, and that every idea needs to be considered without being disparaged. It’s not about you, but about the team.");
        $this->assertEquals(count($data['result'][19]) , 7);
        $this->assertEquals(count($data['result'][19]['component']) , 2);
        $this->assertEquals($data['result'][19]['component']['id'] , 5);
        $this->assertEquals($data['result'][19]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][19]['id'] , 18);
        $this->assertEquals($data['result'][19]['component_id'] , 5);
        $this->assertEquals($data['result'][19]['min'] , 25);
        $this->assertEquals($data['result'][19]['max'] , 50);
        $this->assertEquals($data['result'][19]['describe'] , "You are an average team player, but clearly prefer not being in a team but rather on your own. In today’s and tomorrow’s interconnected world, being a “loner” will not be possible.");
        $this->assertEquals(!empty($data['result'][19]['recommandation']) , true);
        $this->assertEquals(count($data['result'][20]) , 7);
        $this->assertEquals(count($data['result'][20]['component']) , 2);
        $this->assertEquals($data['result'][20]['component']['id'] , 5);
        $this->assertEquals($data['result'][20]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][20]['id'] , 19);
        $this->assertEquals($data['result'][20]['component_id'] , 5);
        $this->assertEquals($data['result'][20]['min'] , 50);
        $this->assertEquals($data['result'][20]['max'] , 75);
        $this->assertEquals($data['result'][20]['describe'] , "You are perceived as a good team player, but have room for improvement.");
        $this->assertEquals($data['result'][20]['recommandation'] , "Participate more openly, genuinely and proactively in the group work discussions, assume more responsibility for your tasks in the team, and proactively encourage other team members to do the same.");
        $this->assertEquals(count($data['result'][21]) , 7);
        $this->assertEquals(count($data['result'][21]['component']) , 2);
        $this->assertEquals($data['result'][21]['component']['id'] , 5);
        $this->assertEquals($data['result'][21]['component']['name'] , "Team player");
        $this->assertEquals($data['result'][21]['id'] , 20);
        $this->assertEquals($data['result'][21]['component_id'] , 5);
        $this->assertEquals($data['result'][21]['min'] , 75);
        $this->assertEquals($data['result'][21]['max'] , 100);
        $this->assertEquals($data['result'][21]['describe'] , "You are an excellent player. Your team perceives you as a positive member of group, always willing to listen and incorporate others’ viewpoints. Even when the team is not working as well as you would like, you do not get angry or frustrated and instead try to bring the team together.");
        $this->assertEquals($data['result'][21]['recommandation'] , "Given your skills as a team player, we recommend that you teach others how to become better team players by pointing out to them their lapses and oversights, and what they should do to improve.");
        $this->assertEquals(count($data['result'][22]) , 7);
        $this->assertEquals(count($data['result'][22]['component']) , 2);
        $this->assertEquals($data['result'][22]['component']['id'] , 6);
        $this->assertEquals($data['result'][22]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][22]['id'] , 21);
        $this->assertEquals($data['result'][22]['component_id'] , 6);
        $this->assertEquals($data['result'][22]['min'] , 0);
        $this->assertEquals($data['result'][22]['max'] , 25);
        $this->assertEquals(!empty($data['result'][22]['describe']) , true);
        $this->assertEquals(!empty($data['result'][22]['recommandation']) , true);
        $this->assertEquals(count($data['result'][23]) , 7);
        $this->assertEquals(count($data['result'][23]['component']) , 2);
        $this->assertEquals($data['result'][23]['component']['id'] , 6);
        $this->assertEquals($data['result'][23]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][23]['id'] , 22);
        $this->assertEquals($data['result'][23]['component_id'] , 6);
        $this->assertEquals($data['result'][23]['min'] , 25);
        $this->assertEquals($data['result'][23]['max'] , 50);
        $this->assertEquals($data['result'][23]['describe'] , "You have average leadership skills, but are not perceived as the “go to” person when it comes to making a decision and/or moving the team forward.");
        $this->assertEquals($data['result'][23]['recommandation'] , "Become more aware of the feelings of others, in order to better influence and inspire them. Involve others more in decision making, and do not try to be overtly in control. The best leaders are those who are able to create a sense of project ownership in others.");
        $this->assertEquals(count($data['result'][24]) , 7);
        $this->assertEquals(count($data['result'][24]['component']) , 2);
        $this->assertEquals($data['result'][24]['component']['id'] , 6);
        $this->assertEquals($data['result'][24]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][24]['id'] , 23);
        $this->assertEquals($data['result'][24]['component_id'] , 6);
        $this->assertEquals($data['result'][24]['min'] , 50);
        $this->assertEquals($data['result'][24]['max'] , 75);
        $this->assertEquals($data['result'][24]['describe'] , "You have the potential of being a true leader. Not because you think so, but because others do.");
        $this->assertEquals($data['result'][24]['recommandation'] , "You need to fine-tune and hone that potential. Congratulate others more, encourage them to participate, demonstrate commitment to the project and trust in team members’ abilities and contributions, aim to always bring out the best in people.");
        $this->assertEquals(count($data['result'][25]) , 7);
        $this->assertEquals(count($data['result'][25]['component']) , 2);
        $this->assertEquals($data['result'][25]['component']['id'] , 6);
        $this->assertEquals($data['result'][25]['component']['name'] , "Leadership");
        $this->assertEquals($data['result'][25]['id'] , 24);
        $this->assertEquals($data['result'][25]['component_id'] , 6);
        $this->assertEquals($data['result'][25]['min'] , 75);
        $this->assertEquals($data['result'][25]['max'] , 100);
        $this->assertEquals(!empty($data['result'][25]['describe']) , true);
        $this->assertEquals($data['result'][25]['recommandation'] , null);
        $this->assertEquals(count($data['result'][26]) , 7);
        $this->assertEquals(count($data['result'][26]['component']) , 2);
        $this->assertEquals($data['result'][26]['component']['id'] , 7);
        $this->assertEquals($data['result'][26]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][26]['id'] , 25);
        $this->assertEquals($data['result'][26]['component_id'] , 7);
        $this->assertEquals($data['result'][26]['min'] , 0);
        $this->assertEquals($data['result'][26]['max'] , 25);
        $this->assertEquals($data['result'][26]['describe'] , "Your critical thinking skills are perceived as being very low. If true, this can hold you back in your career progress.");
        $this->assertEquals(!empty($data['result'][26]['recommandation']) , true);
        $this->assertEquals(count($data['result'][27]) , 7);
        $this->assertEquals(count($data['result'][27]['component']) , 2);
        $this->assertEquals($data['result'][27]['component']['id'] , 7);
        $this->assertEquals($data['result'][27]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][27]['id'] , 26);
        $this->assertEquals($data['result'][27]['component_id'] , 7);
        $this->assertEquals($data['result'][27]['min'] , 25);
        $this->assertEquals($data['result'][27]['max'] , 50);
        $this->assertEquals(!empty($data['result'][27]['describe']) , true);
        $this->assertEquals($data['result'][27]['recommandation'] , "Then always make sure to try to assess how others would view your decision, and the relevance and accuracy of their viewpoint.");
        $this->assertEquals(count($data['result'][28]) , 7);
        $this->assertEquals(count($data['result'][28]['component']) , 2);
        $this->assertEquals($data['result'][28]['component']['id'] , 7);
        $this->assertEquals($data['result'][28]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][28]['id'] , 27);
        $this->assertEquals($data['result'][28]['component_id'] , 7);
        $this->assertEquals($data['result'][28]['min'] , 50);
        $this->assertEquals($data['result'][28]['max'] , 75);
        $this->assertEquals($data['result'][28]['describe'] , "You are perceived as being a quite strong critical thinker.");
        $this->assertEquals(!empty($data['result'][28]['recommandation']) , true);
        $this->assertEquals(count($data['result'][29]) , 7);
        $this->assertEquals(count($data['result'][29]['component']) , 2);
        $this->assertEquals($data['result'][29]['component']['id'] , 7);
        $this->assertEquals($data['result'][29]['component']['name'] , "Critical thinking");
        $this->assertEquals($data['result'][29]['id'] , 28);
        $this->assertEquals($data['result'][29]['component_id'] , 7);
        $this->assertEquals($data['result'][29]['min'] , 75);
        $this->assertEquals($data['result'][29]['max'] , 100);
        $this->assertEquals($data['result'][29]['describe'] , "You are perceived to be an excellent critical thinker, which means team members naturally trust and follow your recommendations in terms of what needs to be done, as well as your ability to analyze any situation from multiple perspectives—including their own.");
        $this->assertEquals(!empty($data['result'][29]['recommandation']) , true);
        $this->assertEquals(count($data['result'][30]) , 7);
        $this->assertEquals(count($data['result'][30]['component']) , 2);
        $this->assertEquals($data['result'][30]['component']['id'] , 8);
        $this->assertEquals($data['result'][30]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][30]['id'] , 29);
        $this->assertEquals($data['result'][30]['component_id'] , 8);
        $this->assertEquals($data['result'][30]['min'] , 0);
        $this->assertEquals($data['result'][30]['max'] , 25);
        $this->assertEquals(!empty($data['result'][30]['describe']) , true);
        $this->assertEquals($data['result'][30]['recommandation'] , "Begin to observe others more, be more patient, try to see the value and good in others, and try to put yourself in their shoes.");
        $this->assertEquals(count($data['result'][31]) , 7);
        $this->assertEquals(count($data['result'][31]['component']) , 2);
        $this->assertEquals($data['result'][31]['component']['id'] , 8);
        $this->assertEquals($data['result'][31]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][31]['id'] , 30);
        $this->assertEquals($data['result'][31]['component_id'] , 8);
        $this->assertEquals($data['result'][31]['min'] , 25);
        $this->assertEquals($data['result'][31]['max'] , 50);
        $this->assertEquals($data['result'][31]['describe'] , "You are perceived as being fairly empathetic, although the score suggests that you may not be naturally empathetic, but simply pretending to be so. If so, this can backfire.");
        $this->assertEquals(!empty($data['result'][31]['recommandation']) , true);
        $this->assertEquals(count($data['result'][32]) , 7);
        $this->assertEquals(count($data['result'][32]['component']) , 2);
        $this->assertEquals($data['result'][32]['component']['id'] , 8);
        $this->assertEquals($data['result'][32]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][32]['id'] , 31);
        $this->assertEquals($data['result'][32]['component_id'] , 8);
        $this->assertEquals($data['result'][32]['min'] , 50);
        $this->assertEquals($data['result'][32]['max'] , 75);
        $this->assertEquals(!empty($data['result'][32]['describe']) , true);
        $this->assertEquals($data['result'][32]['recommandation'] , null);
        $this->assertEquals(count($data['result'][33]) , 7);
        $this->assertEquals(count($data['result'][33]['component']) , 2);
        $this->assertEquals($data['result'][33]['component']['id'] , 8);
        $this->assertEquals($data['result'][33]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][33]['id'] , 32);
        $this->assertEquals($data['result'][33]['component_id'] , 8);
        $this->assertEquals($data['result'][33]['min'] , 75);
        $this->assertEquals($data['result'][33]['max'] , 100);
        $this->assertEquals(!empty($data['result'][33]['describe']) , true);
        $this->assertEquals($data['result'][33]['recommandation'] , null);
        $this->assertEquals(count($data['result'][34]) , 7);
        $this->assertEquals(count($data['result'][34]['component']) , 2);
        $this->assertEquals($data['result'][34]['component']['id'] , 9);
        $this->assertEquals($data['result'][34]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][34]['id'] , 33);
        $this->assertEquals($data['result'][34]['component_id'] , 9);
        $this->assertEquals($data['result'][34]['min'] , 0);
        $this->assertEquals($data['result'][34]['max'] , 25);
        $this->assertEquals($data['result'][34]['describe'] , "You are seen as being apathetic and disinterested. While this may not be the case, the perception can seriously impact your professional progress as people do not like to be around such individuals. They drain energy and make the work environment unpleasant.");
        $this->assertEquals($data['result'][34]['recommandation'] , "Even if a meeting or the people in it bore you, make an effort to be more participative and positive.");
        $this->assertEquals(count($data['result'][35]) , 7);
        $this->assertEquals(count($data['result'][35]['component']) , 2);
        $this->assertEquals($data['result'][35]['component']['id'] , 9);
        $this->assertEquals($data['result'][35]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][35]['id'] , 34);
        $this->assertEquals($data['result'][35]['component_id'] , 9);
        $this->assertEquals($data['result'][35]['min'] , 25);
        $this->assertEquals($data['result'][35]['max'] , 50);
        $this->assertEquals($data['result'][35]['describe'] , "You can be perceived as lacking in energy and enthusiasm. Recruiters would be hesitant to take on and recommend such candidates.");
        $this->assertEquals($data['result'][35]['recommandation'] , "Become more aware of how your demeanor and body language are perceived by others. Complain less, contribute more.");
        $this->assertEquals(count($data['result'][36]) , 7);
        $this->assertEquals(count($data['result'][36]['component']) , 2);
        $this->assertEquals($data['result'][36]['component']['id'] , 9);
        $this->assertEquals($data['result'][36]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][36]['id'] , 35);
        $this->assertEquals($data['result'][36]['component_id'] , 9);
        $this->assertEquals($data['result'][36]['min'] , 50);
        $this->assertEquals($data['result'][36]['max'] , 75);
        $this->assertEquals($data['result'][36]['describe'] , "You are considered quite enthusiastic as an individual, good to have around and work and collaborate with.");
        $this->assertEquals($data['result'][36]['recommandation'] , "Focus on improving this still more. Volunteer to take on additional tasks, motivate others to do the same and, when appropriate, smile and laugh more.");
        $this->assertEquals(count($data['result'][37]) , 7);
        $this->assertEquals(count($data['result'][37]['component']) , 2);
        $this->assertEquals($data['result'][37]['component']['id'] , 9);
        $this->assertEquals($data['result'][37]['component']['name'] , "Enthusiasm");
        $this->assertEquals($data['result'][37]['id'] , 36);
        $this->assertEquals($data['result'][37]['component_id'] , 9);
        $this->assertEquals($data['result'][37]['min'] , 75);
        $this->assertEquals($data['result'][37]['max'] , 100);
        $this->assertEquals($data['result'][37]['describe'] , "You are a very enthusiastic person, very rarely complaining, or blaming others, and taking on each new assignment or project with a positive, can-do attitude. Your natural enthusiasm charges others who may be less enthusiastic by nature. Keep it up!");
        $this->assertEquals($data['result'][37]['recommandation'] , null);
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
