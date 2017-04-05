<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\PageUser as ModelPageUser;
use Zend\Db\Sql\Predicate\Expression;

class PageUser extends AbstractMapper
{
    public function getList($page_id = null, $user_id = null, $role = null, $state = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['page_id','user_id','state','role']);

        if(null !== $role) {
          if ($role !== ModelPageUser::ROLE_ADMIN) {
            $select->where(['state' => ModelPageUser::STATE_MEMBER]);
          } else {
            $select->where(['state <> ?' => ModelPageUser::STATE_REJECTED])
              ->order(new Expression('IF(state = "'.ModelPageUser::STATE_PENDING.'", 0, 1)'));
          }
        }
        if(null!==$page_id) {
          $select->where(['page_id' => $page_id]);
        }
        if(null!==$user_id) {
          $select->where(['user_id' => $user_id]);
        }
        if(null!==$state) {
          $select->where(['state' => $state]);
        }

        return $this->selectWith($select);
    }

}
