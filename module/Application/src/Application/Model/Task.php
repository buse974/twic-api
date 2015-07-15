<?php

namespace Application\Model;

use Application\Model\Base\Task as BaseTask;

class Task extends BaseTask
{
    protected $editable;
    protected $user;
    protected $task_share;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = new User($this);
        $this->user->exchangeArray($data);
    }

    public function getEditable()
    {
        return $this->editable;
    }

    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getTaskShare()
    {
        return $this->task_share;
    }

    public function setTaskShare($task_share)
    {
        $this->task_share = $task_share;

        return $this;
    }
}
