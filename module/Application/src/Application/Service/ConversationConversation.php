<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationConversation extends AbstractService
{
    public function add($id, $conversation_id)
    {
        return $this->getMapper()->insert($this->getModel()->setId($id)->setConversationId($conversation_id));
    }
}