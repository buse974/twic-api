<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class WorkshopDocument extends AbstractModel
{
    protected $id;
    protected $title;
    protected $link;
    protected $video;
    protected $doc;
    protected $workshop_id;

    protected $prefix = 'workshop_document';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    public function getDoc()
    {
        return $this->doc;
    }

    public function setDoc($doc)
    {
        $this->doc = $doc;

        return $this;
    }

    public function getWorkshopId()
    {
        return $this->workshop_id;
    }

    public function setWorkshopId($workshop_id)
    {
        $this->workshop_id = $workshop_id;

        return $this;
    }
}
