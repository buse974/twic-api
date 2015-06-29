<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ItemAssigmentDocument extends AbstractMapper
{
    
    
    public function getListByItemAssignment($item_assignment){
          $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'item_assigment_id', 'type', 'title', 'author', 'link', 'source', 'token'))
               ->where(array('item_assigment_document.item_assigment_id' => $item_assignment));

        return $this->selectWith($select);
    }
}
