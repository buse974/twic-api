<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class QuestionnaireQuestion extends AbstractModel
{
    protected $id;
    protected $question_id;
    protected $questionnaire_id;

    protected $prefix = 'questionnaire_question';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getQuestionId()
    {
        return $this->question_id;
    }

    public function setQuestionId($question_id)
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getQuestionnaireId()
    {
        return $this->questionnaire_id;
    }

    public function setQuestionnaireId($questionnaire_id)
    {
        $this->questionnaire_id = $questionnaire_id;

        return $this;
    }
}
