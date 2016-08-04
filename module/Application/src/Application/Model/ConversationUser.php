<?php

namespace Application\Model;

use Application\Model\Base\ConversationUser as BaseConversationUser;

class ConversationUser extends BaseConversationUser
{
    protected $user;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
