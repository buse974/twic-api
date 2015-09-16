<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Component extends AbstractService
{

    public function getList($dimension = null)
    {
        return ((is_numeric($dimension)) ?
        $this->getMapper()->select($this->getModel()->setDimensionId($dimension)->setComponentScales(null)) :
        $this->getMapper()->fetchAll());
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

    /**
     * @return \Application\Service\ComponentScale
     */
    public function getServiceComponentScale()
    {
        return $this->getServiceLocator()->get('app_service_component_scale');
    }
}
