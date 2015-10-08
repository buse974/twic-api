<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class DimensionScale extends AbstractMapper
{
    public function getList()
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('id','dimension_id','min','max','describe'))->join('dimension', 'dimension.id=dimension_scale.dimension_id', array('id','name'));
        
        return $this->selectWith($select);
    }
}
