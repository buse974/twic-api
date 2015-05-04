<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class MessageDoc extends AbstractService
{
    public function add($message, $token)
    {
        if ($this->getMapper()->insert($this->getModel()
                                           ->setToken($token)
                                           ->setMessageId($message)
                                           ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))) <= 0) {
            throw new \Exception('erro insert document');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    public function delete($message, $document)
    {
        return $this->getMapper()->delete($this->getModel()->setMessageId($message)->setId($document));
    }

    public function replace($document, $message)
    {
        $this->getMapper()->delete($this->getModel()->setMessageId($message));

        foreach ($document as $doc) {
            $this->add($message, $doc);
        }

        return true;
    }

    public function getByMessage($message)
    {
        return $this->getMapper()->select($this->getModel()->setMessageId($message));
    }
}
