<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class MessageReceiver extends AbstractService
{
    public function add($message, $receiver)
    {
        foreach ($receiver as $mr) {
            $this->_add($mr['type'], $mr['user'], $message);
        }

        return true;
    }

    /**
     * Add receiver.
     *
     * @param string $type
     * @param int    $user_id
     * @param int    $message
     *
     * @return int
     */
    public function _add($type, $user_id, $message)
    {
        if ($this->getMapper()->insert($this->getModel()->setType($type)
                         ->setMessageId($message)
                         ->setUserId($user_id)) <= 0) {
            throw new \Exception('error insert receiver');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    public function delete($receiver, $message)
    {
        return $this->getMapper()->delete($this->getModel()->setMessageId($message)->setId($receiver));
    }

    public function replace($receiver, $message)
    {
        $this->getMapper()->delete($this->getModel()->setMessageId($message));
        $this->add($message, $receiver);

        return true;
    }

    public function getByMessage($message)
    {
        return $this->getMapper()->select($this->getModel()->setMessageId($message));
    }
}
