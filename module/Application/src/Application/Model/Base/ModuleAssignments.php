<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ModuleAssignments extends AbstractModel
{
    protected $id;
    protected $question;
    protected $duration;
    protected $ratio;
    protected $module_id;

    protected $prefix = 'module_assignments';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRatio()
    {
        return $this->ratio;
    }

    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    public function getModuleId()
    {
        return $this->module_id;
    }

    public function setModuleId($module_id)
    {
        $this->module_id = $module_id;

        return $this;
    }
}
