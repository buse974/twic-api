<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;

class EventUser extends AbstractMapper
{
    public function insertUpdate($date, $me, $event = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('user_id' => new Expression("'$me'"),'read_date' => new Expression("'$date'")))
            ->join('event', 'event.id=event_user.event_id', array('event_id' => 'id'), $select::JOIN_RIGHT)
            ->where(array("event.target <> 'user'","event_user.read_date IS NULL"));
        
        if (null !== $event) {
            $select->where(array("event.event" => $event));
        }
        
        $insert = $this->tableGateway->getSql()->insert();
        
        $insert->columns(array('user_id','read_date','event_id'))->select($select);
        
        return $this->insertWith($insert);
    }

    public function updateReadMe($date, $me, $event = null)
    {
        $select = new Select('event');
        $select->columns(array('id'));
        if (null !== $event) {
            $select->where(array("event.event" => $event));
        }
        
        $update = $this->tableGateway->getSql()->update();
        $update->set(array('read_date' => $date))
            ->where(array('event_id' => $select))
            ->where(array('user_id' => $me))
            ->where(array('read_date IS NULL'));
        
        return $this->updateWith($update);
    }
}
