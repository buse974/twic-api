<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Activity extends AbstractMapper
{
    public function aggregate($event, $user, $object_id, $object_name = 'item_prog')
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'event',
            'activity$value_user' => new Expression('SUM( IF(user_id='.$user.', object_value, 0))'),
            'activity$value_total' => new Expression('SUM(object_value)'),
        ))->where(array(
            'event' => $event, 
            'object_id' => $object_id, 
            'object_name' => $object_name));
        
        return $this->selectWith($select);
    }
}