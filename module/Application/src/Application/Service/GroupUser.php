<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Group User
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class GroupUser
 */
class GroupUser extends AbstractService
{

    /**
     * Add User to Group
     *
     * @param int $group_id            
     * @param array|int $users            
     * @return boolean
     */
    public function add($group_id, $users)
    {
        if (! is_array($users)) {
            $users = [$users];
        }
        foreach ($users as $u) {
            $this->getMapper()->insert($this->getModel()
                ->setGroupId($group_id)
                ->setUserId($u));
        }
        
        return true;
    }

    /**
     * Get List Id User
     *
     * @param int $group_id            
     * @return Array
     */
    public function getListUser($group_id)
    {
        $res_group_user = $this->getMapper()->select($this->getModel()
            ->setGroupId($group_id));
        $u = [];
        foreach ($res_group_user as $m_group_user) {
            $u[] = $m_group_user->getUserId();
        }
        
        return $u;
    }

    /**
     * Get Group Id By Item And User
     *
     * @param int $item_id            
     * @param int $user_id            
     *
     * @return null|int
     */
    public function getGroupIdByItemUser($item_id, $user_id)
    {
        $res_group_user = $this->getMapper()->getGroupIdByItemUser($item_id, $user_id);
        
        return ($res_group_user->count() > 0) ? $res_group_user->current()->getGroupId() : null;
    }

    /**
     * Delete Group user relation
     *
     * @param int $group_id            
     * @param int $user_id            
     * @return int
     */
    public function delete($group_id, $user_id = null)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setGroupId($group_id)
            ->setUserId($user_id));
    }
}
