<?php

namespace Application\Model;

use Application\Model\Base\Poll as BasePoll;

class Poll extends BasePoll
{
    protected $poll_questions;

    public function getPollQuestions() 
    {
        return $this->poll_questions;
    }

    public function setPollQuestions($poll_questions) 
    {
        $this->poll_questions = $poll_questions;
        
        return $this;
    }
}