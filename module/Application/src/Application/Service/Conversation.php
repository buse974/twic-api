<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;
use Application\Model\Item as ModelItem;

class Conversation extends AbstractService
{
    /**
     * Create Conversation.
     * 
     * @param integer $type
     * @param integer $submission_id
     * @param array $users
     * @param string $text
     */
    public function create($type = null, $submission_id = null, $users = null, $text = null)
    {
        $m_conversation = $this->getModel()
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setType($type);

        if ($this->getMapper()->insert($m_conversation) <= 0) {
            throw new \Exception('Error create conversation');
        }

        $conversation_id = $this->getMapper()->getLastInsertValue();
        if(null !== $users) {
            $this->getServiceConversationUser()->add($conversation_id, $users);
        }
        if(null !== $submission_id) {
            $this->getServiceSubConversation()->add($conversation_id, $submission_id);
        }
        
        if (null !== $text) {
            switch ($type) {
                case ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT : 
                    return $this->getServiceMessage()->sendSubmission($text, null, $conversation_id);
                    break;
            }
        }
        
        return $conversation_id;         
    }
    
    /**
     * @invokable
     * 
     * @param array $users
     * @param string $text
     * @param integer $submission
     */
    public function createSubmission($users, $text, $submission)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (!in_array($user_id, $users)) {
            $users[] = $user_id;
        }
        return $this->create(ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT,$submission,$users,$text);
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
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (!in_array($user_id, $users)) {
            $users[] = $user_id;
        }
        return $this->create(null,null,$users);
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
        return $this->getServiceConversationUser()->add($conversation, $this->getServiceUser()->getIdentity()['id']);
    }

    /**
     * @invokable
     *
     * @param int $conversation
     */
    public function getConversation($conversation, $filter = [])
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
    public function getListBySubmission($submission_id, $all = false)
    {
        $user_id = (true===$all) ? null: $this->getServiceUser()->getIdentity()['id'];
        $res_conversation = $this->getMapper()->getListBySubmission($submission_id, $user_id);
        $ret = [];
        foreach ($res_conversation as $m_conversation) {
            $ret[] = $this->getConversation($m_conversation->getId()) + $m_conversation->toArray();
        }
        
        return $ret;
    }
    
    /**
     * @param integer $submission_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item_id, $submission_id = null)
    {
        $res_conversation = $this->getMapper()->getListByItem($item_id, $submission_id);
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
        $m_item = $this->getServiceItem()->getBySubmission($submission_id);
        $item_id = null;
        
        // Dans le cas d'un type chat sans set_id il faut tt créer par item
        $by_item = ($m_item->getType()===ModelItem::TYPE_CHAT && !is_numeric($m_item->getSetId()));

        if($by_item) {
            $ar = $this->getListByItem($m_item->getId());
        } else {
            $ar = $this->getListBySubmission($submission_id, true);
        }
        
        if (count($ar) <= 0) {
            $m_submission = $this->getServiceSubmission()->getBySubmission($submission_id);
            if($by_item) {
                $res_user = $this->getServiceUser()->getListByItem($m_item->getId());
            } else {
                $res_user = $this->getServiceUser()->getListUsersBySubmission($submission_id);
            }
            $users = [];
            foreach ($res_user as $m_user) {
                $users[] = $m_user->getId();
            }
            $this->create(ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $submission_id, $users);
        }
        
        if($by_item) { 
            // Vérifier si la conversation est liker sur la submission par un byItem
            $res = $this->getListByItem($m_item->getId(), $submission_id);
            if(count($res) <= 0) {
                if (count($ar) <= 0) {
                    $ar = $this->getListByItem($m_item->getId());
                }
                $conv = current($ar);
                $this->getServiceSubConversation()->add($conv['id'], $submission_id);
            }
        }
        
        return $this->getListBySubmission($submission_id);
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
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
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
