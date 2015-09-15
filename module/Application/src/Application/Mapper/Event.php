<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class Event extends AbstractMapper
{

    public function getList($me, $events = null, $id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array("id","source","object","event",'event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")))
            ->join('event_user', 'event.id=event_user.event_id', array(), $select::JOIN_LEFT)
            ->join('like', 'event.id=like.event_id', array('event$nb_like' => new Expression('COUNT(like.event_id)'),'event$is_like' => new Expression('MAX(IF(like.user_id = ' . $me . ', 1, 0))')), $select::JOIN_LEFT)
            ->group('event.id')
            ->order(array('event.id' => 'DESC'));
        
        if (null !== $events) {
            $select->where(array('event.event' => $events))
                ->where(array(' (event_user.user_id = ?' => $me))
                ->where(array(' event.target =  ?)' => "global"), Predicate::OP_OR);
        }
        
        if (null !== $id) {
            $select->where(array('event.id' => $id));
        }
        
        return $this->selectWith($select);
    }
}
