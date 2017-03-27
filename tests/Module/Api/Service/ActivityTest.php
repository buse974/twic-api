<?php
namespace ModuleTest\Api\Service;

use ModuleTest\Api\AbstractService;

class ActivityTest extends AbstractService
{
    public static $session;

    public static function setUpBeforeClass()
    {
        system('phing -q reset-db deploy-db');

        parent::setUpBeforeClass();
    }

    public function testCanAdd()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('activity.add', [
            'activities' => [
                [
                'date' => '2015-01-02',
                'event' => 'event',
                'object' => [
                    'id' => 3,
                    'name' => 'submission',
                    'value' => 7,
                    'data' => 'dataobj'],
                'target' => [
                    'id' => 3,
                    'name' => 'nametarget',
                    'data' => 'datatarget'
                ]
            ],[
                'date' => '2015-01-03',
                'event' => 'event',
                'object' => [
                    'id' => 3,
                    'name' => 'submission',
                    'value' => 5,
                    'data' => 'dataobj'],
                'target' => [
                    'id' => 3,
                    'name' => 'nametarget',
                    'data' => 'datatarget'
                ]
            ]
            ]
        ]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals($data['result'][0], 1);
        $this->assertEquals($data['result'][1], 2);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanAddTwo()
    {
        $this->setIdentity(4);
        $data = $this->jsonRpc('activity.add', [
            'activities' => [[
                'date' => '2015-01-04',
                'event' => 'eventdeux',
                'object' => [
                    'id' => 3,
                    'name' => 'nameobj',
                    'value' => 3,
                    'data' => 'dataobj'
                ],'target' => [
                        'id' => 3,
                    'name' => 'nametarget',
                    'data' => 'datatarget'
                ],
            ]],
        ]);

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 1);
        $this->assertEquals($data['result'][0], 3);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanGetListDate()
    {
        $this->setIdentity(1);

        $data = $this->jsonRpc('activity.getList', array(
            'start_date' => '2015-01-03 ',
            'end_date' => '2015-01-04'
        ));

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 11);
        $this->assertEquals($data['result'][0]['id'], 2);
        $this->assertEquals($data['result'][0]['event'], "event");
        $this->assertEquals($data['result'][0]['object_id'], 3);
        $this->assertEquals($data['result'][0]['object_name'], "submission");
        $this->assertEquals($data['result'][0]['object_value'], 5);
        $this->assertEquals($data['result'][0]['object_data'], "dataobj");
        $this->assertEquals($data['result'][0]['target_id'], 3);
        $this->assertEquals($data['result'][0]['target_name'], "nametarget");
        $this->assertEquals($data['result'][0]['target_data'], "datatarget");
        $this->assertEquals(!empty($data['result'][0]['date']), true);
        $this->assertEquals($data['result'][0]['user_id'], 4);
        $this->assertEquals(count($data['result'][1]), 11);
        $this->assertEquals($data['result'][1]['id'], 3);
        $this->assertEquals($data['result'][1]['event'], "eventdeux");
        $this->assertEquals($data['result'][1]['object_id'], 3);
        $this->assertEquals($data['result'][1]['object_name'], "nameobj");
        $this->assertEquals($data['result'][1]['object_value'], 3);
        $this->assertEquals($data['result'][1]['object_data'], "dataobj");
        $this->assertEquals($data['result'][1]['target_id'], 3);
        $this->assertEquals($data['result'][1]['target_name'], "nametarget");
        $this->assertEquals($data['result'][1]['target_data'], "datatarget");
        $this->assertEquals(!empty($data['result'][1]['date']), true);
        $this->assertEquals($data['result'][1]['user_id'], 4);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }

    public function testCanGetList()
    {
        $this->setIdentity(1);

        $data = $this->jsonRpc('activity.getList', array('event' => 'event'));

        $this->assertEquals(count($data), 3);
        $this->assertEquals(count($data['result']), 2);
        $this->assertEquals(count($data['result'][0]), 11);
        $this->assertEquals($data['result'][0]['id'], 1);
        $this->assertEquals($data['result'][0]['event'], "event");
        $this->assertEquals($data['result'][0]['object_id'], 3);
        $this->assertEquals($data['result'][0]['object_name'], "submission");
        $this->assertEquals($data['result'][0]['object_value'], 7);
        $this->assertEquals($data['result'][0]['object_data'], 'dataobj');
        $this->assertEquals($data['result'][0]['target_id'], 3);
        $this->assertEquals($data['result'][0]['target_name'], "nametarget");
        $this->assertEquals($data['result'][0]['target_data'], 'datatarget');
        $this->assertEquals(!empty($data['result'][0]['date']), true);
        $this->assertEquals($data['result'][0]['user_id'], 4);
        $this->assertEquals(count($data['result'][1]), 11);
        $this->assertEquals($data['result'][1]['id'], 2);
        $this->assertEquals($data['result'][1]['event'], "event");
        $this->assertEquals($data['result'][1]['object_id'], 3);
        $this->assertEquals($data['result'][1]['object_name'], "submission");
        $this->assertEquals($data['result'][1]['object_value'], 5);
        $this->assertEquals($data['result'][1]['object_data'], 'dataobj');
        $this->assertEquals($data['result'][1]['target_id'], 3);
        $this->assertEquals($data['result'][1]['target_name'], "nametarget");
        $this->assertEquals($data['result'][1]['target_data'], 'datatarget');
        $this->assertEquals(!empty($data['result'][1]['date']), true);
        $this->assertEquals($data['result'][1]['user_id'], 4);
        $this->assertEquals($data['id'], 1);
        $this->assertEquals($data['jsonrpc'], 2.0);
    }
}
