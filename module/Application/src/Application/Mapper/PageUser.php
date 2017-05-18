<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\PageUser as ModelPageUser;
use Zend\Db\Sql\Predicate\Expression;

class PageUser extends AbstractMapper
{
    public function getList($page_id = null, $user_id = null, $role = null, $state = null, $type = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['page_id','user_id','state','role'])
          ->join('page', 'page_user.page_id = page.id', [], $select::JOIN_LEFT)
          ->where(['page.deleted_date IS NULL']);

        if(null!==$role) {
          if ($role !== ModelPageUser::ROLE_ADMIN) {
            $select->where(['page_user.state' => ModelPageUser::STATE_MEMBER]);
          } else {
            $select->where(['page_user.state <> ?' => ModelPageUser::STATE_REJECTED])
              ->order(new Expression('IF(state = "'.ModelPageUser::STATE_PENDING.'", 0, 1)'));
          }
          $select->where(['page_user.role' => $role]);
        }
        if(null!==$page_id) {
          $select->where(['page_user.page_id' => $page_id]);
        }
        if(null!==$user_id) {
          $select->where(['page_user.user_id' => $user_id]);
        }
        if(null!==$state) {
          $select->where(['page_user.state' => $state]);
        }
        if(null!==$type) {
          $select->where(['page.type' => $type]);
        }

        return $this->selectWith($select);
    }

}
