<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class PgUserCriteria extends AbstractService
{
    
    /**
     *
     * @param integer $user
     * @param integer $criteria
     * @param integer $points
     */
    public function add($user, $submission, $criteria, $points)
    {
        return  $this->getMapper()->insert($this->getModel()->setUserId($user)->setSubmissionId($submission)->setCriteriaId($criteria)->setPoints($points));
    }
    
    
     /**
     *
     * @param integer $user
     * @param integer $submission
     */
    public function deleteByUserAndSubmission($user, $submission)
    {
        return  $this->getMapper()->delete($this->getModel()->setUserId($user)->setSubmissionId($submission));
    }
    
}