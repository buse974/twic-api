<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;

class EventUser extends AbstractMapper
{
    public function insertUpdate($date, $me, $event = null)
    {
        $select = new Select('event');
        
        $select->columns(array('user_id' => new Expression("'$me'"), 'read_date' => new Expression("'$date'"), 'event_id' => 'id'))
            ->join('user', 'user.id=user.id', [])
            ->join('event_user', 'event.id = event_user.event_id AND user.id=event_user.user_id', [], $select::JOIN_LEFT)
            ->where(array('user.id' => $me))
            ->where(array("event.target <> 'user' AND event_user.user_id IS NULL "));
        
        if (null !== $event) {
            $select->where(array('event.event' => $event));
        }

        $insert = $this->tableGateway->getSql()->insert();
        $insert->columns(array('user_id', 'read_date', 'event_id'))->select($select);

        return $this->insertWith($insert);    
    }

    public function updateReadMe($date, $me, $event = null)
    {
        $select = new Select('event');
        $select->columns(array('id'));
        if (null !== $event) {
            $select->where(array('event.event' => $event));
        }

        $update = $this->tableGateway->getSql()->update();
        $update->set(array('read_date' => $date))
            ->where(array('event_id IN ?' => $select))
            ->where(array('user_id' => $me))
            ->where(array('read_date IS NULL'));

        return $this->updateWith($update);
    }
}
