<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class PollAnswerItems extends AbstractService
{

    public function add($poll_answer, $poll_question_item = null, $answer = null, $date = null, $time = null)
    {
        $m_poll_answer_item = $this->getModel()
            ->setPollAnswerId($poll_answer)
            ->setPollQuestionItemId($poll_question_item)
            ->setAnswer($answer)
            ->setDate($date)
            ->setTime($time);
        
        return $this->getMapper()->insert($m_poll_answer_item);
    }
}