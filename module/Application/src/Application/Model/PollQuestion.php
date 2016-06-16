<?php

namespace Application\Model;

use Application\Model\Base\PollQuestion as BasePollQuestion;

class PollQuestion extends BasePollQuestion
{
    protected $poll_question_items;

    public function getPollQuestionItems()
    {
        return $this->poll_question_items;
    }

    public function setPollQuestionItems($poll_question_items)
    {
        $this->poll_question_items = $poll_question_items;

        return $this;
    }
}
