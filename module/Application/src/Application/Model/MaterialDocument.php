<?php

namespace Application\Model;

use Application\Model\Base\MaterialDocument as BaseMaterialDocument;

class MaterialDocument extends BaseMaterialDocument
{
    protected $user;
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
    
    public function getUser() 
    {
        return $this->user;
    }
     
    public function setUser($user) 
    {
        $this->user = $user;
        
        return $this;
    }
}
