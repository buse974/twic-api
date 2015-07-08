<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationUser extends AbstractService
{
    public function getConversationByUser(array $users)
    {
        $conversation = null;
        $identity = $this->getServiceUser()->getIdentity();
        if (!in_array($identity['id'], $users)) {
            $users[] = $identity['id'];
        }

        $res_conversation_user = $this->getMapper()->getConversationByUser($users);

        if ($res_conversation_user->count() === 1) {
            $conversation = $res_conversation_user->current()->getConversationId();
        } elseif ($res_conversation_user->count() === 0) {
            $conversation = $this->createConversation($users);
        } elseif ($res_conversation_user->count() > 1) {
            throw new \Exception('more of one conversation');
        }

        return $conversation;
    }

    /**
     * @param int $conversation
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getUserByConversation($conversation)
    {
        return $this->getMapper()->select($this->getModel()->setConversationId($conversation));
    }

    public function createConversation($users)
    {
        $conversation_id = $this->getServiceConversation()->create();

        $m_conversation_user = $this->getModel()->setConversationId($conversation_id);
        foreach ($users as $user) {
            $m_conversation_user->setUserId($user);
            $this->getMapper()->insert($m_conversation_user);
        }

        return $conversation_id;
    }

    /**
     * @param int $conversation
     * @param int $user
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByConversationUser($conversation, $user)
    {
        $m_conversation_user = $this->getModel()->setConversationId($conversation)->setUserId($user);

        return $this->getMapper()->select($m_conversation_user);
    }

    /**
     * @return \Application\Service\Conversation
     */
    public function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
