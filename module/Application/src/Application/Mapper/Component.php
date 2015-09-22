<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class Component extends AbstractMapper
{

    public function getList($dimension = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','describe','dimension_id'));
        
        if (null !== $dimension) {
            
            if (is_numeric($dimension)) {
                $select->where(array('component.dimension_id' => $dimension));
            } else {
                $select->join('dimension', 'dimension.id=component.dimension_id', array())->where(array('dimension.name' => $dimension));
            }
        }
        
        if (null !== $search) {
            $select->where(array(' ( component.name LIKE ?' => '%' . $search . '%'))->where(array('component.describe LIKE ? )' => '%' . $search . '%'), Predicate::OP_OR);
        }
        
        $select->where(array('component.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }
}