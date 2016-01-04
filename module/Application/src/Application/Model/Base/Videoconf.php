<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Videoconf extends AbstractModel
{
    protected $id;
    protected $token;
    protected $start_date;
    protected $duration;
    protected $archive_token;
    protected $archive_link;
    protected $archive_status;
    protected $status;
    protected $conversation_id;
    protected $item_prog_id;
    protected $title;
    protected $description;
    protected $created_date;
    protected $deleted_date;

    protected $prefix = 'videoconf';

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

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getArchiveToken()
    {
        return $this->archive_token;
    }

    public function setArchiveToken($archive_token)
    {
        $this->archive_token = $archive_token;

        return $this;
    }

    public function getArchiveLink()
    {
        return $this->archive_link;
    }

    public function setArchiveLink($archive_link)
    {
        $this->archive_link = $archive_link;

        return $this;
    }

    public function getArchiveStatus()
    {
        return $this->archive_status;
    }

    public function setArchiveStatus($archive_status)
    {
        $this->archive_status = $archive_status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getConversationId()
    {
        return $this->conversation_id;
    }

    public function setConversationId($conversation_id)
    {
        $this->conversation_id = $conversation_id;

        return $this;
    }

    public function getItemProgId()
    {
        return $this->item_prog_id;
    }

    public function setItemProgId($item_prog_id)
    {
        $this->item_prog_id = $item_prog_id;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

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

    public function getDeletedDate()
    {
        return $this->deleted_date;
    }

    public function setDeletedDate($deleted_date)
    {
        $this->deleted_date = $deleted_date;

        return $this;
    }
}
