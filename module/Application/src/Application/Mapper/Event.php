<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class Event extends AbstractMapper
{
    public function getList($me, $events = null, $id = null, $source = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'source', 'object', 'event', 'event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")))
            ->join('like', 'event.id=like.event_id', array('event$is_like' => new Expression('MAX(IF(like.user_id = '.$me.' AND like.is_like IS TRUE, 1, 0))')), $select::JOIN_LEFT)
           ->group('event.id')
            ->order(array('event.id' => 'DESC'));
        if (null === $id && $source === null) {
            $select->join('user', 'user.id=user.id', [])
                 ->join('event_user', 'event.id=event_user.event_id AND event_user.user_id=user.id', array('event$read_date' => 'read_date', 'event$view_date' => 'view_date'), $select::JOIN_LEFT)
                 ->where(array('user.id' => $me))
                 ->where(array('(event_user.user_id IS NOT NULL'))
                 ->where(array('(event_user.user_id IS NULL AND event.target = "global"))'), Predicate::OP_OR);
        }
        if (null !== $events) {
            $select->where(array('event.event' => $events));
        }
        if (null !== $id) {
            $select->where(array('event.id' => $id));
        }
        if (null !== $source) {
            $select->join('event_user', 'event.id=event_user.event_id', array('event$read_date' => 'read_date'), $select::JOIN_LEFT)
                ->where(array(' ( ( event.user_id = ? ' => $source))
                ->where(array(' event.target =  ? ) ' => 'user'))
                ->where(array(' ( event_user.user_id = ?' => $source), Predicate::OP_OR)
                ->where(array(' event.user_id IS NULL ) )'));
        }

        return $this->selectWith($select);
    }

    public function nbrLike($event)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array())
            ->join('like', 'event.id=like.event_id', array('event$nb_like' => new Expression('SUM(IF(like.is_like IS TRUE, 1,0))')), $select::JOIN_LEFT)
            ->where(array('event.id' => $event));

        return $this->selectWith($select)->current()->getNbLike();
    }
}
