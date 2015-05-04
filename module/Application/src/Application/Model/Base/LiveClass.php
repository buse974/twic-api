<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class LiveClass extends AbstractModel
{
    protected $id;
    protected $lecture;
    protected $lecture_duration;
    protected $item_id;

    protected $prefix = 'live_class';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getLecture()
    {
        return $this->lecture;
    }

    public function setLecture($lecture)
    {
        $this->lecture = $lecture;

        return $this;
    }

    public function getLectureDuration()
    {
        return $this->lecture_duration;
    }

    public function setLectureDuration($lecture_duration)
    {
        $this->lecture_duration = $lecture_duration;

        return $this;
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }
}
