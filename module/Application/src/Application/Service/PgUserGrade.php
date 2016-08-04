<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Peer grader User Grade
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class PgUserGrade.
 */
class PgUserGrade extends AbstractService
{
    /**
     * Delete Peer grader User Grade by user and submission.
     *
     * @param int $user
     * @param int $submission
     *
     * @return bool
     */
    public function deleteByUserAndSubmission($user, $submission)
    {
        return $this->getMapper()->delete($this->getModel()
            ->setPgId($user)
            ->setSubmissionId($submission));
    }

    /**
     * Get Peer grader User Grade by submission.
     *
     * @param int $submission_id
     *
     * @return Dal\Db\ResultSet\ResultSet
     */
    public function getProcessedGrades($submission_id)
    {
        return $this->getMapper()->getProcessedGrades($submission_id);
    }

    /**
     * Get List By Submission.
     *
     * @param int $submission_id
     *
     * @return Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmission($submission_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setSubmissionId($submission_id));
    }

    /**
     * Add Grade.
     *
     * @param int $pg_id
     * @param int $user_id
     * @param int $submission_id
     * @param int $grade
     *
     * @return int
     */
    public function add($pg_id, $user_id, $submission_id, $grade)
    {
        return $this->getMapper()->insert($this->getModel()
            ->setPgId($pg_id)
            ->setUserId($user_id)
            ->setSubmissionId($submission_id)
            ->setGrade($grade));
    }
}
