<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class Event extends AbstractMapper
{
     public function getList($me, $events = null, $id = null)
    {
        $select = $this->tableGateway->getSql()->select()
            ->columns(array("id", "source", "object", "event", 'event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")))
            ->join('event_user', 'event.id=event_user.event_id', array())
            
            ->order(array('event.id' => 'DESC'));
        
        if(null !== $events){
            $select->where(array('event.event' => $events))
            ->where(array('event_user.user_id' => $me));
        }
        
        if(null !== $id){
            $select->where(array('event.id' => $id));
        }
        
        return $this->selectWith($select);
    }
}