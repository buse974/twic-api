<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Submission Comments
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubmissionComments.
 */
class SubmissionComments extends AbstractService
{
    /**
     * Get List Submission Comments.
     *
     * @invokable
     *
     * @param int $submission
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($submission)
    {
        return $this->getMapper()->getList($submission);
    }

    /**
     * Get Submission Comments.
     *
     * @invokable
     *
     * @param int $id
     *
     * @return \Application\Model\SubmissionComments
     */
    public function get($id)
    {
        return $this->getMapper()
            ->get($id)
            ->current();
    }

    /**
     * Add Submission Comments.
     *
     * @invokable
     *
     * @param int    $submission_id
     * @param int    $user_id
     * @param string $file_name
     * @param string $file_token
     * @param string $audio
     * @param string $text
     *
     * @return \Application\Model\SubmissionComments
     */
    public function add($submission_id, $user_id, $file_name, $file_token, $audio, $text)
    {
        $this->getMapper()->insert(
            $this->getModel()
                ->setSubmissionId($submission_id)
                ->setUserId($user_id)
                ->setFileName($file_name)
                ->setFileToken($file_token)
                ->setAudio($audio)
                ->setText($text)
        );

        return $this->get(
            $this->getMapper()
                ->getLastInsertValue()
        );
    }
}
