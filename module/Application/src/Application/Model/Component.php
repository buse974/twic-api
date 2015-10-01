<?php

namespace Application\Model;

use Application\Model\Base\Component as BaseComponent;

class Component extends BaseComponent
{
    protected $component_scales;

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
