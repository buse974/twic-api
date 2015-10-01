<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Component extends AbstractModel
{
    protected $id;
    protected $name;
    protected $dimension_id;
    protected $describe;
    protected $deleted_date;

    protected $prefix = 'component';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getDimensionId()
    {
        return $this->dimension_id;
    }

    public function setDimensionId($dimension_id)
    {
        $this->dimension_id = $dimension_id;

        return $this;
    }

    public function getDescribe()
    {
        return $this->describe;
    }

    public function setDescribe($describe)
    {
        $this->describe = $describe;

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
