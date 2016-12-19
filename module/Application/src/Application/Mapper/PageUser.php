<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\PageUser as ModelPageUser;
use Zend\Db\Sql\Predicate\Expression;

class PageUser extends AbstractMapper
{
    public function getList($page_id = null, $role = null)
    {
        $select = $this->tableGateway->getSql()->select()
            ->columns(['page_id','user_id','state','role'])
            ->where(['page_id' => $page_id]);
        if ($role !== ModelPageUser::ROLE_ADMIN) {
            $select->where('state = "'.ModelPageUser::STATE_MEMBER.'"');
        } else {
            $select->where('state <> "'.ModelPageUser::STATE_REJECTED.'"')
                    ->order(new Expression('IF(state = "'.ModelPageUser::STATE_PENDING.'", 0, 1)'));
        }

       
        return $this->selectWith($select);
    }
}
