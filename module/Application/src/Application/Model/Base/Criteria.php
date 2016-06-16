<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Criteria extends AbstractModel
{
    protected $id;
    protected $name;
    protected $points;
    protected $description;
    protected $grading_policy_id;

    protected $prefix = 'criteria';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getGradingPolicyId()
    {
        return $this->grading_policy_id;
    }

    public function setGradingPolicyId($grading_policy_id)
    {
        $this->grading_policy_id = $grading_policy_id;

        return $this;
    }
}
