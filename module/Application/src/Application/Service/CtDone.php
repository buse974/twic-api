<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class CtDone extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $item_id            
     * @param integer $target_id            
     * @param bool $all            
     *
     * @return integer
     */
    public function add($item_id, $target_id, $all = true)
    {
        $m_ct_done = $this->getModel()
            ->setItemId($item_id)
            ->setTargetId($target_id)
            ->setAll($all);
        $this->getMapper()->insert($m_ct_done);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param string $target            
     * @param string $all            
     *
     * @return integer
     */
    public function update($id, $target_id = null, $all = null)
    {
        $m_ct_done = $this->getModel()
            ->setId($id)
            ->setTargetId($target_id)
            ->setAll($all);
        
        return $this->getMapper()->update($m_ct_done);
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
     * @param integer $item_id
     */
    public function get($item_id)
    {
        return $this->getMapper()->select($this->getModel()->setItemId($item_id));
    }
}