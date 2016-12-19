<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Submission User
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\Role as ModelRole;

/**
 * Class SubmissionUser.
 */
class SubmissionUser extends AbstractService
{
    /**
     * Create Submission User, Add and remove do a diff.
     *
     * @param int   $submission_id
     * @param array $users
     *
     * @return bool if submission_user has be modifyer
     */
    public function create($submission_id, array $users)
    {
        $has_modif = false;
        $res_submission_user = $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
        foreach ($res_submission_user as $m_submission_user) {
            $is_present = false;
            foreach ($users as $k => $u) {
                if ($m_submission_user->getUserId() === $u) {
                    unset($users[$k]);
                    $is_present = true;
                    break;
                }
            }
            if ($is_present === false) {
                $has_modif = true;
                $this->getMapper()->delete($this->getModel()->setUserId($m_submission_user->getUserId())->setSubmissionId($submission_id));
            }
        }

        $ret = [];
        foreach ($users as $user) {
            $has_modif = true;
            $ret[$user] = $this->add($submission_id, $user);
        }

        return $has_modif;
    }

    /**
     * Add Submission User.
     *
     * @param int $submission_id
     * @param int $user_id
     *
     * @return int
     */
    public function add($submission_id, $user_id)
    {
        return $this->getMapper()->insert($this->getModel()->setSubmissionId($submission_id)->setUserId($user_id));
    }

    /**
     * Overwritten Grade.
     *
     * @param int $submission_id
     * @param int $grade
     *
     * @return int
     */
    public function OverwrittenGrade($submission_id, $grade)
    {
        if ($grade < 0 || !is_numeric($grade)) {
            $grade = 0;
        }

        return $this->getMapper()->update($this->getModel()->setGrade($grade)->setOverwritten(true), ['submission_id' => $submission_id]);
    }

    /**
     * Set Grade submission user.
     *
     * @invokable
     *
     * @param int  $submission_id
     * @param int  $user_id
     * @param int  $grade
     * @param bool $overwritten
     *
     * @return int
     */
    public function setGrade($submission_id, $user_id, $grade, $overwritten = false)
    {
        if ($grade < 0) {
            $grade = 0;
        }

        
        $ret_grade = $this->getMapper()->update($this->getModel()->setGrade($grade)->setOverwritten($overwritten), ['submission_id' => $submission_id, 'user_id' => $user_id]);
        if ($ret_grade) {
            $m_item = $this->getServiceItem()->getBySubmission($submission_id);
            $m_inst = $this->getServiceUser()->getListIdInstructorByItem($m_item->getId());
            $m_user = $this->getServiceUser()->getListIdBySubmission($submission_id);
            $miid = [];
            foreach (array_merge($m_inst, $m_user) as $instructor_id) {
                $miid[] = 'M'.$instructor_id;
            }
            
            $this->getServicePost()->addSys('SS'.$submission_id, '', [
                'state' => 'grade',
                'submission' => $submission_id,
                'course' => $m_item->getCourseId(),
                'item' => $m_item->getId(),
                'grade' => $grade
            ], 'grade', $miid/*sub*/,
                null/*parent*/,
                null/*page*/,
                null/*org*/,
                null/*user*/,
                $m_item->getCourseId()/*course*/,
                'submission');
        }

        return $ret_grade;
    }

    /**
     * Get Submission with user model.
     *
     * @param int $submission_id
     * @param int $user_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListBySubmissionId($submission_id, $user_id = null)
    {
        if (null === $user_id) {
            $identity = $this->getServiceUser()->getIdentity();
            if ($identity !== null) {
                $user_id = $this->getServiceUser()->getIdentity()['id'];
            }
        }

        return $this->getMapper()->getListBySubmissionId($submission_id, $user_id);
    }

    /**
     * Get Submission User  By Item.
     *
     * @param int item_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByItemId($item_id)
    {
        return $this->getMapper()->getListByItemId($item_id);
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
     * Get List Submission User.
     *
     * @param int $submission_id
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($submission_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
    }

    /**
     * Get List Grade Submission User.
     *
     * @invokable
     *
     * @param array  $avg
     * @param array  $filter
     * @param string $search
     *
     * @return array
     */
    public function getListGrade($avg = [], $filter = array(), $search = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        $user_id = $identity['id'];
        // Si c'est un étudient on force le filtre user a lui
        if (array_key_exists(ModelRole::ROLE_STUDENT_ID, $identity['roles'])) {
            $filter['user'] = $user_id;
        }

        $is_academic = (in_array(ModelRole::ROLE_ACADEMIC_STR, $identity['roles']));

        $mapper = $this->getMapper();
        $res_submission_user = $mapper->usePaginator($filter)->getListGrade($user_id, $avg, $filter, $search, $is_academic);

        return ['count' => $mapper->count(), 'list' => $res_submission_user];
    }

    /**
     * Submit Submission User.
     *
     * @param int $submission_id
     * @param int $user_id
     *
     * @return int
     */
    public function submit($submission_id, $user_id = null)
    {
        if (null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }

        $m_submission_user = $this->getModel()->setSubmitDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->update($m_submission_user, ['user_id' => $user_id, 'submission_id' => $submission_id]);
    }

    /**
     * Cancel a submit.
     *
     * @param int $submission_id
     * @param int $user_id
     *
     * @return int
     */
    public function cancelsubmit($submission_id, $user_id)
    {
        $ret = 0;
        $res_submission_user = $this->getMapper()->select($this->getModel()->setUserId($user_id)->setSubmissionId($submission_id));
        if ($res_submission_user->count() > 0) {
            $ret = $this->getMapper()->update($this->getModel()->setSubmitDate(new IsNull()), ['submission_id' => $submission_id]);
        }

        return $ret;
    }

    /**
     * Start the submission user.
     *
     * @invokable
     *
     * @param int $submission
     *
     * @return int
     */
    public function start($submission)
    {
        return $this->getMapper()->update($this->getModel()
            ->setStartDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), [
                'user_id' => $this->getServiceUser()->getIdentity()['id'],
                'submission_id' => $submission, 'start_date IS NULL',
            ]);
    }

    /**
     * End the submission user.
     *
     * @invokable
     *
     * @param int $submission
     *
     * @return int
     */
    public function end($submission)
    {
        return $this->getMapper()->update($this->getModel()
            ->setEndDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')), [
                'user_id' => $this->getServiceUser()->getIdentity()['id'],
                'submission_id' => $submission,
            ]);
    }

    /**
     * Check if is finish.
     *
     * @param int $submission_id
     *
     * @return bool
     */
    public function checkAllFinish($submission_id)
    {
        return $this->getMapper()->checkAllFinish($submission_id);
    }

    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }

    /**
     * Get Service Event.
     *
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->container->get('app_service_event');
    }
    
    /**
     * Get Service Item.
     *
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->container->get('app_service_item');
    }
    
    /**
     * Get Service Post
     *
     * @return \Application\Service\Post
     */
    private function getServicePost()
    {
        return $this->container->get('app_service_post');
    }
}
