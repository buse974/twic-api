<?php

namespace Application\Model;

use Application\Model\Base\User as BaseUser;

class User extends BaseUser
{
    protected $school;
    protected $roles;
    protected $available;
    protected $selected;
    protected $has_contact;
    protected $has_request;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);

        $this->school = new School($this);
        $this->school->exchangeArray($data);
    }

    public function getHasRequest()
    {
        return $this->has_request;
    }
    
    public function setHasRequest($has_request)
    {
        $this->has_request = $has_request;
    
        return $this;
    }
    
    public function getHasContact()
    {
        return $this->has_contact;
    }
    
    public function setHasContact($has_contact)
    {
        $this->has_contact = $has_contact;
    
        return $this;
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

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    public function getSelected()
    {
        return $this->selected;
    }
}
