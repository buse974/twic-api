<?php

namespace Application\Model;

use Application\Model\Base\ThreadMessage as BaseThreadMessage;

class ThreadMessage extends BaseThreadMessage
{
    protected $user;
    protected $thread;

  
    

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->user = new User($this);
        $this->thread = new Thread($this);
        
        $this->thread->exchangeArray($data);
        $this->user->exchangeArray($data);
    }

    public function getThread()
    {
        return $this->thread;
    }
    
    public function setThread($thread)
    {
        $this->thread = $thread;
    
        return $this;
    }
    
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
