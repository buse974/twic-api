<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Contraite Date
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class CtDate.
 */
class CtDate extends AbstractService
{
    /**
     * Add Constraint Date.
     *
     * @invokable
     *
     * @param int    $item_id
     * @param string $date
     * @param string $after
     *
     * @return int
     */
    public function add($item_id, $date, $after = true)
    {
        $m_ct_date = $this->getModel()
            ->setItemId($item_id)
            ->setDate($date)
            ->setAfter($after);
        $this->getMapper()->insert($m_ct_date);

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Constraint Date.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $date
     * @param string $after
     *
     * @return int
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
     * Get Constraint Date.
     *
     * @param int $item_id
     */
    public function get($item_id)
    {
        return $this->getMapper()->get($item_id);
    }

    /**
     * Delete Constraint Date.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }
}
