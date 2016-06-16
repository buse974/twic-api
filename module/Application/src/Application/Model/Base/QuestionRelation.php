<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class QuestionRelation extends AbstractModel
{
    protected $group_question_id;
    protected $bank_question_id;

    protected $prefix = 'question_relation';

    public function getGroupQuestionId()
    {
        return $this->group_question_id;
    }

    public function setGroupQuestionId($group_question_id)
    {
        $this->group_question_id = $group_question_id;

        return $this;
    }

    public function getBankQuestionId()
    {
        return $this->bank_question_id;
    }

    public function setBankQuestionId($bank_question_id)
    {
        $this->bank_question_id = $bank_question_id;

        return $this;
    }
}
