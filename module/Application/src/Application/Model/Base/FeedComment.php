<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class FeedComment extends AbstractModel
{
    protected $id;
    protected $content;
    protected $user_id;
    protected $feed_id;
    protected $created_date;
    protected $deleted_date;

    protected $prefix = 'feed_comment';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFeedId()
    {
        return $this->feed_id;
    }

    public function setFeedId($feed_id)
    {
        $this->feed_id = $feed_id;

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
