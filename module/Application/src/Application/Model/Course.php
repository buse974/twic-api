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

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->grading_policy = new GradingPolicy($this);
        $this->grading = new Grading($this);
        $this->creator = new User($this);
        $this->module = new Module($this);

        $this->module->exchangeArray($data);
        $this->grading_policy->exchangeArray($data);
        $this->grading->exchangeArray($data);
        $this->creator->exchangeArray($data);
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
}
