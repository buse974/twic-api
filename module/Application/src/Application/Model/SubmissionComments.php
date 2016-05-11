<?php

namespace Application\Model;

use Application\Model\Base\SubmissionComments as BaseSubmissionComments;

class SubmissionComments extends BaseSubmissionComments
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