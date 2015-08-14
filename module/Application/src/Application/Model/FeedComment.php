<?php

namespace Application\Model;

use Application\Model\Base\FeedComment as BaseFeedComment;

class FeedComment extends BaseFeedComment
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
