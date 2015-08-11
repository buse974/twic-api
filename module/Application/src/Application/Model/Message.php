<?php

namespace Application\Model;

use Application\Model\Base\Message as BaseMessage;

class Message extends BaseMessage
{
    protected $document;

    public function getDocument() 
    {
        return $this->document;
    }

    public function setDocument($document) 
    {
        $this->document = $document;
        
        return $this;
    }
}
