<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Paire grader User Criteria
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class PgUserCriteria
 */
class PgUserCriteria extends AbstractService
{

    /**
     * Add peer criteria
     * 
     * @param int $pg_id          
     * @param int $user_id    
     * @param int $submission_id      
     * @param int $criteria_id          
     * @param int $points     
     * @return int       
     */
    public function add($pg_id, $user_id, $submission_id, $criteria_id, $points)
    {
        return $this->getMapper()->insert($this->getModel()
            ->setPgId($pg_id)
            ->setUserId($user_id)
            ->setSubmissionId($submission_id)
            ->setCriteriaId($criteria_id)
            ->setPoints($points));
    }

    /**
     * Get List peer criteria
     * 
     * @param int $submission_id 
     * @return \Dal\Db\ResultSet\ResultSet        
     */
    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setSubmissionId($submission_id));
    }

    /**
     * Get Processed Grades
     * 
     * @param int $submission_id
     * @param int $user_id     
     * @return \Dal\Db\ResultSet\ResultSet      
     */
    public function getProcessedGrades($submission_id, $user_id = null)
    {
        return $this->getMapper()->getProcessedGrades($submission_id, $user_id);
    }

    /**
     * Delete peer criteria
     *
     * @param int $user_id            
     * @param int $submission_id    
     * @return int        
     */
    public function deleteByUserAndSubmission($user_id, $submission_id)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setPgId($user_id)
            ->setSubmissionId($submission_id));
    }
}
