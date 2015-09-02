<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ThreadMessage extends AbstractMapper
{

    public function getList($thread)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','message','created_date'))
            ->join('user', 'user.id=thread_message.user_id', array('id','firstname','lastname', 'avatar'))
            ->join('thread', 'thread.id=thread_message.thread_id', array())
            ->where(array('thread_message.thread_id' => $thread))
            ->where(array('thread.deleted_date IS NULL'))
            ->where(array('thread_message.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }

    public function getLast($thread)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','message','created_date'))
            ->join('user', 'user.id=thread_message.user_id', array('id','firstname','lastname', 'avatar'))
            ->join('thread', 'thread.id=thread_message.thread_id', array())
            ->where(array('thread_message.thread_id' => $thread))
            ->where(array('thread.deleted_date IS NULL'))
            ->where(array('thread_message.deleted_date IS NULL'))
            ->order('thread_message.id DESC')
            ->limit(1);
        
        return $this->selectWith($select)->current();
    }
}
