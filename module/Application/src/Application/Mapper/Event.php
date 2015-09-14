<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate;

class Event extends AbstractMapper
{
     public function getList($me, $events = null, $ids = null)
    {
        $select = $this->tableGateway->getSql()->select()
            ->columns(array("id", "source", "object", "event", 'event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")))
            ->join('event_user', 'event.id=event_user.event_id', array(),  $select::JOIN_LEFT)
            ->where(array('( event_user.user_id' => $me))
            ->where(array(' event.target = "global")'), Predicate::OP_OR)
            ->order(array('event.id' => 'DESC'));
        
        if(null !== $events){
            $select->where(array('event.event' => $events));
        }
        //exit($this->printSql($select));
            
        return $this->selectWith($select);
    }
}