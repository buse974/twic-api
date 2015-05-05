<?php

namespace Rbac\Db\Model;

use Rbac\Db\Model\Base\RolePermission as BaseRolePermission;

class RolePermission extends BaseRolePermission
{
    protected $role;

    protected $permission;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->permission = new Permission($this);
        $this->role = new Role($this);

        $this->permission->exchangeArray($data);
        $this->role->exchangeArray($data);
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    public function getPermission()
    {
        return $this->permission;
    }

    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }
}
