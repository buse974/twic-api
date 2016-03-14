<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Group extends AbstractMapper
{
    /**
     * 
     * @param integer $set
     * @param string $name
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($set, $name = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'uid', 'name'))
            ->join('set_group', 'set_group.group_id=group.id')
            ->where(array('set_group.set_id' => $set));
        
        if($name !== null) {
        	$select->where(['group.name LIKE ?' => '%'. $name .'%']);
        }
        
        return $this->selectWith($select);
    }
}