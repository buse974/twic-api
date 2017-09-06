<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ConversationDoc extends AbstractModel
{
    protected $conversation_id;
    protected $library_id;

    protected $prefix = 'conversation_doc';

    public function getConversationId()
    {
        return $this->conversation_id;
    }

    public function setConversationId($conversation_id)
    {
        $this->conversation_id = $conversation_id;

        return $this;
    }

    public function getLibraryId()
    {
        return $this->library_id;
    }

    public function setLibraryId($library_id)
    {
        $this->library_id = $library_id;

        return $this;
    }
}
