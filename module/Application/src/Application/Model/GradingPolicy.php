<?php

namespace Application\Model;

use Application\Model\Base\GradingPolicy as BaseGradingPolicy;

class GradingPolicy extends BaseGradingPolicy
{
    const GP_INDIVIDUAL_ASSIGNEMENT = 'IA';
    const GP_CAPSTONE_PROJECT = 'CP';
    const GP_LIVE_CLASS = 'LC';
    const GP_WORKGROUP = 'WG';

    protected $items;
    protected $criterias;
    protected $nbr_comment;
    protected $processed_grade;
    protected $grading_policy_grade;

    public function exchangeArray(array &$data)
    {
        if ($this->isRepeatRelational()) {
            return;
        }

        parent::exchangeArray($data);

        $this->grading_policy_grade = $this->requireModel('app_model_grading_policy_grade', $data);
        $this->criterias = $this->requireModel('app_model_criteria', $data);
    }

    public function getCriterias()
    {
        return $this->criterias;
    }

    public function setCriterias($criterias)
    {
        $this->criterias = $criterias;

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

    public function getNbrComment()
    {
        return $this->nbr_comment;
    }

    public function setNbrComment($nbr_comment)
    {
        $this->nbr_comment = $nbr_comment;

        return $this;
    }

    public function getProcessedGrade()
    {
        return $this->processed_grade;
    }

    public function setProcessedGrade($processed_grade)
    {
        $this->processed_grade = $processed_grade;

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
