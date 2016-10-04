<?php

namespace Application\Model;

use Application\Model\Base\Event as BaseEvent;

class Event extends BaseEvent
{
    protected $nb_like;

    protected $is_like;

    protected $comment;

    protected $read_date;

    protected $view_date;

    public function getViewDate()
    {
        return $this->view_date;
    }

    public function setViewDate($view_date)
    {
        $this->view_date = $view_date;

        return $this;
    }
    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIsLike()
    {
        return $this->is_like;
    }

    public function setIsLike($is_like)
    {
        $this->is_like = $is_like;

        return $this;
    }

    public function getReadDate()
    {
        return $this->read_date;
    }

    public function setReadDate($read_date)
    {
        $this->read_date = $read_date;

        return $this;
    }
}
