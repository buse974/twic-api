<?php

namespace Application\Model;

use Application\Model\Base\Item as BaseItem;

class Item extends BaseItem
{
    const TYPE_LIVE_CLASS = 'LC';
    const TYPE_WORKGROUP = 'WG';
    const TYPE_INDIVIDUAL_ASSIGMENT = 'IA';
    const TYPE_CAPSTONE_PROJECT = 'CP';

    protected $materials;
    protected $module;
    protected $program;
    protected $course;
    protected $item_prog;
    protected $item_assignment;
    protected $users;
    protected $item_grade;
    protected $new_message;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->module = new Module($this);
        $this->program = new Program($this);
        $this->course = new Course($this);
        $this->item_prog = new ItemProg($this);
        $this->item_assignment = new ItemAssignment($this);
        $this->item_grade = new ItemGrading($this);

        $this->module->exchangeArray($data);
        $this->program->exchangeArray($data);
        $this->course->exchangeArray($data);
        $this->item_prog->exchangeArray($data);
        $this->item_assignment->exchangeArray($data);
        $this->item_grade->exchangeArray($data);
    }

    public function setMaterials($materials)
    {
        $this->materials = $materials;

        return $this;
    }

    public function getMaterials()
    {
        return $this->materials;
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

    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    public function getProgram()
    {
        return $this->program;
    }

    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function setItemProg($item_prog)
    {
        $this->item_prog = $item_prog;

        return $this;
    }

    public function getItemProg()
    {
        return $this->item_prog;
    }

    public function setItemAssignment($item_assignment)
    {
        $this->item_assignment = $item_assignment;

        return $this;
    }

    public function getItemAssignment()
    {
        return $this->item_assignment;
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

    public function setItemGrade($item_grade)
    {
        $this->item_grade = $item_grade;

        return $this;
    }

    public function getItemGrade()
    {
        return $this->item_grade;
    }

    public function setNewMessage($new_message)
    {
        $this->new_message = $new_message;

        return $this;
    }

    public function getNewMessage()
    {
        return $this->new_message;
    }
}
