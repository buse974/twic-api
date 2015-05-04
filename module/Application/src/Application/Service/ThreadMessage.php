<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ThreadMessage extends AbstractService
{
    /**
     * Add message in thread.
     *
     * @invokable
     *
     * @param string $message
     * @param int    $thread
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($message, $thread)
    {
        $m_thread_message =  $this->getModel()
                                  ->setMessage($message)
                                  ->setThreadId($thread)
                                  ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
                                  ->setUserId($this->getServiceAuth()->getIdentity()->getId());

        if ($this->getMapper()->insert($m_thread_message) <= 0) {
            throw new \Exception('error insert thread');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * update message in thread.
     *
     * @invokable
     *
     * @TODO set UpdateDate value
     *
     * @param string $message
     * @param int    $id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function update($message, $id)
    {
        //->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
        return $this->getMapper()->update($this->getModel()->setMessage($message), array('user_id' => $this->getServiceAuth()->getIdentity()->getId(), 'id' => $id));
    }

    /**
     * delete message.
     *
     * @invokable
     *
     * @param int $id
     */
    public function delete($id)
    {
        return $this->getMapper()->update(
                $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')),
                array('user_id' => $this->getServiceAuth()->getIdentity()->getId(), 'id' => $id));
    }

    /**
     * Get list thread message.
     *
     * @invokable
     *
     * @param int $thread
     */
    public function getList($thread)
    {
        return $this->getMapper()->getList($thread);
    }

    /**
     * @return \Auth\Service\AuthService
     */
    public function getServiceAuth()
    {
        return $this->getServiceLocator()->get('auth.service');
    }
}
