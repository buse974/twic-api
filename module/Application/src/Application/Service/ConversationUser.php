<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Conversation as ModelConversation;

class ConversationUser extends AbstractService
{
    /**
     * @invokable
     * 
     * @param array $users
     * @param int   $type
     */
    public function getConversationByUser(array $users, $type = null)
    {
        $conversation = null;
        $identity = $this->getServiceUser()->getIdentity();
        if (!in_array($identity['id'], $users)) {
            $users[] = $identity['id'];
        }

        $res_conversation_user = $this->getMapper()->getConversationByUser($users, $type);  
        if ($res_conversation_user->count() === 1) {
            $conversation = $res_conversation_user->current()->getConversationId();
        } elseif ($res_conversation_user->count() === 0) {
            $conversation = $this->createConversation($users, null, $type);
        } elseif ($res_conversation_user->count() > 1) {
            throw new \Exception('more of one conversation');
        }

        return $conversation;
    }
    
    /**
     * @invokable
     *
     * @param integer $submission_id
     */
    public function getListConversationBySubmission($submission_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        $res_conversation = $this->getServiceConversation()->getListBySubmission($submission_id);
        if ($res_conversation->count() <= 0) {
            $m_submission = $this->getServiceSubmission()->getByItem($item_id);
            $res_user = $this->getServiceUser()->getListUsersGroupByItemAndUser($m_submission->getItemId());
            $users = [];
            foreach ($res_user as $m_user) {
                $users[] = $m_user->getId();
            }
            $this->createConversation($users, null, ModelConversation::TYPE_ITEM_GROUP_ASSIGNMENT, $submission_id);
            $res_conversation = $this->getServiceConversation()->getListBySubmission($submission_id);
        }
    
        return $res_conversation;
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

    /**
     * @param array  $users
     * @param string $videoconf
     *
     * @return int
     */
    public function createConversation($users = null, $videoconf = null, $type = null, $submission_id = null)
    {
        $conversation_id = $this->getServiceConversation()->create($type, $submission_id);

        $m_conversation_user = $this->getModel()->setConversationId($conversation_id);
        foreach ($users as $user) {
            $m_conversation_user->setUserId($user);
            $this->getMapper()->insert($m_conversation_user);
        }

        if ($videoconf !== null) {
            $this->getServiceVideoconfConversation()->add($conversation_id, $videoconf);
        }

        return $conversation_id;
    }

    /**
     * @param integer $user
     * @param integer $conversation
     */
    public function join($user, $conversation)
    {
        if(!is_array($user)) {
            $user = [$user];
        }
        
        
        $ret = 0;
        $m_conversation_user = $this->getModel()->setConversationId($conversation);
        foreach ($user as $u) {
            $m_conversation_user->setUserId($u);
            if($this->getMapper()->select($m_conversation_user)->count() === 0) {
                $ret =+ $this->getMapper()->insert($m_conversation_user);
            }
        }
        
        return $ret;
    }

    public function add($conversation, $users)
    {
        $ret = [];
        foreach ($users as $user) {
            $ret[$user] = $this->getMapper()->add($conversation, $user);
        }

        return $ret;
    }

    public function replace($conversation, $users)
    {
        $this->getMapper()->deleteNotIn($conversation, $users);

        return $this->add($conversation, $users);
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
    
    /**
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }
    
    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
}
