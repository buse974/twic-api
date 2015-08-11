<?php

namespace Application\Model;

use Application\Model\Base\Feed as BaseFeed;

class Feed extends BaseFeed
{
    protected $user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->user = new User($this);
        
        $this->user->exchangeArray($data);
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
