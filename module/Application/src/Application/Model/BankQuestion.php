<?php

namespace Application\Model;

use Application\Model\Base\BankQuestion as BaseBankQuestion;

class BankQuestion extends BaseBankQuestion
{
    protected $bank_question_media;
    protected $bank_question_item;
    protected $bank_question_tag;

    public function getBankQuestionTag()
    {
        return $this->bank_question_tag;
    }

    public function setBankQuestionTag($bank_question_tag)
    {
        $this->bank_question_tag = $bank_question_tag;

        return $this;
    }

    public function getBankQuestionItem()
    {
        return $this->bank_question_item;
    }

    public function setBankQuestionItem($bank_question_item)
    {
        $this->bank_question_item = $bank_question_item;

        return $this;
    }

    public function getBankQuestionMedia()
    {
        return $this->bank_question_media;
    }

    public function setBankQuestionMedia($bank_question_media)
    {
        $this->bank_question_media = $bank_question_media;

        return $this;
    }
}
