<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubmissionUserCriteria extends AbstractService
{
    
    
     /**
     * 
     * @param integer $submission
     * @param integer $user
     * @param integer $criteria
     * @param integer $points
     * 
     */
    public function add($submission, $user, $criteria, $points){
        $m_sbm_user_criteria = $this->getModel()->setSubmissionId($submission)->setUserId($user)->setCriteriaId($criteria);
        $this->getMapper()->delete($m_sbm_user_criteria);
        return $this->getMapper()->insert($m_sbm_user_criteria->setPoints($points));
    }
    
    /**
     * 
     * @param integer $submission
     * 
     */
    public function getProcessedGrades($submission){
        return $this->getMapper()->getProcessedGrades($submission);
    }
    
     /**
     * @invokable
     *
     * @param int $submission
     */
    public function getListBySubmission($submission)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission));
    }
}