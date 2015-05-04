<?php

namespace Application\Model;

use Application\Model\Base\Thread as BaseThread;

class Thread extends BaseThread
{
    protected $user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = new User($this);
        $this->user->exchangeArray($data);
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
