<?php

namespace Application\Model;

use Application\Model\Base\Questionnaire as BaseQuestionnaire;

class Questionnaire extends BaseQuestionnaire
{
    protected $questions;
    protected $nb_no_completed;

    public function getNbNoCompleted() 
    {
        return $this->nb_no_completed;
    }

    public function setNbNoCompleted($nb_no_completed) 
    {
        $this->nb_no_completed = $nb_no_completed;
        
        return $this;
    }
    
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