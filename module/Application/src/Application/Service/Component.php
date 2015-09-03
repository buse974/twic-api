<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Component extends AbstractService
{

    /**
     * @invokable
     */
    public function getList($dimension = null)
    {
        $m_component = $this->getModel()->setDimensionId($dimension);
        
        return $this->getMapper()->select($m_component);
    }
}
