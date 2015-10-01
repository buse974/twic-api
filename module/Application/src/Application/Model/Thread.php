<?php

namespace Application\Model;

use Application\Model\Base\Thread as BaseThread;

class Thread extends BaseThread
{
    protected $user;
    protected $nb_message;
    protected $message;
    protected $course;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = $this->requireModel('app_model_user', $data);
        $this->course = $this->requireModel('app_model_course', $data);
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course)
    {
        $this->course = $course;

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

    public function getNbMessage()
    {
        return $this->nb_message;
    }

    public function setNbMessage($nb_message)
    {
        $this->nb_message = $nb_message;

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
