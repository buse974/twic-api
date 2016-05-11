<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\Conversation as ModelConversation;
use Zend\Db\Sql\Predicate\Predicate;

class Conversation extends AbstractMapper
{
    public function getListBySubmission($submission_id, $user_id, $with_default = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'type', 'created_date'])
            ->join('sub_conversation', 'sub_conversation.conversation_id=conversation.id', array())
            ->join('conversation_user', 'conversation_user.conversation_id=conversation.id', array())
            ->where(array('sub_conversation.submission_id = ? ' => $submission_id))
            ->quantifier('DISTINCT');

            if($with_default===true) {
                $select->where(array(' ( conversation_user.user_id = ? ' => $user_id));
                $select->where(array('conversation.name =  ? )' => ModelConversation::DEFAULT_NAME), Predicate::OP_OR);
            } else {
                $select->where(array('conversation_user.user_id' => $user_id));
            }
            
        return $this->selectWith($select);
    }
}
