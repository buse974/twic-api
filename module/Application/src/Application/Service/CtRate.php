<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Constrainte Rate
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class CtRate.
 */
class CtRate extends AbstractService
{
    /**
     * Add Constraint Rate.
     *
     * @invokable
     *
     * @param int    $item_id
     * @param int    $target_id
     * @param string $inf
     * @param string $sup
     *
     * @return int
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
     * Update Constraint Rate.
     *
     * @invokable
     *
     * @param int    $id
     * @param int    $target_id
     * @param string $inf
     * @param string $sup
     *
     * @return int
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
     * Delete Constraint Rate.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->getMapper()->delete(
            $this->getModel()
                ->setId($id)
        );
    }

    /**
     * Get Constraint Rate.
     *
     * @param int $item_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function get($item_id)
    {
        return $this->getMapper()->select(
            $this->getModel()
                ->setItemId($item_id)
        );
    }
}
