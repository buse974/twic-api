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
    public function create($type = null)
    {
        $m_conversation = $this->getModel()
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setType($type);

        if ($this->getMapper()->insert($m_conversation) <= 0) {
            throw new \Exception('Error create conversation');
        }

        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Create conversation.
     *
     * @invokable
     *
     * @param array $users
     *
     * @return int
     */
    public function add($users)
    {
        return $this->getServiceConversationUser()->createConversation($users);
    }

    /**
     * @invokable
     *
     * @param int $conversation
     */
    public function getConversation($conversation, $filter = null)
    {
        $conv['users'] = $this->getServiceUser()
            ->getListByConversation($conversation)
            ->toArray(array('id'));
        $conv['messages'] = $this->getServiceMessage()->getList($conversation, $filter);
        $conv['id'] = $conversation;

        return $conv;
    }

    /**
     * Read Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     */
    public function read($conversation)
    {
        return $this->getServiceMessageUser()->readByConversation($conversation);
    }

    /**
     * UnRead Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     */
    public function unRead($conversation)
    {
        return $this->getServiceMessageUser()->unReadByConversation($conversation);
    }

    /**
     * Delete Message(s).
     *
     * @invokable
     *
     * @param int|array $conversation
     */
    public function delete($conversation)
    {
        return $this->getServiceMessageUser()->deleteByConversation($conversation);
    }

    /**
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\MessageUser
     */
    public function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
    }

    /**
     * @return \Application\Service\Message
     */
    public function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }
}
