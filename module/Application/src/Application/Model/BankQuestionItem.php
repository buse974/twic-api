<?php

namespace Application\Model;

use Application\Model\Base\BankQuestionItem as BaseBankQuestionItem;

class BankQuestionItem extends BaseBankQuestionItem
{
    protected $bank_answer_item;
      
    public function getBankAnswerItem() 
    {
        return $this->bank_answer_item;
    }
    
    public function setBankAnswerItem($bank_answer_item) 
    {
        $this->bank_answer_item = $bank_answer_item;
        
        return $this;
    }
}