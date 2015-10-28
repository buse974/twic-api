<?php

namespace Application\Model;

use Application\Model\Base\EventComment as BaseEventComment;

class EventComment extends BaseEventComment
{
    protected $user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = $this->requireModel('app_model_user', $data);
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
