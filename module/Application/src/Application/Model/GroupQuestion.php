<?php

namespace Application\Model;

use Application\Model\Base\GroupQuestion as BaseGroupQuestion;

class GroupQuestion extends BaseGroupQuestion
{
    protected $bank_question;

    public function getBankQuestion()
    {
        return $this->bank_question;
    }

    public function setBankQuestion($bank_question)
    {
        $this->bank_question = $bank_question;

        return $this;
    }
}
