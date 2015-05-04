<?php
namespace Rbac\Db\Model;

use Rbac\Db\Model\Base\RoleRelation as BaseRoleRelation;

class RoleRelation extends BaseRoleRelation
{
    protected $role;
    protected $permission;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
        
        $this->role = new Role($this);
        $this->permission = new Permission($this);
        
        $this->permission->exchangeArray($data);
        $this->role->exchangeArray($data);
    }
    
    public function setPermission($permission)
    {
        $this->permission = $permission;
        
        return $this;
    }
     
    public function getPermission()
    {
        return $this->permission;
    }
    
    public function setRole($role)
    {
        $this->role = $role;
        
        return $this;
    }
     
    public function getRole()
    {
        return $this->role;
    }
}
