<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class TextEditor extends AbstractMapper
{
    public function getListByConversation($conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'text', 'submission_id', 'submit_date'])
            ->join('conversation_text_editor', 'conversation_text_editor.text_editor_id=text_editor.id', [])
            ->where(array('conversation_text_editor.conversation_id' =>  $conversation_id));
            
        return $this->selectWith($select);
    }
}
