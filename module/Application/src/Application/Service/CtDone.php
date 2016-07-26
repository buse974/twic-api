<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Contrainte Done
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class CtDone
 */
class CtDone extends AbstractService
{

    /**
     * Add Constraint Done
     *
     * @invokable
     *
     * @param int $item_id            
     * @param int $target_id            
     * @param bool $all            
     * @return int
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
     * Update Constraint Done
     *
     * @invokable
     *
     * @param int $id            
     * @param string $target_id            
     * @param string $all            
     * @return int
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
     * Delete Constraint Done
     *
     * @invokable
     *
     * @param int $id            
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * Get Constraint Done
     *
     * @param int $item_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($item_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setItemId($item_id));
    }
}
