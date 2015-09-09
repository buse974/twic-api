<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ThreadMessage extends AbstractMapper
{

    public function getList($thread = null, $thread_message = null)
    {
        if(null === $thread && null === $thread_message) {
            throw new \Exception('error params');
        }
        
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','message','created_date'))
            ->join(array('thread_message_user' => 'user'), 'thread_message_user.id=thread_message.user_id', array('id','firstname','lastname', 'avatar'))
            ->join('thread', 'thread.id=thread_message.thread_id', array('id','course_id'))
            ->where(array('thread.deleted_date IS NULL'))
            ->where(array('thread_message.deleted_date IS NULL'));
        
        if(null !== $thread) {
            $select->where(array('thread_message.thread_id' => $thread));
        }
        
        if(null !== $thread_message) {
            $select->where(array('thread_message.id' => $thread_message));
        }
        
        return $this->selectWith($select);
    }

    public function getLast($thread)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','message','created_date'))
            ->join(array('thread_message_user' => 'user'), 'thread_message_user.id=thread_message.user_id', array('id','firstname','lastname', 'avatar'))
            ->join('thread', 'thread.id=thread_message.thread_id', array())
            ->where(array('thread_message.thread_id' => $thread))
            ->where(array('thread.deleted_date IS NULL'))
            ->where(array('thread_message.deleted_date IS NULL'))
            ->order('thread_message.id DESC')
            ->limit(1);
        
        return $this->selectWith($select)->current();
    }
}
