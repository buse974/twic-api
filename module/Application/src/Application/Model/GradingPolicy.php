<?php

namespace Application\Model;

use Application\Model\Base\GradingPolicy as BaseGradingPolicy;

class GradingPolicy extends BaseGradingPolicy
{
    protected $items;
    protected $nbr_comment;
    protected $grading_policy_grade;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);
        $this->grading_policy_grade = new GradingPolicyGrade($this);
        $this->grading_policy_grade->exchangeArray($data);
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

    public function getNbrComment()
    {
        return $this->nbr_comment;
    }

    public function setNbrComment($nbr_comment)
    {
        $this->nbr_comment = $nbr_comment;

        return $this;
    }

    public function getGradingPolicyGrade()
    {
        return $this->grading_policy_grade;
    }

    public function setGradingPolicyGrade($grading_policy_grade)
    {
        $this->grading_policy_grade = $grading_policy_grade;

        return $this;
    }
}
