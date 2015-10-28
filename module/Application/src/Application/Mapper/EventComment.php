<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class EventComment extends AbstractMapper
{
        /**
     * @invokable
     *
     * @param int $event
     */
     public function getList($event)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'content', 'event_comment$created_date' => new Expression("DATE_FORMAT(created_date, '%Y-%m-%dT%TZ') ")))
            ->join('user', 'event_comment.user_id = user.id', array('id', 'firstname', 'lastname', 'avatar'))
            ->join('school', 'user.school_id = school.id', array('id', 'name', 'logo'))
            ->where(array('event_comment.event_id' => $event))
            ->where(array('event_comment.deleted_date IS NULL'));
        
        return $this->selectWith($select); 
    }

}
