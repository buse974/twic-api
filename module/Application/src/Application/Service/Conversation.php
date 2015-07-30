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
    
    /**
     * @invokable
     * 
     * @param integer $conversation
     */
    public function getConversation($conversation)
    {
        $conv['users'] = $this->getServiceUser()->getListByConversation($conversation)->toArray(array('id'));;
        $conv['messages'] = $this->getServiceMessage()->getList($conversation);
        $conv['id'] = $conversation;
        return $conv;
    }
    
    /**
     *
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }
    
    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     *
     * @return \Application\Service\Message
     */
    public function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }
}
