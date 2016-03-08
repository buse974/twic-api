<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Group extends AbstractMapper
{
    /**
     * 
     * @param integer $set
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($set)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'uid', 'name'))
            ->join('set_group', 'set_group.group_id=group.id')
            ->where(array('set_group.set_id' => $set));
        
        return $this->selectWith($select);
    }
}