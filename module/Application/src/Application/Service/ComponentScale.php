<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ComponentScale extends AbstractService
{
    public function getList($component_id)
    {
        $m_component_scale = $this->getModel()->setComponentId($component_id);

        return $this->getMapper()->select($m_component_scale);
    }
}
