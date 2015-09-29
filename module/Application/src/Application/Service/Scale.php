<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Scale extends AbstractService
{
    /**
     * @invokable
     *
     * @param string $name            
     * @param string $value            
     * @throws \Exception
     *
     * @return integer
     */
    public function add($name, $value)
    {
        if ($this->getMapper()->insert($this->getModel()
            ->setName($name)
            ->setValue($value)) <= 0) {
            throw new \Exception('error insert scale');
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
    public function deltete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->steId($id));
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param string $name            
     * @param string $value            
     *
     * @return integer
     */
    public function update($id, $name, $value)
    {
        return $this->getMapper()->update($this->getModel()
            ->steId($id)
            ->setName($name)
            ->setValue($value));
    }

    /**
     * @invokable
     *
     * @param array|null $filter            
     */
    public function getList($filter = null)
    {
        $mapper = $this->getMapper();
        $res_scale = $mapper->usePaginator($filter)->fetchAll();
        
        return ($filter !== null) ? ['count' => $mapper->count(),'list' => $res_scale] : $res_scale;
    }
}