<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Research extends AbstractMapper
{
    public function getList($filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','firstname','lastname','avatar','category','role','position','interest','avatar'));
          
        return $this->selectWith($select);
    }
}
