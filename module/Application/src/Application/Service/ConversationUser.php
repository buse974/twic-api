<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Conversation User
 *
 */

namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Conversation User
 */
class ConversationUser extends AbstractService
{
    /**
     * Get Conversation OR Create if not exist
     * 
     * @invokable
     * 
     * @param array $users
     * @param int   $type
     * @return int
     */
    public function getConversationByUser(array $users, $type = null)
    {
        $conversation_id = null;
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        if (!in_array($user_id, $users)) {
            $users[] = $user_id;
        }

        $res_conversation_user = $this->getMapper()->getConversationByUser($users, $type);
        if ($res_conversation_user->count() === 1) {
            $conversation_id = $res_conversation_user->current()->getConversationId();
        } elseif ($res_conversation_user->count() === 0) {
            $conversation_id = $this->getServiceConversation()->create($type, null, $users);
        } elseif ($res_conversation_user->count() > 1) {
            throw new \Exception('more of one conversation');
        }

        return $conversation_id;
    }

    /**
     * Get List Conversation 
     * 
     * @invokable
     *
     * @param int $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListConversationBySubmission($submission_id)
    {
        return $this->getServiceConversation()->getListBySubmission($submission_id);
    }

    /**
     * Get User By Conversation
     * 
     * @param int $conversation_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getUserByConversation($conversation_id)
    {
        return $this->getMapper()->select($this->getModel()->setConversationId($conversation_id));
    }

    /**
     * Check If is in conversation
     * 
     * @param int $conversation_id
     * @param int $user_id
     * @return bool
     */
    public function isInConversation($conversation_id, $user_id)
    {
        $res_conversation_user = $this->getMapper()->select($this->getModel()->setConversationId($conversation_id)->setUserId($user_id));

        return $res_conversation_user->count() > 0;
    }

    /**
     * Add User in the Conversation
     * 
     * @param int $conversation_id
     * @param int|array $users
     * @return array
     */
    public function add($conversation_id, $users)
    {
        if (!is_array($users)) {
            $users = [$users];
        }

        $ret = [];
        foreach ($users as $user) {
            $ret[$user] = $this->getMapper()->add($conversation_id, $user);
        }

        return $ret;
    }

    /**
     * Replace user in conversation
     * 
     * @param int $conversation_id
     * @param array  $users
     * @return array
     */
    public function replace($conversation_id, $users)
    {
        $this->getMapper()->deleteNotIn($conversation, $users);

        return $this->add($conversation, $users);
    }

    /**
     * Get Service Conversation
     * 
     * @return \Application\Service\Conversation
     */
    private function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }

    /**
     * Get Service User
     * 
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
