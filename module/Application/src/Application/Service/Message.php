<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Message extends AbstractService
{

    /**
     * @invokable
     *
     * @param string $title            
     * @param string $text            
     * @param array $to            
     * @param integer $conversation            
     * @param boolean $draft            
     * @param integer $id            
     * @throws \Exception
     */
    public function sendMail($title, $text, $to, $conversation = null, $draft = false, $id = null)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        if (! is_array($to)) {
            $to = array($to);
        }
        if (! in_array($me, $to)) {
            $to[] = $me;
        }
        
        if (null !== $id) {
            $m_message = $this->getModel()->setId($id);
            $res_message = $this->getMapper()->select($m_message);
            
            if($res_message->count() <= 0) {
                throw new \Exception('error select message with id :' . $id);
            }
            $m_message= $res_message->current();
            $message_id = $m_message->getId();
            $conversation = $m_message->getConversationId();
            $this->getServiceMessageUser()->hardDeleteByMessage($message_id);
            
            $m_message = $this->getModel()
                ->setId($message_id)
                ->setTitle($title)
                ->setIsDraft($draft)
                ->setText($text);
            
            $this->getMapper()->update($m_message);
        } else {
            if (null === $conversation) {
                $conversation = $this->getServiceConversationUser()->createConversation($to);
            }
            
            $m_message = $this->getModel()
                ->setTitle($title)
                ->setIsDraft($draft)
                ->setText($text)
                ->setConversationId($conversation)
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            
            if ($this->getMapper()->insert($m_message) <= 0) {
                throw new \Exception('error insert message');
            }
            
            $message_id = $this->getMapper()->getLastInsertValue();
        }
        
        $message_user_id = $this->getServiceMessageUser()->sendByTo($message_id, $conversation, $to);
        
        return $this->getServiceMessageUser()
            ->getList($me, $message_id)['list']
            ->current();
    }

    /**
     * Send message.
     *
     * @invokable
     *
     * @param string $text            
     * @param int $to            
     * @param conversation $conversation            
     *
     * @throws \Exception
     *
     * @return int
     */
    public function send($text = null, $to = null, $conversation = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        
        $me = $identity['id'];
        
        /*
         * if $to
         * on vérifie qu'il n'esiste pas de conversation deja existante
         * if oui
         * on récupaire la conversation id
         * if non
         * on créé la conversation
         * else if $conversation
         * on vérifie que la personne qui envoie le messge fait parti de la conversation
         * if oui
         * continue;
         * if non
         * exception;
         */
        if (null !== $to) {
            if (! is_array($to)) {
                $to = array($to);
            }
            if (! in_array($me, $to)) {
                $to[] = $me;
            }
            $conversation = $this->getServiceConversationUser()->getConversationByUser($to);
        } elseif ($conversation !== null) {
            if ($this->getServiceConversationUser()
                ->getByConversationUser($conversation, $me)
                ->count() !== 1) {
                throw new \Exception('User ' . $me . ' is not in conversation ' . $conversation);
            }
        }
        
        if (empty($text)) {
            throw new \Exception('error content is empty');
        }
        $m_message = $this->getModel()
            ->setText($text)
            ->setConversationId($conversation)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_message) <= 0) {
            throw new \Exception('error insert message');
        }
        
        $message_id = $this->getMapper()->getLastInsertValue();
        $message_user_id = $this->getServiceMessageUser()->send($message_id, $conversation);
        
        return $this->getServiceMessageUser()
            ->getList($me, $message_id)['list']
            ->current();
    }

    /**
     * @invokable
     *
     * Get List By user Conversation
     *
     * @param int $conversation            
     */
    public function getList($conversation, $filter = array())
    {
        $identity = $this->getServiceUser()->getIdentity();
        $me = $identity['id'];
        
        return $this->getServiceMessageUser()->getList($me, null, $conversation, $filter);
    }

    /**
     * Read Message(s)
     *
     * @invokable
     *
     * @param integer|array $message            
     */
    public function read($message)
    {
        return $this->getServiceMessageUser()->readByMessage($message);
    }
    
    /**
     * Delete Message(s)
     *
     * @invokable
     *
     * @param integer|array $id
     */
    public function delete($id)
    {
        return $this->getServiceMessageUser()->deleteByMessage($id);
    }

    /**
     * @invokable
     *
     * Get List Conversation
     * @param string $filter
     * 
     */
    public function getListConversation($filter = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $me = $identity['id'];
        
        return $this->getServiceMessageUser()->getList($me, null, null, $filter);
    }

    /**
     *
     * @param int $id            
     *
     * @return \Application\Model\Message
     */
    public function getMessage($id)
    {
        $res_message = $this->getMapper()->select($this->getModel()
            ->setId($id));
        
        if ($res_message->count() <= 0) {
            throw new \Exception('error get messge ');
        }
        
        return $res_message->current();
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
     * @return \Application\Service\MessageUser
     */
    public function getServiceMessageUser()
    {
        return $this->getServiceLocator()->get('app_service_message_user');
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
