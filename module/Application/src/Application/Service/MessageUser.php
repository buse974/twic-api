<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class MessageUser extends AbstractService
{
    /**
     * @param int $message_id
     * @param int $user_id
     * @param int $message_group_id
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($message_id, $user_id, $message_group_id)
    {
        $m_messge_user = $this->getModel()->setMessageId($message_id)
                         ->setUserId($user_id)
                         ->setMessageGroupId($message_group_id)
                         ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_messge_user) <= 0) {
            throw new \Exception('error insert message user');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Delete message.
     *
     * @param int $user
     * @param int $id
     *
     * @return int
     */
    public function delete($user, $id)
    {
        $m_message_user = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_message_user, array('user_id' => $user, 'message_id' => $id));
    }
}
