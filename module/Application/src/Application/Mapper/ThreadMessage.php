<?php

namespace Application\Mapper;

use Zend\Db\Sql\Predicate\Expression;
use Dal\Mapper\AbstractMapper;

class ThreadMessage extends AbstractMapper
{
    public function getList($thread = null, $thread_message = null, $parent_id = null)
    {
        if (null === $thread && null === $thread_message) {
            throw new \Exception('error params');
        }

        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id', 'message', 'parent_id', 
            'thread_message$created_date' => new Expression('DATE_FORMAT(thread_message.created_date, "%Y-%m-%dT%TZ")')))
            ->join(array('thread_message_user' => 'user'), 'thread_message_user.id=thread_message.user_id', array('id', 'firstname', 'lastname', 'avatar'))
            ->join('thread', 'thread.id=thread_message.thread_id', array('id', 'course_id'))
            ->join(array('thread_message_parent' => 'thread_message'), 'thread_message_parent.id=thread_message.parent_id', array('id', 'thread_message_parent$deleted_date' => new Expression('DATE_FORMAT(thread_message_parent.deleted_date, "%Y-%m-%dT%TZ")')), $select::JOIN_LEFT)
            ->join(array('thread_message_parent_user' => 'user'), 'thread_message_parent_user.id=thread_message_parent.user_id', array('id', 'firstname', 'lastname', 'avatar'),  $select::JOIN_LEFT)                
            ->where(array('thread.deleted_date IS NULL'))
            ->where(array('thread_message.deleted_date IS NULL'))                
            ->order(array('thread_message.created_date DESC'));

        if (null !== $thread) {
            $select->where(array('thread_message.thread_id' => $thread));
        }

        if (null !== $thread_message) {
            $select->where(array('thread_message.id' => $thread_message));
        }
        
        if (null !== $parent_id) {
            $select->where(array('thread_message.parent_id' => $parent_id));
        }

        return $this->selectWith($select);
    }

    public function getLast($thread)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'message', 'thread_message$created_date' => new Expression('DATE_FORMAT(thread_message.created_date, "%Y-%m-%dT%TZ")')))
            ->join(array('thread_message_user' => 'user'), 'thread_message_user.id=thread_message.user_id', array('id', 'firstname', 'lastname', 'avatar'))
            ->join('thread', 'thread.id=thread_message.thread_id', array())
            ->where(array('thread_message.thread_id' => $thread))
            ->where(array('thread.deleted_date IS NULL'))
            ->where(array('thread_message.deleted_date IS NULL'))
            ->order('thread_message.id DESC')
            ->limit(1);

        return $this->selectWith($select)->current();
    }
}
