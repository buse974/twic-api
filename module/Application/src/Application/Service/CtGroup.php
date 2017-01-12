<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Contrainte Group
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class CtGroup.
 */
class CtGroup extends AbstractService
{
    /**
     * Add Contraint Group.
     *
     * @invokable
     *
     * @param int  $item_id
     * @param int  $group
     * @param bool $belongs
     *
     * @return int
     */
    public function add($item_id, $group, $belongs = true)
    {
        $m_ct_group = $this->getModel()
            ->setItemId($item_id)
            ->setGroupId($group)
            ->setBelongs($belongs);
        $this->getMapper()->insert($m_ct_group);

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Update Contraint Group.
     *
     * @invokable
     *
     * @param int    $id
     * @param string $group
     * @param bool   $belongs
     *
     * @return int
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
     * Delete Contraint Group.
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
     * Get Contraint Group.
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
