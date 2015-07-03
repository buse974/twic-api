<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class MailDoc extends AbstractModel
{
    protected $id;
    protected $token;
    protected $mail_id;
    protected $created_date;

    protected $prefix = 'mail_doc';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getMailId()
    {
        return $this->mail_id;
    }

    public function setMailId($mail_id)
    {
        $this->mail_id = $mail_id;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }
}
