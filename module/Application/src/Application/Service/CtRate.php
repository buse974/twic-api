<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class CtRate extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $item            
     * @param integer $target            
     * @param string $inf            
     * @param string $sup            
     *
     * @return integer
     */
    public function add($item, $target, $inf = null, $sup = null)
    {
        $m_ct_rate = $this->getModel()
            ->setItemId($item)
            ->setTargetId($target)
            ->setInf($inf)
            ->setSup($sup);
        $this->getMapper()->insert($m_ct_rate);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id    
     * @param integer $target
     * @param string $inf
     * @param string $sup
     * @return integer
     */
    public function update($id, $target = null, $inf = null, $sup = null)
    {
        $m_ct_rate = $this->getModel()
            ->setId($id)
            ->setTargetId($target)
            ->setInf($inf)
            ->setSup($sup);
        
        return $this->getMapper()->update($m_ct_rate);
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @return integer
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }
}