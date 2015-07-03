<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Conversation extends AbstractModel
{
    protected $id;
    protected $created_date;

    protected $prefix = 'conversation';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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
