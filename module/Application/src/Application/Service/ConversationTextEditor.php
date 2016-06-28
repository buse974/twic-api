<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationTextEditor extends AbstractService
{
    public function add($conversation_id, $text_editor_id) 
    {
        return $this->getMapper()->insert($this->getModel()
            ->setConversationId($conversation_id)
            ->setTextEditorId($text_editor_id));
    }
}   