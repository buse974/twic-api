<?php

namespace Application\Model;

use Application\Model\Base\Message as BaseMessage;

class Message extends BaseMessage
{
    protected $document;
    protected $to;
    protected $from;
    
    public function getFrom() 
    {
        return $this->from;
    }
     
    public function setFrom($from) 
    {
        $this->from = $from;
        
        return $this;
    }

    public function getDocument() 
    {
        return $this->document;
    }

    public function setDocument($document) 
    {
        $this->document = $document;
        
        return $this;
    }
    
    public function getTo()
    {
        return $this->to;
    }
    
    public function setTo($to)
    {
        $this->to = $to;
    
        return $this;
    }
}
