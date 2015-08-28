<?php

namespace Application\Model;

use Application\Model\Base\Questionnaire as BaseQuestionnaire;

class Questionnaire extends BaseQuestionnaire
{
    protected $questions;
    
    public function getQuestions()
    {
        return $this->questions;
    }
    
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    
        return $this;
    }
}