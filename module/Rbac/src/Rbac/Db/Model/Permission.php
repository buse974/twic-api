<?php

namespace Rbac\Db\Model;

use Rbac\Db\Model\Base\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $role;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);
        
        $this->role = new Role($this);
        $this->role->exchangeArray($data);
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
