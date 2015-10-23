<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Component extends AbstractService
{
    /**
     * @invokable
     * 
     * @param int    $dimension
     * @param array  $filter
     * @param string $search
     */
    public function getList($dimension = null, $filter = null, $search = null)
    {
        $mapper = $this->getMapper();

        $res_component = $mapper->usePaginator($filter)->getList($dimension, $search);

        return (null !== $filter) ? 
            array('count' => $mapper->count(),'list' => $res_component) :
            $res_component;
    }

    /**
     * @invokable
     */
    public function getListWithScale()
    {
        $components = $this->getMapper()->fetchAll();

        foreach ($components as $component) {
            $component->setComponentScales($this->getServiceComponentScale()
                ->getList($component->getId()));
        }

        return $components;
    }

    /**
     * @invokable
     *
     * @param string $name
     * @param string $dimension
     * @param string $describe
     */
    public function add($name, $dimension, $describe)
    {
        $m_component = $this->getModel()
            ->setName($name)
            ->setDimensionId($dimension)
            ->setDescribe($describe);

        if ($this->getMapper()->insert($m_component) <= 0) {
            throw new \Exception('error insert component');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     * 
     * @param integer $school
     * @param string $gender
     * @param string $nationality
     * @param string $origin
     * @param string $program
     */
    public function getEqCq($school, $gender = null, $nationality = null, $origin = null, $program = null)
    {
        return $this->getMapper()->getEqCq($school, $gender, $nationality, $origin, $program);
    }
    
    /**
     * @invokable
     *
     * @param int    $id
     * @param string $name
     * @param string $dimension
     * @param string $describe
     */
    public function update($id, $name, $dimension, $describe)
    {
        $m_component = $this->getModel()
            ->setId($id)
            ->setName($name)
            ->setDimensionId($dimension)
            ->setDescribe($describe);

        return $this->getMapper()->update($m_component);
    }

    /**
     * @invokable
     *
     * @param int $id
     */
    public function delete($id)
    {
        $m_component = $this->getModel()
            ->setId($id)
            ->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_component);
    }

    /**
     * @return \Application\Service\ComponentScale
     */
    public function getServiceComponentScale()
    {
        return $this->getServiceLocator()->get('app_service_component_scale');
    }
}
