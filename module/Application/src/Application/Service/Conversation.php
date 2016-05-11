<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;

class Conversation extends AbstractService
{
    /**
     * Create Conversation.
     * 
     * @param integer $type
     * @param integer $submission_id
     * 
     * @return integer
     */
    public function create($type = null, $submission_id = null)
    {
        $m_conversation = $this->getModel()
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setType($type);

        if ($this->getMapper()->insert($m_conversation) <= 0) {
            throw new \Exception('Error create conversation');
        }

        $conversation_id = $this->getMapper()->getLastInsertValue();
        
        if(null !== $submission_id) {
            $this->getServiceSubConversation()->add($conversation_id, $submission_id);
        }
        
        return $conversation_id;         
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
     * Joindre conversation.
     *
     * @invokable
     *
     * @param integer $conversation
     */
    public function join($conversation)
    {
        return $this->getServiceConversationUser()->join($this->getServiceUser()->getIdentity()['id'], $conversation);
    }

    /**
     * @invokable
     *
     * @param int $conversation
     */
    public function getConversation($conversation, $filter = null)
    {
        $conv['users'] = $this->getServiceUser()->getListByConversation($conversation)->toArray(array('id'));
        $conv['messages'] = $this->getServiceMessage()->getList($conversation, $filter);
        $conv['id'] = $conversation;

        return $conv;
    }
    
    /**
     * @param integer $submission_id
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        $res_conversation = $this->getMapper()->getListBySubmission($submission_id);
        
        $ret = [];
        foreach ($res_conversation as $m_conversation) {
            $ret[] = $this->getConversation($m_conversation->getId()) + $m_conversation->toArray();
        }
        
        return $ret;
    }

    /**
     * 
     * @param integer $submission_id
     * 
     * @return []
     */
    public function getListOrCreate($submission_id)
    {
        $ar = $this->getListBySubmission($submission_id);
        if (count($ar) <= 0) {
            $m_submission = $this->getServiceSubmission()->getBySubmission($submission_id);
            $res_user = $this->getServiceUser()->getListUsersGroupByItemAndUser($m_submission->getItemId());
            $users = [];
            foreach ($res_user as $m_user) {
                $users[] = $m_user->getId();
            }
            $this->getServiceConversationUser()->createConversation($users, null, ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $submission_id);
            $ar = $this->getListBySubmission($submission_id);
        }
        
        return $ar;
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
     * @return \Application\Service\SubConversation
     */
    public function getServiceSubConversation()
    {
        return $this->getServiceLocator()->get('app_service_sub_conversation');
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
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * @return \Application\Service\Message
     */
    public function getServiceMessage()
    {
        return $this->getServiceLocator()->get('app_service_message');
    }
}
