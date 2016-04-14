<?php

namespace Application\Model;

use Application\Model\Base\Submission as BaseSubmission;

class Submission extends BaseSubmission
{
    protected $chat;
    protected $submission_user;
      
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
    
        $this->submission_user = $this->requireModel('app_model_submission_user', $data);
    }
    
    public function getSubmissionUser() 
    {
        return $this->submission_user;
    }
    
    public function setSubmissionUser($submission_user) 
    {
        $this->submission_user = $submission_user;
        
        return $this;
    }
      
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