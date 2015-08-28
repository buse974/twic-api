<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Dimension extends AbstractService
{
    public function getList()
    {
        $res_dimension = $this->getMapper()->fetchAll();
        
        foreach ($res_dimension as $m_dimension) {
            $m_dimension->setComponent($this->getServiceComponent()->getList($m_dimension->getId()));
        }
        
        return $res_dimension;
    }
    
    /**
     * @return \Application\Service\Component
     */
    public function getServiceComponent()
    {
        return $this->getServiceLocator()->get('app_service_component');
    }
}