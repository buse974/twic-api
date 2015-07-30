<?php

namespace Rbac\Db\Service;

use Dal\Service\AbstractService;

class RolePermission extends AbstractService
{
    public function getDroits()
    {
        return $this->getMapper()
            ->getDroit()
            ->toArray();
    }
    
    public function insert($permission)
    {
        return $this->getMapper()->insert($permission);
    }
        
    /**
     * Add role permission
     *
     * @invokable
     *
     * @param int $role_id
     * @param int $permission_id
     *
     * @return int
     */
    public function add($role_id, $permission_id)
    {
        $p_role = $this->getModel();

        $p_role->setRoleId($role_id)
               ->setPermissionId($permission_id);

        return $this->getMapper()->insert($p_role);
    } 
    
    /**
     * Delete role permission 
     * @invokable
     * 
     * @param int $permission_id
     * @return int
     */
    public function delete($permission_id)
    {
        $ret = array();
        if (!is_array($permission_id)) {
            $permission_id = array($permission_id);
        }

        foreach ($permission_id as $i) {
            $m_permission = $this->getModel();
            $m_permission->setPermissionId($i);
            $ret[$i] = $this->getMapper()->delete($m_permission);
        }
        
        return $ret;
    }
    
    /**
     * @invokable
     */
    public function update($permission)
    {
        return $this-getMapper()->update($permission);
    }
}
