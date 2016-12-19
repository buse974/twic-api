<?php
namespace Application\Model;

use Application\Model\Base\Course as BaseCourse;

class Course extends BaseCourse
{
    protected $creator;

    protected $grading;

    protected $grading_policy;

    protected $module;

    protected $users;

    protected $student;

    protected $instructor;

    protected $items;

    protected $school;

    protected $school_id;

    protected $program;

    protected $avg;

    protected $nbr_course;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);
        
        $this->grading_policy = $this->requireModel('app_model_grading_policy', $data);
        $this->grading = $this->requireModel('app_model_grading', $data);
        $this->creator = $this->requireModel('app_model_user', $data);
        $this->school = $this->requireModel('app_model_school', $data);
        $this->program = $this->requireModel('app_model_program', $data);
    }

    public function getNbrCourse()
    {
        return $this->nbr_course;
    }

    public function setNbrCourse($nbr_course)
    {
        $this->nbr_course = $nbr_course;
        
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

    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram($program)
    {
        $this->program = $program;
        
        return $this;
    }

    public function getSchool()
    {
        return $this->school;
    }

    public function setSchool($school)
    {
        $this->school = $school;
        
        return $this;
    }

    public function getSchoolId()
    {
        return $this->school_id;
    }
    
    public function setSchoolId($school_id)
    {
        $this->school_id = $school_id;
    
        return $this;
    }
    
    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
        
        return $this;
    }

    public function setStudent($student)
    {
        $this->student = $student;
        
        return $this;
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function setInstructor($instructor)
    {
        $this->instructor = $instructor;
        
        return $this;
    }

    public function getInstructor()
    {
        return $this->instructor;
    }

    public function setUsers($users)
    {
        $this->users = $users;
        
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
        
        return $this;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setGrading($grading)
    {
        $this->grading = $grading;
        
        return $this;
    }

    public function getGrading()
    {
        return $this->grading;
    }

    public function setGradingPolicy($grading_policy)
    {
        $this->grading_policy = $grading_policy;
        
        return $this;
    }

    public function getGradingPolicy()
    {
        return $this->grading_policy;
    }
}
