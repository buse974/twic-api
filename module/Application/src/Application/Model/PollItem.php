<?php

namespace Application\Model;

use Application\Model\Base\PollItem as BasePollItem;

class PollItem extends BasePollItem
{
    protected $group_question;
      
    public function getGroupQuestion() 
    {
        return $this->group_question;
    }
    
    public function setGroupQuestion($group_question) 
    {
        $this->group_question = $group_question;
        
        return $this;
    }
}