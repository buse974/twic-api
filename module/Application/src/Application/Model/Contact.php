<?php

namespace Application\Model;

use Application\Model\Base\Contact as BaseContact;

class Contact extends BaseContact
{
    protected $contact;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->contact = new User($this);

        $this->contact->exchangeArray($data);
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }
}
