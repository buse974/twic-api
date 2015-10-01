<?php

namespace Application\Model;

use Application\Model\Base\QuestionnaireUser as BaseQuestionnaireUser;

class QuestionnaireUser extends BaseQuestionnaireUser
{
    protected $answers;

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }
}
