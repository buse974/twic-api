<?php

namespace Application\Model;

use Application\Model\Base\Component as BaseComponent;

class Component extends BaseComponent
{
    protected $component_scales = array();

    public function addComponentScale($component_scale) {
        $component_scales[] = $component_scale;
    }

    public function setComponentScales($component_scales) {
        $this->component_scales = $component_scales;
    }

    public function getComponentScales()
    {
        return $this->component_scales;
    }
}
