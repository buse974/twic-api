<?php

namespace Application\Model;

use Application\Model\Base\Submission as BaseSubmission;

class Submission extends BaseSubmission
{
    protected $chat;
    protected $submission_user;
    protected $videoconf_archives;
    protected $users;
      
    public function getUsers() 
    {
        return $this->users;
    }
    
    public function setUsers($users) 
    {
        $this->users = $users;
        
        return $this;
    }
      
    public function getVideoconfArchives() 
    {
        return $this->videoconf_archives;
    }
    
    public function setVideoconfArchives($videoconf_archives) 
    {
        $this->videoconf_archives = $videoconf_archives;
        
        return $this;
    }
      
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