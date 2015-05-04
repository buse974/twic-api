<?php

namespace Application\Model;

use Application\Model\Base\School as BaseSchool;
use Address\Model\Address;

class School extends BaseSchool
{
    protected $address;
    protected $contact_user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->address = new Address($this);
        $this->contact_user = new User($this);

        $this->contact_user->exchangeArray($data);
        $this->address->exchangeArray($data);
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setContactUser($contact_user)
    {
        $this->contact_user = $contact_user;

        return $this;
    }

    public function getContactUser()
    {
        return $this->contact_user;
    }
}
