<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Application\Model\PageUser as ModelPageUser;
use Zend\Db\Sql\Predicate\Expression;

class PageUser extends AbstractMapper
{
    
    public function getList($page_id = null)
    {
        $select = $this->tableGateway->getSql()->select()
            ->columns(['page_id','user_id','state','role'])   
            ->where(['page_id' => $page_id])
            ->where('state <> "'.ModelPageUser::STATE_REJECTED.'"')
            ->order(new Expression('IF(state = "'.ModelPageUser::STATE_PENDING.'", 0, 1)'));

       
        syslog(1, $this->printSql($select));
        return $this->selectWith($select);
    }
    
}