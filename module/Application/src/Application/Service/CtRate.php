<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class CtRate extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $item_id            
     * @param integer $target_id           
     * @param string $inf            
     * @param string $sup            
     *
     * @return integer
     */
    public function add($item_id, $target_id, $inf = null, $sup = null)
    {
        $m_ct_rate = $this->getModel()
            ->setItemId($item_id)
            ->setTargetId($target_id)
            ->setInf($inf)
            ->setSup($sup);
        $this->getMapper()->insert($m_ct_rate);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id    
     * @param integer $target_id
     * @param string $inf
     * @param string $sup
     * @return integer
     */
    public function update($id, $target_id = null, $inf = null, $sup = null)
    {
        $m_ct_rate = $this->getModel()
            ->setId($id)
            ->setTargetId($target_id)
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
    
    /**
     * @param integer $item_id
     */
    public function get($item_id)
    {
        return $this->getMapper()->select($this->getModel()->setItemId($item_id));
    }
}