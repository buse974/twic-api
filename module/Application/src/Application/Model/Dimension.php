<?php

namespace Application\Model;

use Application\Model\Base\Dimension as BaseDimension;

class Dimension extends BaseDimension
{
    protected $component;

    public function exchangeArray(array &$data)
    {
        $this->component = $this->requireModel('app_model_component', $data);

        parent::exchangeArray($data);
    }

    public function getComponent()
    {
        return $this->component;
    }

    public function setComponent($component)
    {
        $this->component = $component;

        return $this;
    }
}
