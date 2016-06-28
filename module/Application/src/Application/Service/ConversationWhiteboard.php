<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationWhiteboard extends AbstractService
{
    public function add($conversation_id, $whiteboard_id)
    {
        return $this->getMapper()->insert($this->getModel()
            ->setConversationId($conversation_id)
            ->setWhiteboardId($whiteboard_id));
    }
}