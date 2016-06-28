<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ConversationConversation extends AbstractMapper
{
    public function getList($conversation_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'conversation_id'))
            ->join('conversation_user', 'conversation_user.conversation_id=conversation_conversation.conversation_id', [])
            ->where(['conversation_conversation.id' => $conversation_id])
            ->where(['conversation_user.user_id' => $user_id])
            ->quantifier('DISTINCT');
        
        return $this->selectWith($select);
    }
}