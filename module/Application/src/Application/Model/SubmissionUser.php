<?php

namespace Application\Model;

use Application\Model\Base\SubmissionUser as BaseSubmissionUser;

class SubmissionUser extends BaseSubmissionUser
{
    protected $user;
    protected $program;
    protected $course;
    protected $avg;
    protected $letter;

    public function exchangeArray(array &$data)
    {
        $this->user = $this->requireModel('app_model_user', $data);
        $this->program = $this->requireModel('app_model_program', $data);
        $this->course = $this->requireModel('app_model_course', $data);
        
        parent::exchangeArray($data);
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
      
    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    public function getAvg()
    {
        return $this->avg;
    }

    public function setAvg($avg)
    {
        $this->avg = $avg;

        return $this;
    }
}