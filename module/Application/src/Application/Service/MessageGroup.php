<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class MessageGroup extends AbstractService
{
    /**
     * Create a id group.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function create()
    {
        if ($this->getMapper()->insert($this->getModel()) <= 0) {
            throw new \Exception('error create Group Id');
        }

        return $this->getMapper()->getLastInsertValue();
    }
}
