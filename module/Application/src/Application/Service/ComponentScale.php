<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class ComponentScale extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $component            
     * @param integer $min            
     * @param integer $max            
     * @param string $describe            
     * @param string $recommandation            
     */
    public function add($component, $min, $max, $describe, $recommandation)
    {
        if ($this->getMapper()->insert($this->getModel()
            ->setComponentId($component)
            ->setMin($min)
            ->setMax($max)
            ->setDescribe($describe)
            ->setRecommandation($recommandation)) <= 0) {
            throw new \Exception('error insert component scale');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id            
     *
     * @return integer
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param integer $component            
     * @param integer $min            
     * @param integer $max            
     * @param string $describe            
     * @param string $recommandation            
     *
     * @return integer
     */
    public function update($id, $component, $min, $max, $describe, $recommandation)
    {
        return $this->getMapper()->update($this->getModel()
            ->setId($id)
            ->setComponentId($component)
            ->setMin($min)
            ->setMax($max)
            ->setDescribe($describe)
            ->setRecommandation($recommandation));
    }

    /**
     * @invokable
     *
     * @param integer $component_id            
     * @param array $filter            
     */
    public function getList($component_id = null, $filter = null)
    {
        $mapper = $this->getMapper();
        $res_component_scale = ($component_id !== null) ? $mapper->select($this->getModel()
            ->setComponentId($component_id)) : $mapper->usePaginator($filter)->fetchAll();
        
        return ($filter !== null) ? ['count' => $mapper->count(),'list' => $res_component_scale] : $res_component_scale;
    }
}
