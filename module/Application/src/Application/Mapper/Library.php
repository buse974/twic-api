<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Library extends AbstractMapper
{
    public function getListByParentItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('item', 'document.item_id=item.id', [])
            ->where(array('item.parent_id' => $item));
        
        syslog(1, $this->printSql($select));
        return $this->selectWith($select);
    }
    
    public function getListByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->where(array('document.item_id' => $item));
    
        return $this->selectWith($select);
    }

    public function getListByBankQuestion($bank_question_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('bank_question_media', 'bank_question_media.library_id=library.id', [])
            ->where(array('bank_question_media.bank_question_id' => $bank_question_id));
    
        return $this->selectWith($select);
    }
    
    public function getListBySubmission($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
        ->join('document', 'document.library_id=library.id', [])
        ->where(array('document.submission_id' => $submission_id));
    
        return $this->selectWith($select);
    }
    
    public function getListByCt($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->join('document', 'document.library_id=library.id', [])
            ->join('ct_done', 'ct_done.target_id=item.parent_id', [])
            ->where(array('ct_done.item_id' => $item));
        
        return $this->selectWith($select);
    }
}