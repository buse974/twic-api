<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Mail extends AbstractModel
{
    protected $id;
    protected $mail_group_id;
    protected $suject;
    protected $content;
    protected $created_date;
    protected $draft;

    protected $prefix = 'mail';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getMailGroupId()
    {
        return $this->mail_group_id;
    }

    public function setMailGroupId($mail_group_id)
    {
        $this->mail_group_id = $mail_group_id;

        return $this;
    }

    public function getSuject()
    {
        return $this->suject;
    }

    public function setSuject($suject)
    {
        $this->suject = $suject;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

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

    public function getDraft()
    {
        return $this->draft;
    }

    public function setDraft($draft)
    {
        $this->draft = $draft;

        return $this;
    }
}
