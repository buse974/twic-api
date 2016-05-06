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
            ->join('sub_question', 'sub_question.bank_question_id=bank_question.id', [])
            ->where(['sub_question.bank_question_id' => $bank_question_id]);
        
        return $this->selectWith($select);
    }
    
    public function getList($course_id, $search = null, $older = false)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'name', 'question', 'bank_question_type_id', 'course_id', 'point', 'older', 'created_date'])
            ->join('bank_question_tag', 'bank_question_tag.bank_question_id=bank_question.id', [], $select::JOIN_LEFT)
            ->where(['( bank_question_tag.name LIKE ? ' => '%'.$search.'%'])
            ->where(['bank_question.name LIKE ? ' => '%'.$search.'%'], Predicate::OP_OR)
            ->where(['bank_question.question LIKE ? )' => '%'.$search.'%'], Predicate::OP_OR)
            ->where(['bank_question.course_id' => $course_id])
            ->quantifier('DISTINCT');
        if($older !== true){
            $select->where(['bank_question.older IS NULL']);
        }
    
        return $this->selectWith($select);
    }
}