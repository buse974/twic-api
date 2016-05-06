<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubQuestion extends AbstractService
{
    /**
     * @param integer $sub_quiz_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListLite($sub_quiz_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubQuizId($sub_quiz_id));
    }
    
    public function add($sub_quiz_id, $poll_item_id, $bank_question_id, $group_question_id)
    {
        $m_sub_question = $this->getModel()
            ->setSubQuizId($sub_quiz_id)
            ->setPollItemId($poll_item_id)
            ->setBankQuestionId($bank_question_id)
            ->setGroupQuestionId($group_question_id);

        $this->getMapper()->insert($m_sub_question);
        
        return $this->getMapper()->getLastInsertValue();
    }
}