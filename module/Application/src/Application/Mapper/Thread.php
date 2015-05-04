<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Thread extends AbstractMapper
{
    public function getList($course)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'created_date', 'deleted_date'))
               ->join('user', 'user.id=thread.user_id', array('id', 'firstname', 'lastname'))
               ->where(array('thread.course_id' => $course))
               ->where(array('thread.deleted_date IS NULL'));

        return $this->selectWith($select);
    }
}
