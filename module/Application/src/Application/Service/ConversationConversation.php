<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationConversation extends AbstractService
{
    public function add($id, $conversation_id)
    {
        return $this->getMapper()->insert($this->getModel()->setId($id)->setConversationId($conversation_id));
    }
    
    public function getList($conversation_id, $user_id = null)
    {
        if(null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        return $this->getMapper()->getList($conversation_id, $user_id);
    }
    
    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}