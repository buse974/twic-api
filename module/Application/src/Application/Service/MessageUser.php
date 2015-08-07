<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class MessageUser extends AbstractService
{

    /**
     * Send message.
     *
     * @param int $message            
     * @param int $conversation            
     *
     * @throws \Exception
     *
     * @return int
     */
    public function send($message, $conversation)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $res_conversation_user = $this->getServiceConversationUser()->getUserByConversation($conversation);
        
        foreach ($res_conversation_user as $m_conversation_user) {
            $m_message_user = $this->getModel()
                ->setMessageId($message)
                ->setConversationId($conversation)
                ->setFromId($me)
                ->setUserId($m_conversation_user->getUserId())
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            
            if ($this->getMapper()->insert($m_message_user) <= 0) {
                throw new \Exception('error insert message to');
            }
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    public function getList($me, $message = null, $conversation = null, $filter = null)
    {
        $mapper = $this->getMapper();
        $list = $mapper->usePaginator($filter)->getList($me, $message, $conversation);
        
        return array('list' => $list, 'count' => $mapper->count());
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
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }
}
