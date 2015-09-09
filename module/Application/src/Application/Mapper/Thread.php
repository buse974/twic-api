<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;

class Thread extends AbstractMapper
{
    public function getList($course = null, $thread = null)
    {
        if(null === $course && null === $thread) {
            throw new \Exception('no params');
        }
        
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'created_date', 'deleted_date'))
               ->join(array('thread_user' =>  'user'), 'thread_user.id=thread.user_id', array('id', 'firstname', 'lastname', 'avatar', 'thread$nb_message' => new Expression('SUM(IF(thread_message.id IS NULL OR thread_message.deleted_date IS NOT NULL, 0,1))')))
               ->join('thread_message', 'thread_message.thread_id=thread.id', array(), $select::JOIN_LEFT)
               ->join('course', 'thread.course_id=course.id', array('id', 'title'), $select::JOIN_LEFT)
               ->where(array('thread.deleted_date IS NULL'))
               ->group('thread.id');

        if(null !== $course) {
            $select->where(array('thread.course_id' => $course));
        }
        
        if(null !== $thread) {
            $select->where(array('thread.id' => $thread));
        }
        
        return $this->selectWith($select);
    }
}
