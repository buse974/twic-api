<?php

namespace Application\Model;

use Application\Model\Base\DimensionScale as BaseDimensionScale;

class DimensionScale extends BaseDimensionScale
{
    protected $dimension;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->dimension = $this->requireModel('app_model_dimension', $data);
    }
    
    public function getDimension()
    {
        return $this->dimension;
    }
    
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
    
        return $this;
    }
}
