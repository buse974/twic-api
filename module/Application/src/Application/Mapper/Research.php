<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Research extends AbstractMapper
{
    /**
     * Get research list
     * 
     * @param string $string 
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($string, $filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','firstname','lastname','avatar','category','role'))
                ->where(array('(firstname LIKE ?' => '%' .$string . '%'))
                ->where(array('lastname LIKE ?)' => '%' .$string. '%'), \Zend\Db\Sql\Predicate\Predicate::OP_OR)
                ->order(array('facette', 'firstname'));

        return $this->selectWith($select);
    }
}
