<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Event extends AbstractMapper
{
     public function getList($me, $ids = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('event_user', 'event.id=event_user.event_id', array())
            ->where(array('event_user.user_id' => $me));
        
            
        return $this->selectWith($select);
    }
}