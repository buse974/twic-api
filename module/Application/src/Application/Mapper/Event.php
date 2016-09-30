<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class Event extends AbstractMapper
{

    public function getList($me, $events = null, $id = null, $source = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id','source','object','event','event$date' => new Expression("DATE_FORMAT(date, '%Y-%m-%dT%TZ') ")])
            ->join('like', 'event.id=like.event_id', ['event$is_like' => new Expression('MAX(IF(like.user_id = ' . $me . ' AND like.is_like IS TRUE, 1, 0))')], $select::JOIN_LEFT)
            ->group('event.id')
            ->order(['event.id' => 'DESC']);
        if (null === $id && $source === null) {
            $select->join('subscription_user', 'subscription_user.libelle=event.libelle', [])->where(['subscription_user.user_id' => $me]);
        }
        if (null !== $events) {
            $select->where(['event.event' => $events]);
        }
        if (null !== $id) {
            $select->where(['event.id' => $id]);
        }
        if (null !== $source) {
            $select->where(['event.user_id' => $source]);
        }
        
        return $this->selectWith($select);
    }

}
