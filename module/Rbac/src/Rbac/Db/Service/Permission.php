<?php

namespace Rbac\Db\Service;

use Dal\Service\AbstractService;

class Permission extends AbstractService
{
    public function getPermission()
    {
        return $this->getMapper()->getPermissions();
    }

    public function getPermissionByRole($role)
    {
    }

    public function insert($mPerm)
    {
        $this->getMapper()->insert($mPerm);

        return $this->getMapper()->getLastInsertValue();
    }

    public function getListByRole($role)
    {
        return $this->getMapper()->getListByRole($role);
    }
}
