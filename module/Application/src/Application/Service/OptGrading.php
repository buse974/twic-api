<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class OptGrading extends AbstractService
{
    /**
     * @invokable
     *
     * @param integer $item_id
     * @param string $mode
     * @param bool $has_pg
     * @param integer $pg_nb
     * @param bool $pg_auto
     * @param string $pg_due_date
     * @param bool $pg_can_view
     * @param bool $user_can_view
     * @param bool $pg_stars
     *
     * @return integer
     */
    public function add($item_id, $mode = null, $has_pg = null, $pg_nb = null, $pg_auto = null, $pg_due_date = null, $pg_can_view = null, $user_can_view = null, $pg_stars = null)
    {
        $m_opt_grading = $this->getModel()
        ->setItemId($item_id)
        ->setMode($mode)
        ->setHasPg($has_pg)
        ->setPgNb($pg_nb)
        ->setPgAuto($pg_auto)
        ->setPgDueDate($pg_due_date)
        ->setPgCanView($pg_can_view)
        ->setUserCanView($user_can_view)
        ->setPgStars($pg_stars);
    
        return $this->getMapper()->insert($m_opt_grading);
    }
    
    /**
     * @invokable
     *
     * @param integer $item_id
     * @param string $mode
     * @param bool $has_pg
     * @param integer $pg_nb
     * @param bool $pg_auto
     * @param string $pg_due_date
     * @param bool $pg_can_view
     * @param bool $user_can_view
     * @param bool $pg_stars
     *
     * @return integer
     */
    public function update($item_id, $mode = null, $has_pg = null, $pg_nb = null, $pg_auto = null, $pg_due_date = null, $pg_can_view = null, $user_can_view = null, $pg_stars = null)
    {
        $m_opt_grading = $this->getModel()
        ->setItemId($item_id)
        ->setMode($mode)
        ->setHasPg($has_pg)
        ->setPgNb($pg_nb)
        ->setPgAuto($pg_auto)
        ->setPgDueDate($pg_due_date)
        ->setPgCanView($pg_can_view)
        ->setUserCanView($user_can_view)
        ->setPgStars($pg_stars);
    
        return $this->getMapper()->update($m_opt_grading);
    }
    
    /**
     * @invokable
     *
     * @param integer $item_id
     *
     * @return boolean
     */
    public function delete($item_id)
    {
        $m_opt_grading = $this->getModel()->setItemId($item_id);
    
        return $this->getMapper()->delete($m_opt_grading);
    }
}