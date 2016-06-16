<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CtGroup extends AbstractModel
{
    protected $id;
    protected $item_id;
    protected $group_id;
    protected $set_id;
    protected $belongs;

    protected $prefix = 'ct_group';

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

    public function getGroupId()
    {
        return $this->group_id;
    }

    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;

        return $this;
    }

    public function getSetId()
    {
        return $this->set_id;
    }

    public function setSetId($set_id)
    {
        $this->set_id = $set_id;

        return $this;
    }

    public function getBelongs()
    {
        return $this->belongs;
    }

    public function setBelongs($belongs)
    {
        $this->belongs = $belongs;

        return $this;
    }
}
