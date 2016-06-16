<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class BankQuestionItem extends AbstractMapper
{
    public function getList($bank_question_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'libelle', 'bank_question_id', 'order_id'))
            ->join('bank_answer_item', 'bank_answer_item.bank_question_item_id=bank_question_item.id', array('bank_question_item_id', 'percent', 'answer', 'date', 'time'))
            ->where(array('bank_question_item.bank_question_id' => $bank_question_id));

        return $this->selectWith($select);
    }
}
