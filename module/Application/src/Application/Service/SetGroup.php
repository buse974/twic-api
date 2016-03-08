<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SetGroup extends AbstractService
{
    /**
     * @param integer $set
     * @param integer $group
     * 
     * @return integer
     */
    public function add($set, $group)
    {
        return $this->getMapper()->insert($this->getModel()->setSetId($set)->setGroupId($group));
    }
}