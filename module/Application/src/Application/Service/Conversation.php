<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Conversation extends AbstractService
{
    /**
     * Create Conversation.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function create()
    {
        if ($this->getMapper()->insert($this->getModel()->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))) <= 0) {
            throw new \Exception('Error create conversation');
        }

        return $this->getMapper()->getLastInsertValue();
    }
}
