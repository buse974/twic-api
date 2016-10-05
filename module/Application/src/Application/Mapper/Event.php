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
            ->group('event.id')
            ->order(['event.id' => 'DESC']);
        if (null === $id && $source === null) {
            $select->join('event_subscription', 'event_subscription.event_id=event.id', [])
                ->join('subscription', 'subscription.libelle=event_subscription.libelle', [])->where(['subscription.user_id' => $me]);
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
