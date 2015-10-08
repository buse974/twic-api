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
        
        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result']['count'], 3);
        $this->assertEquals(count($data['result']['list']), 3);
        $this->assertEquals(count($data['result']['list'][0]), 5);
        $this->assertEquals(count($data['result']['list'][0]['component']), 4);
        $this->assertEquals(count($data['result']['list'][0]['component'][0]), 5);
        $this->assertEquals($data['result']['list'][0]['component'][0]['id'], 1);
        $this->assertEquals(count($data['result']['list'][0]['component'][0]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['component'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['component'][0]['name'], "Awareness");
        $this->assertEquals($data['result']['list'][0]['component'][0]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][0]['describe']), true);
        $this->assertEquals(count($data['result']['list'][0]['component'][1]), 5);
        $this->assertEquals($data['result']['list'][0]['component'][1]['id'], 2);
        $this->assertEquals(count($data['result']['list'][0]['component'][1]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['component'][1]['name'], "Literacy");
        $this->assertEquals($data['result']['list'][0]['component'][1]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][1]['describe']), true);
        $this->assertEquals(count($data['result']['list'][0]['component'][2]), 5);
        $this->assertEquals($data['result']['list'][0]['component'][2]['id'], 3);
        $this->assertEquals($data['result']['list'][0]['component'][2]['name'], "Impulse");
        $this->assertEquals($data['result']['list'][0]['component'][2]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][2]['describe']), true);
        $this->assertEquals(count($data['result']['list'][0]['component'][3]), 5);
        $this->assertEquals($data['result']['list'][0]['component'][3]['id'], 4);
        $this->assertEquals($data['result']['list'][0]['component'][3]['name'], "Performance");
        $this->assertEquals($data['result']['list'][0]['component'][3]['dimension_id'], 1);
        $this->assertEquals(! empty($data['result']['list'][0]['component'][3]['describe']), true);
        $this->assertEquals($data['result']['list'][0]['id'], 1);
        $this->assertEquals($data['result']['list'][0]['name'], "CQ");
        $this->assertEquals(! empty($data['result']['list'][0]['describe']), true);
        $this->assertEquals($data['result']['list'][0]['deleted_date'], null);
        $this->assertEquals(count($data['result']['list'][1]), 5);
        $this->assertEquals(count($data['result']['list'][1]['component']), 7);
        $this->assertEquals(count($data['result']['list'][1]['component'][0]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][0]['id'], 5);
        $this->assertEquals($data['result']['list'][1]['component'][0]['name'], "Positive drive");
        $this->assertEquals($data['result']['list'][1]['component'][0]['dimension_id'], 2);
        $this->assertEquals($data['result']['list'][1]['component'][0]['describe'], "Describe Positive drive");
        $this->assertEquals(count($data['result']['list'][1]['component'][1]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][1]['id'], 6);
        $this->assertEquals($data['result']['list'][1]['component'][1]['name'], "Empathy");
        $this->assertEquals($data['result']['list'][1]['component'][1]['dimension_id'], 2);
        $this->assertEquals($data['result']['list'][1]['component'][1]['describe'], "Describe Empathy");
        $this->assertEquals(count($data['result']['list'][1]['component'][2]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][2]['id'], 7);
        $this->assertEquals($data['result']['list'][1]['component'][2]['name'], "Happy Emotions");
        $this->assertEquals($data['result']['list'][1]['component'][2]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][2]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][3]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][3]['id'], 8);
        $this->assertEquals($data['result']['list'][1]['component'][3]['name'], "Emotional Self-Awareness");
        $this->assertEquals($data['result']['list'][1]['component'][3]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][3]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][4]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][4]['id'], 9);
        $this->assertEquals($data['result']['list'][1]['component'][4]['name'], "Emotional Display");
        $this->assertEquals($data['result']['list'][1]['component'][4]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][4]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][5]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][5]['id'], 10);
        $this->assertEquals($data['result']['list'][1]['component'][5]['name'], "Emotional Management");
        $this->assertEquals($data['result']['list'][1]['component'][5]['dimension_id'], 2);
        $this->assertEquals(! empty($data['result']['list'][1]['component'][5]['describe']), true);
        $this->assertEquals(count($data['result']['list'][1]['component'][6]), 5);
        $this->assertEquals($data['result']['list'][1]['component'][6]['id'], 11);
        $this->assertEquals($data['result']['list'][1]['component'][6]['name'], "Non-specific");
        $this->assertEquals($data['result']['list'][1]['component'][6]['dimension_id'], 2);
        $this->assertEquals($data['result']['list'][1]['component'][6]['describe'], "Non-specific");
        $this->assertEquals($data['result']['list'][1]['id'], 2);
        $this->assertEquals($data['result']['list'][1]['name'], "EQ");
        $this->assertEquals(! empty($data['result']['list'][1]['describe']), true);
        $this->assertEquals($data['result']['list'][1]['deleted_date'], null);
        $this->assertEquals(count($data['result']['list'][2]), 5);
        $this->assertEquals(count($data['result']['list'][2]['component']), 0);
        $this->assertEquals($data['result']['list'][2]['id'], 3);
        $this->assertEquals($data['result']['list'][2]['name'], null);
        $this->assertEquals($data['result']['list'][2]['describe'], "une super dimension UPT");
        $this->assertEquals($data['result']['list'][2]['deleted_date'], null);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
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
        $this->assertEquals($data['result'] , 52);
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
        $this->assertEquals(count($data['result']) , 51);
        $this->assertEquals(count($data['result'][0]) , 2);
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['text'] , "I am aware of the type of specific cultural knowledge which is required to interact with people from different cultural contexts.");
        $this->assertEquals(count($data['result'][1]) , 2);
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['text'] , "I automatically adapt my cultural stance and knowledge when I interact with people coming from a different culture than mine.");
        $this->assertEquals(count($data['result'][2]) , 2);
        $this->assertEquals($data['result'][2]['id'] , 3);
        $this->assertEquals($data['result'][2]['text'] , "I can identify the type of cultural knowledge which is required in various cross-cultural contexts.");
        $this->assertEquals(count($data['result'][3]) , 2);
        $this->assertEquals($data['result'][3]['id'] , 4);
        $this->assertEquals($data['result'][3]['text'] , "I test the validity and the accuracy of my cultural knowledge while dealing with people from different cultures.");
        $this->assertEquals(count($data['result'][4]) , 2);
        $this->assertEquals($data['result'][4]['id'] , 5);
        $this->assertEquals($data['result'][4]['text'] , "I know the legal and the economic environment of other cultures.");
        $this->assertEquals(count($data['result'][5]) , 2);
        $this->assertEquals($data['result'][5]['id'] , 6);
        $this->assertEquals($data['result'][5]['text'] , "I know foreign languages.");
        $this->assertEquals(count($data['result'][6]) , 2);
        $this->assertEquals($data['result'][6]['id'] , 7);
        $this->assertEquals($data['result'][6]['text'] , "I know other cultures religions and values.");
        $this->assertEquals(count($data['result'][7]) , 2);
        $this->assertEquals($data['result'][7]['id'] , 8);
        $this->assertEquals($data['result'][7]['text'] , "I know the artistic heritage and craft of other cultures.");
        $this->assertEquals(count($data['result'][8]) , 2);
        $this->assertEquals($data['result'][8]['id'] , 9);
        $this->assertEquals($data['result'][8]['text'] , "I know the rules of non-verbal communication in other cultures.");
        $this->assertEquals(count($data['result'][9]) , 2);
        $this->assertEquals($data['result'][9]['id'] , 10);
        $this->assertEquals($data['result'][9]['text'] , "I like dealing with people from different cultures.");
        $this->assertEquals(count($data['result'][10]) , 2);
        $this->assertEquals($data['result'][10]['id'] , 11);
        $this->assertEquals($data['result'][10]['text'] , "I am secure when I have to socialize with people coming from unfamiliar cultures.");
        $this->assertEquals(count($data['result'][11]) , 2);
        $this->assertEquals($data['result'][11]['id'] , 12);
        $this->assertEquals($data['result'][11]['text'] , "I can handle the stress of adapting to new cultures.");
        $this->assertEquals(count($data['result'][12]) , 2);
        $this->assertEquals($data['result'][12]['id'] , 13);
        $this->assertEquals($data['result'][12]['text'] , "I like living in different cultural contexts.");
        $this->assertEquals(count($data['result'][13]) , 2);
        $this->assertEquals($data['result'][13]['id'] , 14);
        $this->assertEquals($data['result'][13]['text'] , "I am sure that I can get used to living, shopping, and eating conditions in different cultures.");
        $this->assertEquals(count($data['result'][14]) , 2);
        $this->assertEquals($data['result'][14]['id'] , 15);
        $this->assertEquals($data['result'][14]['text'] , "I adapt my verbal communication when cross-cultural interactions require it.");
        $this->assertEquals(count($data['result'][15]) , 2);
        $this->assertEquals($data['result'][15]['id'] , 16);
        $this->assertEquals($data['result'][15]['text'] , "The pace of my talk is different depending on the cultural context.");
        $this->assertEquals(count($data['result'][16]) , 2);
        $this->assertEquals($data['result'][16]['id'] , 17);
        $this->assertEquals($data['result'][16]['text'] , "I adjust my non-verbal communication when cross-cultural interactions require it.");
        $this->assertEquals(count($data['result'][17]) , 2);
        $this->assertEquals($data['result'][17]['id'] , 18);
        $this->assertEquals($data['result'][17]['text'] , "I adapt the expression of my face according to the cultural context.");
        $this->assertEquals(count($data['result'][18]) , 2);
        $this->assertEquals($data['result'][18]['id'] , 19);
        $this->assertEquals($data['result'][18]['text'] , "When I am faced with obstacles, I remember times I faced similar obstacles and overcame them.");
        $this->assertEquals(count($data['result'][19]) , 2);
        $this->assertEquals($data['result'][19]['id'] , 20);
        $this->assertEquals($data['result'][19]['text'] , "I expect that I will do well on most things I try.");
        $this->assertEquals(count($data['result'][20]) , 2);
        $this->assertEquals($data['result'][20]['id'] , 21);
        $this->assertEquals($data['result'][20]['text'] , "Some of the major events of my life have led me to re?evaluate what is important and not important.");
        $this->assertEquals(count($data['result'][21]) , 2);
        $this->assertEquals($data['result'][21]['id'] , 22);
        $this->assertEquals($data['result'][21]['text'] , "I expect good things to happen.");
        $this->assertEquals(count($data['result'][22]) , 2);
        $this->assertEquals($data['result'][22]['id'] , 23);
        $this->assertEquals($data['result'][22]['text'] , "When I am in a positive mood, solving  problems is easy for me.");
        $this->assertEquals(count($data['result'][23]) , 2);
        $this->assertEquals($data['result'][23]['id'] , 24);
        $this->assertEquals($data['result'][23]['text'] , "When I am in a positive mood, I am able to come up with new ideas.");
        $this->assertEquals(count($data['result'][24]) , 2);
        $this->assertEquals($data['result'][24]['id'] , 25);
        $this->assertEquals($data['result'][24]['text'] , "I motivate myself by imagining a good outcome to tasks I take on.");
        $this->assertEquals(count($data['result'][25]) , 2);
        $this->assertEquals($data['result'][25]['id'] , 26);
        $this->assertEquals($data['result'][25]['text'] , "Other people find it easy to confide in me.");
        $this->assertEquals(count($data['result'][26]) , 2);
        $this->assertEquals($data['result'][26]['id'] , 27);
        $this->assertEquals($data['result'][26]['text'] , "By looking at their facial expressions, I recognize the     emotions people are experiencing.");
        $this->assertEquals(count($data['result'][27]) , 2);
        $this->assertEquals($data['result'][27]['id'] , 28);
        $this->assertEquals($data['result'][27]['text'] , "When another person tells me about an important event in  his or her life, I almost feel as though I experienced this event myself.");
        $this->assertEquals(count($data['result'][28]) , 2);
        $this->assertEquals($data['result'][28]['id'] , 29);
        $this->assertEquals($data['result'][28]['text'] , "When I feel a change in emotions, I tend to come up   with new ideas.");
        $this->assertEquals(count($data['result'][29]) , 2);
        $this->assertEquals($data['result'][29]['id'] , 30);
        $this->assertEquals($data['result'][29]['text'] , "I know what other people are feeling just by looking at them.");
        $this->assertEquals(count($data['result'][30]) , 2);
        $this->assertEquals($data['result'][30]['id'] , 31);
        $this->assertEquals($data['result'][30]['text'] , "I help other people feel better when  they are down.");
        $this->assertEquals(count($data['result'][31]) , 2);
        $this->assertEquals($data['result'][31]['id'] , 32);
        $this->assertEquals($data['result'][31]['text'] , "I can tell how people are feeling by listening to the tone of their voice.");
        $this->assertEquals(count($data['result'][32]) , 2);
        $this->assertEquals($data['result'][32]['id'] , 33);
        $this->assertEquals($data['result'][32]['text'] , "It is difficult for me to understand why people feel the way  they do.");
        $this->assertEquals(count($data['result'][33]) , 2);
        $this->assertEquals($data['result'][33]['id'] , 34);
        $this->assertEquals($data['result'][33]['text'] , "When I experience a positive emotion, I know how to  make it last.");
        $this->assertEquals(count($data['result'][34]) , 2);
        $this->assertEquals($data['result'][34]['id'] , 35);
        $this->assertEquals($data['result'][34]['text'] , "I arrange events others enjoy.");
        $this->assertEquals(count($data['result'][35]) , 2);
        $this->assertEquals($data['result'][35]['id'] , 36);
        $this->assertEquals($data['result'][35]['text'] , "I seek out activities that make me happy.");
        $this->assertEquals(count($data['result'][36]) , 2);
        $this->assertEquals($data['result'][36]['id'] , 37);
        $this->assertEquals($data['result'][36]['text'] , "I use good moods to help myself keep trying in the face of obstacles.");
        $this->assertEquals(count($data['result'][37]) , 2);
        $this->assertEquals($data['result'][37]['id'] , 38);
        $this->assertEquals($data['result'][37]['text'] , "Emotions are one of the things that make my life worth living.");
        $this->assertEquals(count($data['result'][38]) , 2);
        $this->assertEquals($data['result'][38]['id'] , 39);
        $this->assertEquals($data['result'][38]['text'] , "I am aware of my emotions as I experience them.");
        $this->assertEquals(count($data['result'][39]) , 2);
        $this->assertEquals($data['result'][39]['id'] , 40);
        $this->assertEquals($data['result'][39]['text'] , "I know why my emotions change.");
        $this->assertEquals(count($data['result'][40]) , 2);
        $this->assertEquals($data['result'][40]['id'] , 41);
        $this->assertEquals($data['result'][40]['text'] , "I easily recognize my emotions as I experience them.");
        $this->assertEquals(count($data['result'][41]) , 2);
        $this->assertEquals($data['result'][41]['id'] , 42);
        $this->assertEquals($data['result'][41]['text'] , "I find it hard to understand the non?verbal messages of other.");
        $this->assertEquals(count($data['result'][42]) , 2);
        $this->assertEquals($data['result'][42]['id'] , 43);
        $this->assertEquals($data['result'][42]['text'] , "I am aware of the non?verbal messages I send to others.");
        $this->assertEquals(count($data['result'][43]) , 2);
        $this->assertEquals($data['result'][43]['id'] , 44);
        $this->assertEquals($data['result'][43]['text'] , "I am aware of the non?verbal messages other people send.");
        $this->assertEquals(count($data['result'][44]) , 2);
        $this->assertEquals($data['result'][44]['id'] , 45);
        $this->assertEquals($data['result'][44]['text'] , "I know when to speak about my personal problems to others.");
        $this->assertEquals(count($data['result'][45]) , 2);
        $this->assertEquals($data['result'][45]['id'] , 46);
        $this->assertEquals($data['result'][45]['text'] , "I have control over my emotions.");
        $this->assertEquals(count($data['result'][46]) , 2);
        $this->assertEquals($data['result'][46]['id'] , 47);
        $this->assertEquals($data['result'][46]['text'] , "I compliment others when they have done something well.");
        $this->assertEquals(count($data['result'][47]) , 2);
        $this->assertEquals($data['result'][47]['id'] , 48);
        $this->assertEquals($data['result'][47]['text'] , "When I am faced with a challenge, I give up because.");
        $this->assertEquals(count($data['result'][48]) , 2);
        $this->assertEquals($data['result'][48]['id'] , 49);
        $this->assertEquals($data['result'][48]['text'] , "When my mood changes, I see new possibilities.");
        $this->assertEquals(count($data['result'][49]) , 2);
        $this->assertEquals($data['result'][49]['id'] , 50);
        $this->assertEquals(!empty($data['result'][49]['text']) , true);
        $this->assertEquals(count($data['result'][50]) , 2);
        $this->assertEquals($data['result'][50]['id'] , 51);
        $this->assertEquals($data['result'][50]['text'] , "I present myself in a way that makes a good impression on others.");
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
                'search' => 'know'
            )
        );
        
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 9);
        $this->assertEquals(count($data['result']['list']) , 4);
        $this->assertEquals(count($data['result']['list'][0]) , 2);
        $this->assertEquals($data['result']['list'][0]['id'] , 6);
        $this->assertEquals($data['result']['list'][0]['text'] , "I know foreign languages.");
        $this->assertEquals(count($data['result']['list'][1]) , 2);
        $this->assertEquals($data['result']['list'][1]['id'] , 7);
        $this->assertEquals($data['result']['list'][1]['text'] , "I know other cultures religions and values.");
        $this->assertEquals(count($data['result']['list'][2]) , 2);
        $this->assertEquals($data['result']['list'][2]['id'] , 8);
        $this->assertEquals($data['result']['list'][2]['text'] , "I know the artistic heritage and craft of other cultures.");
        $this->assertEquals(count($data['result']['list'][3]) , 2);
        $this->assertEquals($data['result']['list'][3]['id'] , 9);
        $this->assertEquals($data['result']['list'][3]['text'] , "I know the rules of non-verbal communication in other cultures.");
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
        $this->assertEquals($data['result'] , 12);
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
            'component.getList', array('filter' => ['n' => 3], 'dimension' => 1, 'search' => 'drive')
        );
    
        $this->assertEquals(count($data) , 3);
        $this->assertEquals(count($data['result']) , 2);
        $this->assertEquals($data['result']['count'] , 1);
        $this->assertEquals(count($data['result']['list']) , 1);
        $this->assertEquals(count($data['result']['list'][0]) , 5);
        $this->assertEquals(count($data['result']['list'][0]['dimension']) , 2);
        $this->assertEquals($data['result']['list'][0]['dimension']['id'] , 1);
        $this->assertEquals($data['result']['list'][0]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result']['list'][0]['id'] , 3);
        $this->assertEquals($data['result']['list'][0]['name'] , "Impulse");
        $this->assertEquals($data['result']['list'][0]['dimension_id'] , 1);
        $this->assertEquals(!empty($data['result']['list'][0]['describe']) , true);
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
        $this->assertEquals($data['result'] , 6);
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
        $this->assertEquals($data['result'] , 7);
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
        $this->assertEquals(count($data['result']) , 7);
        $this->assertEquals(count($data['result'][0]) , 6);
        $this->assertEquals(count($data['result'][0]['dimension']) , 2);
        $this->assertEquals($data['result'][0]['dimension']['id'] , 1);
        $this->assertEquals($data['result'][0]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['dimension_id'] , 1);
        $this->assertEquals($data['result'][0]['min'] , 0);
        $this->assertEquals($data['result'][0]['max'] , 50);
        $this->assertEquals($data['result'][0]['describe'] , "describe min0 max50 CQ");
        $this->assertEquals(count($data['result'][1]) , 6);
        $this->assertEquals(count($data['result'][1]['dimension']) , 2);
        $this->assertEquals($data['result'][1]['dimension']['id'] , 1);
        $this->assertEquals($data['result'][1]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['dimension_id'] , 1);
        $this->assertEquals($data['result'][1]['min'] , 50);
        $this->assertEquals($data['result'][1]['max'] , 100);
        $this->assertEquals($data['result'][1]['describe'] , "describe min50 max100 CQ");
        $this->assertEquals(count($data['result'][2]) , 6);
        $this->assertEquals(count($data['result'][2]['dimension']) , 2);
        $this->assertEquals($data['result'][2]['dimension']['id'] , 2);
        $this->assertEquals($data['result'][2]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result'][2]['id'] , 3);
        $this->assertEquals($data['result'][2]['dimension_id'] , 2);
        $this->assertEquals($data['result'][2]['min'] , 0);
        $this->assertEquals($data['result'][2]['max'] , 30);
        $this->assertEquals($data['result'][2]['describe'] , "describe min0 max30 EQ");
        $this->assertEquals(count($data['result'][3]) , 6);
        $this->assertEquals(count($data['result'][3]['dimension']) , 2);
        $this->assertEquals($data['result'][3]['dimension']['id'] , 2);
        $this->assertEquals($data['result'][3]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result'][3]['id'] , 4);
        $this->assertEquals($data['result'][3]['dimension_id'] , 2);
        $this->assertEquals($data['result'][3]['min'] , 30);
        $this->assertEquals($data['result'][3]['max'] , 66);
        $this->assertEquals($data['result'][3]['describe'] , "describe min30 max66 EQ");
        $this->assertEquals(count($data['result'][4]) , 6);
        $this->assertEquals(count($data['result'][4]['dimension']) , 2);
        $this->assertEquals($data['result'][4]['dimension']['id'] , 2);
        $this->assertEquals($data['result'][4]['dimension']['name'] , "EQ");
        $this->assertEquals($data['result'][4]['id'] , 5);
        $this->assertEquals($data['result'][4]['dimension_id'] , 2);
        $this->assertEquals($data['result'][4]['min'] , 66);
        $this->assertEquals($data['result'][4]['max'] , 100);
        $this->assertEquals($data['result'][4]['describe'] , "describe min66 max100 EQ");
        $this->assertEquals(count($data['result'][5]) , 6);
        $this->assertEquals(count($data['result'][5]['dimension']) , 2);
        $this->assertEquals($data['result'][5]['dimension']['id'] , 1);
        $this->assertEquals($data['result'][5]['dimension']['name'] , "CQ");
        $this->assertEquals($data['result'][5]['id'] , 6);
        $this->assertEquals($data['result'][5]['dimension_id'] , 1);
        $this->assertEquals($data['result'][5]['min'] , 110);
        $this->assertEquals($data['result'][5]['max'] , 120);
        $this->assertEquals($data['result'][5]['describe'] , "describe");
        $this->assertEquals(count($data['result'][6]) , 6);
        $this->assertEquals(count($data['result'][6]['dimension']) , 2);
        $this->assertEquals($data['result'][6]['dimension']['id'] , 3);
        $this->assertEquals($data['result'][6]['dimension']['name'] , null);
        $this->assertEquals($data['result'][6]['id'] , 7);
        $this->assertEquals($data['result'][6]['dimension_id'] , 3);
        $this->assertEquals($data['result'][6]['min'] , 140);
        $this->assertEquals($data['result'][6]['max'] , 150);
        $this->assertEquals($data['result'][6]['describe'] , "describe2updt");
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
        $this->assertEquals($data['result'] , 23);
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
        $this->assertEquals($data['result'] , 24);
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
        $this->assertEquals(count($data['result']) , 24);
        $this->assertEquals(count($data['result'][0]) , 7);
        $this->assertEquals(count($data['result'][0]['component']) , 2);
        $this->assertEquals($data['result'][0]['component']['id'] , 1);
        $this->assertEquals($data['result'][0]['component']['name'] , "Awareness");
        $this->assertEquals($data['result'][0]['id'] , 1);
        $this->assertEquals($data['result'][0]['component_id'] , 1);
        $this->assertEquals($data['result'][0]['min'] , 0);
        $this->assertEquals($data['result'][0]['max'] , 50);
        $this->assertEquals($data['result'][0]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][0]['recommandation'] , null);
        $this->assertEquals(count($data['result'][1]) , 7);
        $this->assertEquals(count($data['result'][1]['component']) , 2);
        $this->assertEquals($data['result'][1]['component']['id'] , 1);
        $this->assertEquals($data['result'][1]['component']['name'] , "Awareness");
        $this->assertEquals($data['result'][1]['id'] , 2);
        $this->assertEquals($data['result'][1]['component_id'] , 1);
        $this->assertEquals($data['result'][1]['min'] , 50);
        $this->assertEquals($data['result'][1]['max'] , 100);
        $this->assertEquals($data['result'][1]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][1]['recommandation'] , null);
        $this->assertEquals(count($data['result'][2]) , 7);
        $this->assertEquals(count($data['result'][2]['component']) , 2);
        $this->assertEquals($data['result'][2]['component']['id'] , 1);
        $this->assertEquals($data['result'][2]['component']['name'] , "Awareness");
        $this->assertEquals($data['result'][2]['id'] , 23);
        $this->assertEquals($data['result'][2]['component_id'] , 1);
        $this->assertEquals($data['result'][2]['min'] , 110);
        $this->assertEquals($data['result'][2]['max'] , 120);
        $this->assertEquals($data['result'][2]['describe'] , "describe");
        $this->assertEquals($data['result'][2]['recommandation'] , "recommandation");
        $this->assertEquals(count($data['result'][3]) , 7);
        $this->assertEquals(count($data['result'][3]['component']) , 2);
        $this->assertEquals($data['result'][3]['component']['id'] , 2);
        $this->assertEquals($data['result'][3]['component']['name'] , "Literacy");
        $this->assertEquals($data['result'][3]['id'] , 3);
        $this->assertEquals($data['result'][3]['component_id'] , 2);
        $this->assertEquals($data['result'][3]['min'] , 0);
        $this->assertEquals($data['result'][3]['max'] , 50);
        $this->assertEquals($data['result'][3]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][3]['recommandation'] , null);
        $this->assertEquals(count($data['result'][4]) , 7);
        $this->assertEquals(count($data['result'][4]['component']) , 2);
        $this->assertEquals($data['result'][4]['component']['id'] , 2);
        $this->assertEquals($data['result'][4]['component']['name'] , "Literacy");
        $this->assertEquals($data['result'][4]['id'] , 4);
        $this->assertEquals($data['result'][4]['component_id'] , 2);
        $this->assertEquals($data['result'][4]['min'] , 50);
        $this->assertEquals($data['result'][4]['max'] , 100);
        $this->assertEquals($data['result'][4]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][4]['recommandation'] , null);
        $this->assertEquals(count($data['result'][5]) , 7);
        $this->assertEquals(count($data['result'][5]['component']) , 2);
        $this->assertEquals($data['result'][5]['component']['id'] , 3);
        $this->assertEquals($data['result'][5]['component']['name'] , "Impulse");
        $this->assertEquals($data['result'][5]['id'] , 5);
        $this->assertEquals($data['result'][5]['component_id'] , 3);
        $this->assertEquals($data['result'][5]['min'] , 0);
        $this->assertEquals($data['result'][5]['max'] , 50);
        $this->assertEquals($data['result'][5]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][5]['recommandation'] , null);
        $this->assertEquals(count($data['result'][6]) , 7);
        $this->assertEquals(count($data['result'][6]['component']) , 2);
        $this->assertEquals($data['result'][6]['component']['id'] , 3);
        $this->assertEquals($data['result'][6]['component']['name'] , "Impulse");
        $this->assertEquals($data['result'][6]['id'] , 6);
        $this->assertEquals($data['result'][6]['component_id'] , 3);
        $this->assertEquals($data['result'][6]['min'] , 50);
        $this->assertEquals($data['result'][6]['max'] , 100);
        $this->assertEquals($data['result'][6]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][6]['recommandation'] , null);
        $this->assertEquals(count($data['result'][7]) , 7);
        $this->assertEquals(count($data['result'][7]['component']) , 2);
        $this->assertEquals($data['result'][7]['component']['id'] , 3);
        $this->assertEquals($data['result'][7]['component']['name'] , "Impulse");
        $this->assertEquals($data['result'][7]['id'] , 24);
        $this->assertEquals($data['result'][7]['component_id'] , 3);
        $this->assertEquals($data['result'][7]['min'] , 140);
        $this->assertEquals($data['result'][7]['max'] , 150);
        $this->assertEquals($data['result'][7]['describe'] , "describe updt");
        $this->assertEquals($data['result'][7]['recommandation'] , "recommandation updt");
        $this->assertEquals(count($data['result'][8]) , 7);
        $this->assertEquals(count($data['result'][8]['component']) , 2);
        $this->assertEquals($data['result'][8]['component']['id'] , 4);
        $this->assertEquals($data['result'][8]['component']['name'] , "Performance");
        $this->assertEquals($data['result'][8]['id'] , 7);
        $this->assertEquals($data['result'][8]['component_id'] , 4);
        $this->assertEquals($data['result'][8]['min'] , 0);
        $this->assertEquals($data['result'][8]['max'] , 50);
        $this->assertEquals($data['result'][8]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][8]['recommandation'] , null);
        $this->assertEquals(count($data['result'][9]) , 7);
        $this->assertEquals(count($data['result'][9]['component']) , 2);
        $this->assertEquals($data['result'][9]['component']['id'] , 4);
        $this->assertEquals($data['result'][9]['component']['name'] , "Performance");
        $this->assertEquals($data['result'][9]['id'] , 8);
        $this->assertEquals($data['result'][9]['component_id'] , 4);
        $this->assertEquals($data['result'][9]['min'] , 50);
        $this->assertEquals($data['result'][9]['max'] , 100);
        $this->assertEquals($data['result'][9]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][9]['recommandation'] , null);
        $this->assertEquals(count($data['result'][10]) , 7);
        $this->assertEquals(count($data['result'][10]['component']) , 2);
        $this->assertEquals($data['result'][10]['component']['id'] , 5);
        $this->assertEquals($data['result'][10]['component']['name'] , "Positive drive");
        $this->assertEquals($data['result'][10]['id'] , 9);
        $this->assertEquals($data['result'][10]['component_id'] , 5);
        $this->assertEquals($data['result'][10]['min'] , 0);
        $this->assertEquals($data['result'][10]['max'] , 50);
        $this->assertEquals($data['result'][10]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][10]['recommandation'] , null);
        $this->assertEquals(count($data['result'][11]) , 7);
        $this->assertEquals(count($data['result'][11]['component']) , 2);
        $this->assertEquals($data['result'][11]['component']['id'] , 5);
        $this->assertEquals($data['result'][11]['component']['name'] , "Positive drive");
        $this->assertEquals($data['result'][11]['id'] , 10);
        $this->assertEquals($data['result'][11]['component_id'] , 5);
        $this->assertEquals($data['result'][11]['min'] , 50);
        $this->assertEquals($data['result'][11]['max'] , 100);
        $this->assertEquals($data['result'][11]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][11]['recommandation'] , null);
        $this->assertEquals(count($data['result'][12]) , 7);
        $this->assertEquals(count($data['result'][12]['component']) , 2);
        $this->assertEquals($data['result'][12]['component']['id'] , 6);
        $this->assertEquals($data['result'][12]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][12]['id'] , 11);
        $this->assertEquals($data['result'][12]['component_id'] , 6);
        $this->assertEquals($data['result'][12]['min'] , 0);
        $this->assertEquals($data['result'][12]['max'] , 50);
        $this->assertEquals($data['result'][12]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][12]['recommandation'] , null);
        $this->assertEquals(count($data['result'][13]) , 7);
        $this->assertEquals(count($data['result'][13]['component']) , 2);
        $this->assertEquals($data['result'][13]['component']['id'] , 6);
        $this->assertEquals($data['result'][13]['component']['name'] , "Empathy");
        $this->assertEquals($data['result'][13]['id'] , 12);
        $this->assertEquals($data['result'][13]['component_id'] , 6);
        $this->assertEquals($data['result'][13]['min'] , 50);
        $this->assertEquals($data['result'][13]['max'] , 100);
        $this->assertEquals($data['result'][13]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][13]['recommandation'] , null);
        $this->assertEquals(count($data['result'][14]) , 7);
        $this->assertEquals(count($data['result'][14]['component']) , 2);
        $this->assertEquals($data['result'][14]['component']['id'] , 7);
        $this->assertEquals($data['result'][14]['component']['name'] , "Happy Emotions");
        $this->assertEquals($data['result'][14]['id'] , 13);
        $this->assertEquals($data['result'][14]['component_id'] , 7);
        $this->assertEquals($data['result'][14]['min'] , 0);
        $this->assertEquals($data['result'][14]['max'] , 50);
        $this->assertEquals($data['result'][14]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][14]['recommandation'] , null);
        $this->assertEquals(count($data['result'][15]) , 7);
        $this->assertEquals(count($data['result'][15]['component']) , 2);
        $this->assertEquals($data['result'][15]['component']['id'] , 7);
        $this->assertEquals($data['result'][15]['component']['name'] , "Happy Emotions");
        $this->assertEquals($data['result'][15]['id'] , 14);
        $this->assertEquals($data['result'][15]['component_id'] , 7);
        $this->assertEquals($data['result'][15]['min'] , 50);
        $this->assertEquals($data['result'][15]['max'] , 100);
        $this->assertEquals($data['result'][15]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][15]['recommandation'] , null);
        $this->assertEquals(count($data['result'][16]) , 7);
        $this->assertEquals(count($data['result'][16]['component']) , 2);
        $this->assertEquals($data['result'][16]['component']['id'] , 8);
        $this->assertEquals($data['result'][16]['component']['name'] , "Emotional Self-Awareness");
        $this->assertEquals($data['result'][16]['id'] , 15);
        $this->assertEquals($data['result'][16]['component_id'] , 8);
        $this->assertEquals($data['result'][16]['min'] , 0);
        $this->assertEquals($data['result'][16]['max'] , 50);
        $this->assertEquals($data['result'][16]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][16]['recommandation'] , null);
        $this->assertEquals(count($data['result'][17]) , 7);
        $this->assertEquals(count($data['result'][17]['component']) , 2);
        $this->assertEquals($data['result'][17]['component']['id'] , 8);
        $this->assertEquals($data['result'][17]['component']['name'] , "Emotional Self-Awareness");
        $this->assertEquals($data['result'][17]['id'] , 16);
        $this->assertEquals($data['result'][17]['component_id'] , 8);
        $this->assertEquals($data['result'][17]['min'] , 50);
        $this->assertEquals($data['result'][17]['max'] , 100);
        $this->assertEquals($data['result'][17]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][17]['recommandation'] , null);
        $this->assertEquals(count($data['result'][18]) , 7);
        $this->assertEquals(count($data['result'][18]['component']) , 2);
        $this->assertEquals($data['result'][18]['component']['id'] , 9);
        $this->assertEquals($data['result'][18]['component']['name'] , "Emotional Display");
        $this->assertEquals($data['result'][18]['id'] , 17);
        $this->assertEquals($data['result'][18]['component_id'] , 9);
        $this->assertEquals($data['result'][18]['min'] , 0);
        $this->assertEquals($data['result'][18]['max'] , 50);
        $this->assertEquals($data['result'][18]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][18]['recommandation'] , null);
        $this->assertEquals(count($data['result'][19]) , 7);
        $this->assertEquals(count($data['result'][19]['component']) , 2);
        $this->assertEquals($data['result'][19]['component']['id'] , 9);
        $this->assertEquals($data['result'][19]['component']['name'] , "Emotional Display");
        $this->assertEquals($data['result'][19]['id'] , 18);
        $this->assertEquals($data['result'][19]['component_id'] , 9);
        $this->assertEquals($data['result'][19]['min'] , 50);
        $this->assertEquals($data['result'][19]['max'] , 100);
        $this->assertEquals($data['result'][19]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][19]['recommandation'] , null);
        $this->assertEquals(count($data['result'][20]) , 7);
        $this->assertEquals(count($data['result'][20]['component']) , 2);
        $this->assertEquals($data['result'][20]['component']['id'] , 10);
        $this->assertEquals($data['result'][20]['component']['name'] , "Emotional Management");
        $this->assertEquals($data['result'][20]['id'] , 19);
        $this->assertEquals($data['result'][20]['component_id'] , 10);
        $this->assertEquals($data['result'][20]['min'] , 0);
        $this->assertEquals($data['result'][20]['max'] , 50);
        $this->assertEquals($data['result'][20]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][20]['recommandation'] , null);
        $this->assertEquals(count($data['result'][21]) , 7);
        $this->assertEquals(count($data['result'][21]['component']) , 2);
        $this->assertEquals($data['result'][21]['component']['id'] , 10);
        $this->assertEquals($data['result'][21]['component']['name'] , "Emotional Management");
        $this->assertEquals($data['result'][21]['id'] , 20);
        $this->assertEquals($data['result'][21]['component_id'] , 10);
        $this->assertEquals($data['result'][21]['min'] , 50);
        $this->assertEquals($data['result'][21]['max'] , 100);
        $this->assertEquals($data['result'][21]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][21]['recommandation'] , null);
        $this->assertEquals(count($data['result'][22]) , 7);
        $this->assertEquals(count($data['result'][22]['component']) , 2);
        $this->assertEquals($data['result'][22]['component']['id'] , 11);
        $this->assertEquals($data['result'][22]['component']['name'] , "Non-specific");
        $this->assertEquals($data['result'][22]['id'] , 21);
        $this->assertEquals($data['result'][22]['component_id'] , 11);
        $this->assertEquals($data['result'][22]['min'] , 0);
        $this->assertEquals($data['result'][22]['max'] , 50);
        $this->assertEquals($data['result'][22]['describe'] , "describe component 0-50");
        $this->assertEquals($data['result'][22]['recommandation'] , null);
        $this->assertEquals(count($data['result'][23]) , 7);
        $this->assertEquals(count($data['result'][23]['component']) , 2);
        $this->assertEquals($data['result'][23]['component']['id'] , 11);
        $this->assertEquals($data['result'][23]['component']['name'] , "Non-specific");
        $this->assertEquals($data['result'][23]['id'] , 22);
        $this->assertEquals($data['result'][23]['component_id'] , 11);
        $this->assertEquals($data['result'][23]['min'] , 50);
        $this->assertEquals($data['result'][23]['max'] , 100);
        $this->assertEquals($data['result'][23]['describe'] , "describe component 50-100");
        $this->assertEquals($data['result'][23]['recommandation'] , null);
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
