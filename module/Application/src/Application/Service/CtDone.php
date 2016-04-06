<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class CtDone extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $item            
     * @param integer $target            
     * @param bool $all            
     *
     * @return integer
     */
    public function add($item, $target, $all = true)
    {
        $m_ct_done = $this->getModel()
            ->setItemId($item)
            ->setTargetId($target)
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
    public function update($id, $target = null, $all = null)
    {
        $m_ct_done = $this->getModel()
            ->setId($id)
            ->setTargetId($target)
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