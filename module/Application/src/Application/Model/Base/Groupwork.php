<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Groupwork extends AbstractModel
{
    protected $id;
    protected $groupwork;
    protected $groupwork_duration;
    protected $item_id;

    protected $prefix = 'groupwork';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getGroupwork()
    {
        return $this->groupwork;
    }

    public function setGroupwork($groupwork)
    {
        $this->groupwork = $groupwork;

        return $this;
    }

    public function getGroupworkDuration()
    {
        return $this->groupwork_duration;
    }

    public function setGroupworkDuration($groupwork_duration)
    {
        $this->groupwork_duration = $groupwork_duration;

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
