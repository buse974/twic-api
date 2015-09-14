<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Component extends AbstractService
{

    public function getList($dimension = null)
    {
        $m_component = $this->getModel()->setDimensionId($dimension);
        
        return $this->getMapper()->select($m_component);
    }

    /**
     * @invokable
     */
    public function getListWithScale()
    {
        $components = $this->getMapper()->fetchAll();

        foreach ($components as $component) {
        $component->setComponentScales($this->getServiceComponentScale()->getList($component->getId()));
        }

        return $components;
    }

    public function getServiceComponentScale()
    {
        return $this->getServiceLocator()->get('app_service_component_scale');
    }
}
