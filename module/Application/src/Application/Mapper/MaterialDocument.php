<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class MaterialDocument extends AbstractMapper
{
    
        /**
     * Get List material document by item id.
     *
     * @param int $item
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'course_id', 'type', 'title', 'author', 'link', 'source', 'token'))
        ->join('item_material_document_relation','item_material_document_relation.material_document_id = material_document.id')
        ->where(array('item_material_document_relation.item_id ' => $item));
               
        return $this->selectWith($select);
    }
}
