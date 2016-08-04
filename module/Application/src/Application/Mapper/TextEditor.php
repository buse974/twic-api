<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class TextEditor extends AbstractMapper
{
    public function getListByConversation($conversation_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'text', 'submit_date'])
            ->join('conversation_text_editor', 'conversation_text_editor.text_editor_id=text_editor.id', [])
            ->where(array('conversation_text_editor.conversation_id' => $conversation_id));

        return $this->selectWith($select);
    }

    public function getListBySubmission($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'text', 'submit_date'])
            ->join('sub_text_editor', 'sub_text_editor.text_editor_id=text_editor.id', [])
            ->where(array('sub_text_editor.submission_id' => $submission_id));

        return $this->selectWith($select);
    }
}
