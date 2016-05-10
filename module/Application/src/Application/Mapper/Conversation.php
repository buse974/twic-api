<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Conversation extends AbstractMapper
{
    public function getListBySubmission($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'type', 'created_date'])
            ->join('sub_conversation', 'sub_conversation.conversation_id=conversation.id', array())
            ->where(array('sub_conversation.submission_id' => $submission_id));
        
        return $this->selectWith($select);
    }
}
