<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemGradingComment extends AbstractModel
{
    protected $id;
    protected $item_grading_id;
    protected $comment;
    protected $created_date;

    protected $prefix = 'item_grading_comment';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getItemGradingId()
    {
        return $this->item_grading_id;
    }

    public function setItemGradingId($item_grading_id)
    {
        $this->item_grading_id = $item_grading_id;

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
