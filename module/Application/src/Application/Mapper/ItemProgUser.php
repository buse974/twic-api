<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Application\Model\Role as ModelRole;

class ItemProgUser extends AbstractMapper
{
    public function insertStudent($u, $ip)
    {
        $select = new Select('user');
        $select->columns(array('id', 'ip' => new Expression($ip)))
            ->join('user_role', 'user_role.user_id=user.id', array())
            ->where(array('user_role.role_id=' . ModelRole::ROLE_STUDENT_ID))
            ->where(array('user_role.user_id' => $u));
        
        $insert = $this->tableGateway->getSql()->insert();

        $insert->columns(array('user_id', 'item_prog_id'))->select($select);
        
        return $this->insertWith($insert);
    }
}
