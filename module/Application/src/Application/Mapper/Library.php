<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Library extends AbstractMapper
{
    public function getListByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('item', 'document.item_id=item.parent_id', [])
            ->where(array('item.id' => $item));
        
        return $this->getMapper()->getListByItem($item);
    }

    public function getListByCt($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('ct_done', 'ct_done.target_id=item.parent_id', [])
            ->where(array('ct_done.item_id' => $item));
        
        return $this->getMapper()->getListByItem($item);
    }

    public function getListBySubmission($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('ct_done', 'ct_done.target_id=item.parent_id', [])
            ->where(array('ct_done.item_id' => $item));
        
        return $this->getMapper()->getListByItem($item);
    }
}