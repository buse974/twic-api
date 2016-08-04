<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Submission User Criteria
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubmissionUserCriteria.
 */
class SubmissionUserCriteria extends AbstractService
{
    /**
     * Add Submission User Criteria.
     * 
     * @param int  $submission_id
     * @param int  $user_id
     * @param int  $criteria_id
     * @param int  $points
     * @param bool $overwritten
     *
     * @return int
     */
    public function add($submission_id, $user_id, $criteria_id, $points, $overwritten = false)
    {
        $m_sbm_user_criteria = $this->getModel()->setSubmissionId($submission_id)->setUserId($user_id)->setCriteriaId($criteria_id);
        $this->getMapper()->delete($m_sbm_user_criteria);

        return $this->getMapper()->insert($m_sbm_user_criteria->setPoints($points)->setOverwritten($overwritten));
    }

    /**
     * Delete Submission User Criteria By Submission.
     * 
     * @param int $submission_id
     *
     * @return int
     */
    public function deleteBySubmission($submission_id)
    {
        return $this->getMapper()->delete($this->getModel()->setSubmissionId($submission_id));
    }

    /**
     * Get Processed Grades.
     * 
     * @param int $submission_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getProcessedGrades($submission_id)
    {
        return $this->getMapper()->getProcessedGrades($submission_id);
    }

    /**
     * Get List By Submission.
     * 
     * @invokable
     *
     * @param int $submission
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission));
    }
}
