<?php

namespace Application\Model;

use Application\Model\Base\Group as BaseGroup;

class Group extends BaseGroup
{
    protected $users;

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }
}
