<?php

namespace Application\Model;

use Application\Model\Base\Component as BaseComponent;

class Component extends BaseComponent
{
    protected $component_scales;
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
    
    public function setComponentScales($component_scales)
    {
        $this->component_scales = $component_scales;

        return $this;
    }

    public function getComponentScales()
    {
        return $this->component_scales;
    }
}
