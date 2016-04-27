<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class BankQuestion extends AbstractMapper
{
    public function getWithPollItemExist($bank_question_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'question', 'bank_question_type_id', 'course_id', 'point', 'older', 'created_date'])
            ->join('poll_item', 'poll_item.bank_question_id=bank_question.id', [], $select::JOIN_LEFT)
            ->join('question_relation', 'question_relation.group_question_id=poll_item.group_question_id', [], $select::JOIN_LEFT)
            ->where([' ( poll_item.bank_question_id = ? ' => $bank_question_id])
            ->where([' question_relation.bank_question_id = ? ) ' => $bank_question_id], Predicate::OP_OR);
        
        return $this->selectWith($select);
    }
}