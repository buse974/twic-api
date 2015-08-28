<?php

namespace Application\Model;

use Application\Model\Base\QuestionnaireQuestion as BaseQuestionnaireQuestion;

class QuestionnaireQuestion extends BaseQuestionnaireQuestion
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