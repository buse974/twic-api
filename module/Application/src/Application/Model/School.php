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

        $this->address = $this->requireModel('addr_model_address', $data);
        $this->contact_user = $this->requireModel('app_model_user', $data);
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
