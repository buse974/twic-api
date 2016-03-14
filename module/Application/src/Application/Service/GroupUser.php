<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GroupUser extends AbstractService
{
    public function add($group, $users)
    {
        if(!is_array($users)) {
            $users = [$users];
        }
        foreach ($users as $u) {
            $this->getMapper()->insert($this->getModel()->setGroupId($group)->setUserId($u));
        }
    
        return true;
    }
    
    public function getListUser($group)
    {
        $res_group_user = $this->getMapper()->select($this->getModel()->setGroupId($group));
        
        $u=[];
        foreach ($res_group_user as $m_group_user) {
            $u[] = $m_group_user->getUserId();
        }
        
        return $u;
    }
    
    public function delete($group, $user = null) 
    {
        return $this->getMapper()->delete($this->getModel()->setGroupId($group)->setUserId($user));
    }
}