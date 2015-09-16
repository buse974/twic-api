<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Dimension extends AbstractMapper
{
    /**
     * 
     * @param string $search
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id', 'name', 'describe', 'deleted_date'));

        if(null !== $search) {
            $select->where(array('name LIKE ? ' => '%' . $search . '%'));
        }
        
        return $this->selectWith($select);
    }
}