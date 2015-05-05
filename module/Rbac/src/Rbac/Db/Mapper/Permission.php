<?php

namespace Rbac\Db\Mapper;

use Dal\Mapper\AbstractMapper;

class Permission extends AbstractMapper
{
    /**
     * @param int $role
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByRole($role)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'libelle'))
            ->join('role_permission', 'role_permission.permission_id =permission.id', array(), $select::JOIN_LEFT)
            ->where(array('role_permission.role_id' => $role));

        return $this->selectWith($select);
    }
}
