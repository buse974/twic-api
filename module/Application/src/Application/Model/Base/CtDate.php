<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CtDate extends AbstractModel
{
    protected $id;
    protected $item_id;
    protected $date;
    protected $after;

    protected $prefix = 'ct_date';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function setAfter($after)
    {
        $this->after = $after;

        return $this;
    }
}
