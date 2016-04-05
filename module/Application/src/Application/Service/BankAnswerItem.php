<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class BankAnswerItem extends AbstractService
{
    /**
     * @param integer $bank_question_item_id
     * @param integer $percent
     * @param string $answer
     * 
     * @return integer
     */
    public function add($bank_question_item_id, $percent, $answer)
    {
        $m_bank_answer_item = $this->getModel()
            ->setBankQuestionItemId($bank_question_item_id)
            ->setPercent($percent)
            ->setAnswer($answer);
        
        return $this->getMapper()->insert($m_bank_answer_item);
    }
    
    public function get($bank_question_item_id)
    {
        $res_bank_answer_item = $this->getMapper()->select($this->getModel()->setBankQuestionItemId($bank_question_item_id));
        
        return ($res_bank_answer_item->count() > 0) ? $res_bank_answer_item->current():null;
    }
}