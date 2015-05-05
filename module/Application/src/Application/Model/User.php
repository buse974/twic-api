<?php

namespace Application\Model;

use Application\Model\Base\User as BaseUser;

class User extends BaseUser
{
    protected $school;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);

        $this->school = new School($this);
        $this->school->exchangeArray($data);
    }

    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    public function getSchool()
    {
        return $this->school;
    }
}
