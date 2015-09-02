<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Thread extends AbstractMapper
{
    public function getList($course)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'created_date', 'deleted_date'))
               ->join('user', 'user.id=thread.user_id', array('id', 'firstname', 'lastname', 'avatar', 'thread$nb_message' => new Expression('SUM(IF(thread_message.id IS NULL, 0,1))')))
               ->join('thread_message', 'thread_message.thread_id=thread.id', array(), $select::JOIN_LEFT)
               ->where(array('thread.course_id' => $course))
               ->where(array('thread.deleted_date IS NULL'))
               ->group('thread.id');

        return $this->selectWith($select);
    }
}
