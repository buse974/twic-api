<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ConversationUser extends AbstractService
{
    /**
     * @invokable
     * 
     * @param array $users
     * @param int   $type
     */
    public function getConversationByUser(array $users, $type = null, $item = null, $group = null)
    {
        $conversation = null;
        $identity = $this->getServiceUser()->getIdentity();
        if (!in_array($identity['id'], $users)) {
            $users[] = $identity['id'];
        }

        $res_conversation_user = $this->getMapper()->getConversationByUser($users, $type, $item, $group);

        if ($res_conversation_user->count() === 1) {
            $conversation = $res_conversation_user->current()->getConversationId();
        } elseif ($res_conversation_user->count() === 0) {
            $conversation = $this->createConversation($users, null, $type, $item, $group);
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

    /**
     * @param array  $users
     * @param string $videoconf
     *
     * @return int
     */
    public function createConversation($users, $videoconf = null, $type = null, $item = null, $group = null)
    {
        $conversation_id = $this->getServiceConversation()->create($type, $item, $group);

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
}
