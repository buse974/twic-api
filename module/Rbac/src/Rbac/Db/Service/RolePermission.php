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

    public function insert($mRbacDroits)
    {
        return $this->getMapper()->insert($mRbacDroits);
    }

    public function delete($mRbacDroits)
    {
        return $this->getMapper()->delete($mRbacDroits);
    }
}
