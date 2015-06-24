<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class GradingPolicyGrade extends AbstractModel
{
    protected $id;
    protected $grading_policy_id;
    protected $grade;
    protected $created_date;

    protected $prefix = 'grading_policy_grade';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

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

    public function getGrade()
    {
        return $this->grade;
    }

    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;

        return $this;
    }
}
