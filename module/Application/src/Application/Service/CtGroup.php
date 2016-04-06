<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class CtGroup extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $item            
     * @param integer $group            
     * @param bool $belongs            
     *
     * @return integer
     */
    public function add($item, $group, $belongs = true)
    {
        $m_ct_group = $this->getModel()
            ->setItemId($item)
            ->setGroupId($group)
            ->setBelongs($belongs);
        $this->getMapper()->insert($m_ct_group);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param string $group            
     * @param bool $belongs            
     *
     * @return integer
     */
    public function update($id, $group = null, $belongs = null)
    {
        $m_ct_group = $this->getModel()
            ->setId($id)
            ->setGroupId($group)
            ->setBelongs($belongs);
        
        return $this->getMapper()->update($m_ct_group);
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