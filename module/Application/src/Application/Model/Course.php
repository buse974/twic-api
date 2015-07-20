<?php

namespace Application\Model;

use Application\Model\Base\Course as BaseCourse;

class Course extends BaseCourse
{
    protected $material_document;
    protected $creator;
    protected $grading;
    protected $grading_policy;
    protected $module;
    protected $users;
    protected $student;
    protected $instructor;
    protected $start_date;
    protected $end_date;
    protected $items;
    protected $school;
    protected $program;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->grading_policy = new GradingPolicy($this);
        $this->grading = new Grading($this);
        $this->creator = new User($this);
        $this->module = new Module($this);
        $this->school = new School($this);
        $this->program = new Program($this);

        $this->program->exchangeArray($data);
        $this->module->exchangeArray($data);
        $this->grading_policy->exchangeArray($data);
        $this->grading->exchangeArray($data);
        $this->creator->exchangeArray($data);
        $this->school->exchangeArray($data);
        
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

    public function setMaterialDocument($material_document)
    {
        $this->material_document = $material_document;

        return $this;
    }

    public function getMaterialDocument()
    {
        return $this->material_document;
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

    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }
}
