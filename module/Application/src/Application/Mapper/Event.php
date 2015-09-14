<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class Event extends AbstractMapper
{
     public function getList($me, $events = null, $id = null)
    {
<<<<<<< HEAD
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array("id", "source", "object", "event", 'event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")))
            ->join('event_user', 'event.id=event_user.event_id', array(),  $select::JOIN_LEFT)
            ->where(array(' (event_user.user_id = ?' => $me))
            ->where(array(' event.target =  ?)' => "global"), Predicate::OP_OR)
=======
        $select = $this->tableGateway->getSql()->select()
            ->columns(array("id", "source", "object", "event", 'event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")))
            ->join('event_user', 'event.id=event_user.event_id', array())
            
>>>>>>> 59a39a87256f1e1b7c624a938f61210f23d86b6f
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