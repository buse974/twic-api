<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class PgUserGrade extends AbstractService
{
    
    /**
     *
     * @param integer $user
     * @param integer $submission
     */
    public function deleteByUserAndSubmission($user, $submission)
    {
        return  $this->getMapper()->delete($this->getModel()->setPgId($user)->setSubmissionId($submission));
    }
    
      /**
     *
     * @param integer $submission
     */
    public function getProcessedGrades($submission)
    {
        return  $this->getMapper()->getProcessedGrades($submission);
    }
    
     /**
     * 
     * @param integer $submission
     * 
     */
    public function getListBySubmission($submission){
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission));
    }
    
     /**
     *
     * @param integer $pg
     * @param integer $user
     * @param integer $points
     */
    public function add($pg, $user, $submission, $grade)
    {
        return  $this->getMapper()->insert($this->getModel()->setPgId($pg)->setUserId($user)->setSubmissionId($submission)->setGrade($grade));
    }
    
    
}