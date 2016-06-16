<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CtRate extends AbstractModel
{
    protected $id;
    protected $item_id;
    protected $inf;
    protected $sup;
    protected $target_id;

    protected $prefix = 'ct_rate';

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

    public function getInf()
    {
        return $this->inf;
    }

    public function setInf($inf)
    {
        $this->inf = $inf;

        return $this;
    }

    public function getSup()
    {
        return $this->sup;
    }

    public function setSup($sup)
    {
        $this->sup = $sup;

        return $this;
    }

    public function getTargetId()
    {
        return $this->target_id;
    }

    public function setTargetId($target_id)
    {
        $this->target_id = $target_id;

        return $this;
    }
}
