<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationDoc extends AbstractService
{
    public function add($conversation_id, $library_id) 
    {
        return $this->getMapper()->insert($this->getModel()
            ->setConversationId($conversation_id)
            ->setLibraryId($library_id));
    }
}