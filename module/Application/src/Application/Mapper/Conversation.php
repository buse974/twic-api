<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Conversation extends AbstractMapper
{
    public function getListBySubmission($submission_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'type', 'created_date'])
            ->join('sub_conversation', 'sub_conversation.conversation_id=conversation.id', array())
            ->join('conversation_user', 'conversation_user.conversation_id=conversation.id', array())
            ->where(array('sub_conversation.submission_id' => $submission_id))
            ->where(array('conversation_user.user_id' => $user_id));
        
        return $this->selectWith($select);
    }
}
