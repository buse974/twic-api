<?php

namespace Application\Model;

use Application\Model\Base\SubmissionUser as BaseSubmissionUser;

class SubmissionUser extends BaseSubmissionUser
{
    protected $user;
      
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->user = $this->requireModel('app_model_user', $data);
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