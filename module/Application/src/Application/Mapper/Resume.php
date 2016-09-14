<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Resume extends AbstractMapper
{
     public function m_getListIdByUser($user)
    {
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'user_id'])
               ->where(['user_id' => $user]);
                  
        
        

        return $this->selectWith($select);
    }    
    
}
