<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ConversationUser extends AbstractModel
{
    protected $conversation_id;
    protected $user_id;

    protected $prefix = 'conversation_user';

    public function getConversationId()
    {
        return $this->conversation_id;
    }

    public function setConversationId($conversation_id)
    {
        $this->conversation_id = $conversation_id;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
