<?php

namespace Application\Model;

use Application\Model\Base\Submission as BaseSubmission;

class Submission extends BaseSubmission
{
    protected $chat;
      
    public function getPropertyName() 
    {
        return $this->chat;
    }
    
    public function setPropertyName($chat) 
    {
        $this->chat = $chat;
        
        return $this;
    }
}