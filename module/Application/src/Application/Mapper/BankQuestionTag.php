<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class BankQuestionTag extends AbstractMapper
{
    public function getList($bank_question_id = null, $course_id = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['name']);

        if (null !== $bank_question_id) {
            $select->where(['bank_question_tag.bank_question_id' => $bank_question_id]);
        }

        if (null !== $search) {
            $select->where(['bank_question_tag.name LIKE ?' => '%'.$search.'%']);
        }

        if (null !== $course_id) {
            $select->join('bank_question', 'bank_question.id=bank_question_tag.bank_question_id', [])
                ->where(['bank_question.course_id' => $course_id]);
        }

        $select->quantifier('DISTINCT');

        return $this->selectWith($select);
    }
}
