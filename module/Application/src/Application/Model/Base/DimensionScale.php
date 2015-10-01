<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class DimensionScale extends AbstractModel
{
    protected $id;
    protected $dimension_id;
    protected $min;
    protected $max;
    protected $describe;

    protected $prefix = 'dimension_scale';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getMin()
    {
        return $this->min;
    }

    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function setMax($max)
    {
        $this->max = $max;

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
}
