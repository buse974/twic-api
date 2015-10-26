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
     * @param array  $to
     * @param int    $conversation
     * @param bool   $draft
     * @param int    $id
     * @param array  $document
     *
     * @throws \Exception
     */
    public function sendMail($title, $text, $to, $conversation = null, $draft = false, $id = null, $document = null)
    {
        // Fetches sender id
        $me = $this->getServiceUser()->getIdentity()['id'];

        if (!is_array($to)) {
            $to = array($to);
        }

        // Id is set => update
        if (null !== $id) {
            $m_message = $this->get($id);
            $message_id = $m_message->getId();
            $conversation = $m_message->getConversationId();

            // Applies the changes and update
            $m_message = $this->getModel()
                ->setId($message_id)
                ->setTitle($title)
                ->setIsDraft($draft)
                ->setText($text)
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            $this->getMapper()->update($m_message);
        }

        // Id is not set => insert
        else {
            // Conversation is not set => create it and stores the conversation id
            if (null === $conversation) {
                $tmp = $to;
                if (!in_array($me, $tmp)) {
                    $tmp[] = $me;
                }

                $conversation = $this->getServiceConversationUser()->createConversation($tmp, null, 1);
            }

            // Applies the params to a new model
            $m_message = $this->getModel()
                ->setTitle($title)
                ->setIsDraft($draft)
                ->setType(1)
                ->setText($text)
                ->setConversationId($conversation)
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

            // Inserts it or throws an error
            if ($this->getMapper()->insert($m_message) <= 0) {
                throw new \Exception('error insert message');
            }

            // Stores the new message id
            $message_id = $this->getMapper()->getLastInsertValue();
        }

        $this->getServiceMessageDoc()->replace($message_id, $document);
        // Delete all users and inserts them again
        $this->getServiceMessageUser()->hardDeleteByMessage($message_id);
        $message_user_id = $this->getServiceMessageUser()->sendByTo($message_id, $conversation, $to);

        if($draft===false) {
            $this->getServiceEvent()->messageNew($message_id, $to);
        }
        
        return $this->getServiceMessageUser()
            ->getList($me, $message_id)['list']
            ->current();
    }
    
    /**
     * Get Message
     * 
     * @return \Application\Model\Message
     */
    public function get($id) 
    {
        $m_message = $this->getModel()->setId($id);
        $res_message = $this->getMapper()->select($m_message);
        
        // Throws an error if the message does not exist
        if ($res_message->count() <= 0) {
            throw new \Exception('error select message with id :'.$id);
        }
        // Fetches the entity and stores the message and conversation ids
        return $res_message->current();
    }

    /**
     * Send message for videoconf.
     * 
     * @invokable
     *
     * @param string       $text
     * @param int          $to
     * @param conversation $conversation
     *
     * @throws \Exception
     *
     * @return int
     */
    public function sendVideoConf($text = null, $to = null, $conversation = null)
    {
        return $this->_send($text, $to, $conversation, 3);
    }

    /**
     * Send message.
     *
     * @invokable
     *
     * @param string       $text
     * @param int          $to
     * @param conversation $conversation
     *
     * @throws \Exception
     *
     * @return int
     */
    public function send($text = null, $to = null, $conversation = null)
    {
        return $this->_send($text, $to, $conversation, 2);
    }

    public function _send($text = null, $to = null, $conversation = null, $type = null)
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
            if (!is_array($to)) {
                $to = array($to);
            }
            if (!in_array($me, $to)) {
                $to[] = $me;
            }
            $conversation = $this->getServiceConversationUser()->getConversationByUser($to, $type);
        } elseif ($conversation !== null) {
            if ($this->getServiceConversationUser()
                ->getByConversationUser($conversation, $me)
                ->count() !== 1) {
                throw new \Exception('User '.$me.' is not in conversation '.$conversation);
            }
        }

        if (empty($text)) {
            throw new \Exception('error content is empty');
        }
        $m_message = $this->getModel()
            ->setText($text)
            ->setType($type)
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
     * Read Message(s).
     *
     * @invokable
     *
     * @param int|array $message
     */
    public function read($message)
    {
        return $this->getServiceMessageUser()->readByMessage($message);
    }

    /**
     * UnRead Message(s).
     *
     * @invokable
     *
     * @param int|array $message
     */
    public function unRead($message)
    {
        return $this->getServiceMessageUser()->UnReadByMessage($message);
    }

    /**
     * Delete Message(s).
     *
     * @invokable
     *
     * @param int|array $id
     */
    public function delete($id)
    {
        return $this->getServiceMessageUser()->deleteByMessage($id);
    }

    /**
     * @invokable
     *
     * Get List Conversation
     *
     * @param string $filter
     * @param string $tag
     * @param int    $type
     * @param string $search
     */
    public function getListConversation($filter = null, $tag = null, $type = null, $search = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $me = $identity['id'];

        return $this->getServiceMessageUser()->getList($me, null, null, $filter, $tag, $type, $search);
    }

    /**
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
     * @invokable
     *
     * @param integer $school
     * @param integer $day
     *
     * @return integer
     */
    public function getNbrMessage($school, $day = null)
    {
        return $this->getMapper()->getNbrMessage($school, $day);
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
     * @return \Application\Service\MessageDoc
     */
    public function getServiceMessageDoc()
    {
        return $this->getServiceLocator()->get('app_service_message_doc');
    }

    /**
     * @return \Application\Service\ConversationUser
     */
    public function getServiceConversationUser()
    {
        return $this->getServiceLocator()->get('app_service_conversation_user');
    }
    
    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }
}
