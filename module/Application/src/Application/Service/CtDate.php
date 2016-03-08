<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class CtDate extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $item            
     * @param string $date            
     * @param string $after            
     *
     * @return integer
     */
    public function add($item, $date, $after = true)
    {
        $m_ct_date = $this->getModel()
            ->setItemId($item)
            ->setDate($date)
            ->setAfter($after);
        $this->getMapper()->insert($m_ct_date);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param string $date            
     * @param string $after            
     *
     * @return integer
     */
    public function update($id, $date = null, $after = null)
    {
        $m_ct_date = $this->getModel()
            ->setId($id)
            ->setDate($date)
            ->setAfter($after);
        
        return $this->getMapper()->update($m_ct_date);
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
}