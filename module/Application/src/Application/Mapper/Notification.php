<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class Notification extends AbstractMapper
{
    public function getList($user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','source','event','object','notification$date' => new Expression('DATE_FORMAT(notification.date, "%Y-%m-%dT%TZ")')))
            ->join('notification_user', 'notification_user.notification_id=notification.id', array(), $select::JOIN_LEFT)
            ->where(array(' ( notification_user.user_id = ? ' => $user))
            ->where(array('notification.target = ? )' => 'global'), Predicate::OP_OR);
        
        return $this->selectWith($select);
    }
}