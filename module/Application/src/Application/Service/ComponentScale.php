<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ComponentScale extends AbstractService
{
    public function add($component_id, $min, $max, $describe, $recommandation)
    {
    
    }
    
    public function deltete($id)
    {
    
    }
    
    public function update($id, $component_id, $min, $max, $describe, $recommandation)
    {
    
    }
    
    public function getList($component_id)
    {
        $m_component_scale = $this->getModel()->setComponentId($component_id);

        $result = $this->getMapper()->select($m_component_scale);
        if (!$result->count()) {
            return [];
        }
        return $result;
    }
}
