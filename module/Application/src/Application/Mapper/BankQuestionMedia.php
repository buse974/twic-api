<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class BankQuestionMedia extends AbstractMapper
{
    public function getListBankQuestion($bank_question_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['bank_question_id', 'library_id'])
            ->join('library', 'bank_question_media.library_id=library.id', ['id', 'name', 'link', 'token', 'type', 'created_date', 'deleted_date', 'updated_date', 'folder_id', 'owner_id', 'box_id'])
            ->where(array('bank_question_media.bank_question_id' => $bank_question_id));

        return $this->selectWith($select);
    }
}
