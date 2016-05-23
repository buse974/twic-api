<?php

namespace Application\Model;

use Application\Model\Base\BankQuestionType as BaseBankQuestionType;

class BankQuestionType extends BaseBankQuestionType
{
    const TYPE_TEXT_INT = 1;
    const TYPE_TEXT_STR = 'text';
    
    const TYPE_MULTIPLE_CHOICE_INT = 2;
    const TYPE_MULTIPLE_CHOICE_STR = 'multiple_choice';
    
    const TYPE_CHECKBOX_INT = 3;
    const TYPE_CHECKBOX_STR = 'checkbox';
    
    const TYPE_DATE_INT = 4;
    const TYPE_DATE_STR = 'date';
    
    const TYPE_TIME_INT = 5;
    const TYPE_TIME_STR = 'time';
    
}