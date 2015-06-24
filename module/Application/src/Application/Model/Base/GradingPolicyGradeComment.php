<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class GradingPolicyGradeComment extends AbstractModel
{
    protected $id;
    protected $text;
    protected $user_id;
    protected $grading_policy_grade_id;
    protected $created_date;

    protected $prefix = 'grading_policy_grade_comment';

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getGradingPolicyGradeId()
    {
        return $this->grading_policy_grade_id;
    }

    public function setGradingPolicyGradeId($grading_policy_grade_id)
    {
        $this->grading_policy_grade_id = $grading_policy_grade_id;

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
