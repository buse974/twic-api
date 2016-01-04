<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemProg extends AbstractModel
{
    protected $id;
    protected $item_id;
    protected $start_date;
    protected $due_date;

    protected $prefix = 'item_prog';

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

    public function getStartDate()
    {
        return $this->start_date;
    }

    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getDueDate()
    {
        return $this->due_date;
    }

    public function setDueDate($due_date)
    {
        $this->due_date = $due_date;

        return $this;
    }
}
