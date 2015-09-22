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
     * @param integer $role_id            
     * @param integer $permission_id            
     *
     * @return integer
     */
    public function add($role_id, $permission_id)
    {
        $m_role_permission = $this->getModel()->setRoleId($role_id)->setPermissionId($permission_id);
        
        $ret = $this->getMapper()->insert($m_role_permission);
        
        if ($ret > 0) {
            $this->getServiceRbac()->createRbac();
        }
        
        return $ret;
    }

    /**
     * Delete role permission
     * @invokable
     *
     * @param integer $permission_id            
     * @return integer
     */
    public function delete($permission_id)
    {
        $ret = array();
        if (! is_array($permission_id)) {
            $permission_id = array($permission_id);
        }
        
        foreach ($permission_id as $i) {
            $m_permission = $this->getModel();
            $m_permission->setPermissionId($i);
            $ret[$i] = $this->getMapper()->delete($m_permission);
        }
        
        $this->getServiceRbac()->createRbac();
        
        return $ret;
    }


    /**
     * @invokable
     * 
     * @param integer $id
     * @param integer $role_id
     * @param integer $permission_id
     */
    public function update($id, $role_id, $permission_id)
    {
        $m_role_permission = $this->getModel($id)->setId()->setRoleId($role_id)->setPermissionId($permission_id);
        
        $ret = $this->getMapper()->update($m_role_permission);
        
        if ($ret > 0) {
            $this->getServiceRbac()->createRbac();
        }
        
        return $ret;
    }

    /**
     *
     * @return \Rbac\Service\Rbac
     */
    public function getServiceRbac()
    {
        return $this->getServiceLocator()->get('rbac.service');
    }
}
