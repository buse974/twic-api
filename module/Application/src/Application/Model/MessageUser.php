<?php

namespace Application\Model;

use Application\Model\Base\MessageUser as BaseMessageUser;

class MessageUser extends BaseMessageUser
{
    protected $message;
    protected $user;
    protected $count;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->message = $this->requireModel('app_model_message', $data);
        $this->user = $this->requireModel('app_model_user', $data, 'from');
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
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
