<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ModuleQuestion extends AbstractModel
{
    protected $id;
    protected $title;
    protected $question;
    protected $time;
    protected $module_id;
    protected $desired;

    protected $prefix = 'module_question';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

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

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;

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

    public function getDesired()
    {
        return $this->desired;
    }

    public function setDesired($desired)
    {
        $this->desired = $desired;

        return $this;
    }
}
