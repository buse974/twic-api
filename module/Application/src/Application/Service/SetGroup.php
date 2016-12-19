<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Set Group
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SetGroup.
 */
class SetGroup extends AbstractService
{
    /**
     * Add Set Group.
     *
     * @param int $set
     * @param int $group
     *
     * @return int
     */
    public function add($set, $group)
    {
        return $this->getMapper()->insert($this->getModel()->setSetId($set)->setGroupId($group));
    }
}
