<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Symfony\Component\Console\Application;
use Zend\Db\Sql\Predicate\IsNull;

class SubmissionUser extends AbstractService
{
    public function create($submission_id, array $users)
    {
        $res_submission_user = $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
        foreach ($res_submission_user as $m_submission_user) {
            $is_present = false;
            foreach ($users as $k => $u) {
                if($m_submission_user->getUserId() === $u) {
                    unset($users[$k]);
                    $is_present = true;
                    break;
                }
            }
            if($is_present===false) {
                $this->getMapper()->delete($this->getModel()->setUserId($m_submission->getUserId())->setSubmissionId($submission_id));
            }
        }
        
        $ret = [];
        foreach ($users as $user) {
             $ret[$user] = $this->getMapper()->insert($this->getModel()->setSubmissionId($submission_id)->setUserId($user));
        }
        
        return $ret;
    }
    
    public function OverwrittenGrade($submission_id, $grade)
    {
        $return = $this->getMapper()->update($this->getModel()->setGrade($grade)->setOverwritten(true), ['submission_id' => $submission_id]);
                
        $this->getServiceGradingPolicyGrade()->process($submission_id);
        return $return;
    }
    
     /**
     * @invokable
     *
     * @param int  $submission_id
     * @param int  $user_id
     * @param int  $grade
     * @param bool  $overwritten
     */
    public function setGrade($submission_id, $user_id, $grade, $overwritten = false)
    {
        $return = $this->getMapper()->update($this->getModel()->setGrade($grade)->setOverwritten($overwritten), ['submission_id' => $submission_id, 'user_id' => $user_id]); 
        
        $this->getServiceGradingPolicyGrade()->process($submission_id, $user_id);
        return $return;
    }
    
    public function getListBySubmissionId($submission_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        return $this->getMapper()->getListBySubmissionId($submission_id, $me);
    }
    
    public function getProcessedGrades($submission_id)
    {
        return $this->getMapper()->getProcessedGrades($submission_id);
    }
    
    public function getList($submission_id)
    {
        return $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
    }
    
       /**
     * @invokable
     *
     * @param array  $avg
     * @param array  $filter
     * @param string $search
     */
    public function getListGrade($avg = array(), $filter = array(), $search = null)
    {
        $mapper = $this->getMapper();
        $res_submission_user = $mapper->usePaginator($filter)->getListGrade($avg, $filter, $search, $this->getServiceUser()->getIdentity());

        return ['count' => $mapper->count(),'list' => $res_submission_user];
    }
    
    /**
     * @param integer $submission_id
     * @param integer $user_id
     * 
     * @return integer
     */
    public function submit($submission_id, $user_id = null)
    {
        if(null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $m_submission_user = $this->getModel()->setSubmitDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->update($m_submission_user, ['user_id' => $user_id, 'submission_id' => $submission_id]);
    }
    
    /**
     * @param integer $submission_id
     * @param integer $user_id
     */
    public function cancelsubmit($submission_id, $user_id)
    {
        $ret = 0;
        $res_submission_user = $this->getMapper()->select($this->getModel()->setUserId($user_id)->setSubmissionId($submission_id));
        if($res_submission_user->count() > 0) {
            $ret = $this->getMapper()->update($this->getModel()->setSubmitDate(new IsNull()), ['submission_id' => $submission_id]);
        }
            
        return $ret;
    }
    
     /**
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
                'submission_id' => $submission, 'start_date IS NULL'
            ]);
    }

    /**
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
                'submission_id' => $submission
            ]);
    }
    
    /**
     * @param int $submission
     *
     * @return bool
     */
    public function checkAllFinish($submission)
    {
        return $this->getMapper()->checkAllFinish($submission);
    }
    
    /**
     * 
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
        /**
     * 
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
    
        /**
     * 
     * @return \Application\Service\GradingPolicyGrade
     */
    public function getServiceGradingPolicyGrade()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy_grade');
    }
}