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
    protected $avg;
    protected $item_prog;
    protected $nbr_course;
    
    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->grading_policy = $this->requireModel('app_model_grading_policy', $data);
        $this->grading = $this->requireModel('app_model_grading', $data);
        $this->creator = $this->requireModel('app_model_user', $data);
        $this->module = $this->requireModel('app_model_module', $data);
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

    public function getItemProg()
    {
        return $this->item_prog;
    }

    public function setItemProg($item_prog)
    {
        $this->item_prog = $item_prog;

        return $this;
    }
}
