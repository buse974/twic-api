<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class PgUserCriteria extends AbstractService
{
    
    /**
     *
     * @param integer $pg
     * @param integer $user
     * @param integer $criteria
     * @param integer $points
     */
    public function add($pg, $user, $submission, $criteria, $points)
    {
        return  $this->getMapper()->insert($this->getModel()->setPgId($pg)->setUserId($user)->setSubmissionId($submission)->setCriteriaId($criteria)->setPoints($points));
    }
    
    
     /**
     *
     * @param integer $user
     * @param integer $submission
     */
    public function deleteByUserAndSubmission($user, $submission)
    {
        return  $this->getMapper()->delete($this->getModel()->setPgId($user)->setSubmissionId($submission));
    }
    
}