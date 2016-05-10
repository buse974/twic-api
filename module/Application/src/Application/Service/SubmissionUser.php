<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Symfony\Component\Console\Application;
use Zend\Db\Sql\Predicate\IsNull;

class SubmissionUser extends AbstractService
{
    public function create($submission_id, array $users)
    {
        $ret = [];
        foreach ($users as $user) {
             $ret[$user] = $this->getMapper()->insert($this->getModel()->setSubmissionId($submission_id)->setUserId($user));
        }
        
        return $ret;
    }
    
    public function OverwrittenGrade($submission_id, $grade)
    {
        return $this->getMapper()->update($this->getModel()->setGrade($grade)->setOverwritten(true), ['submission_id' => $submission_id]);
    }
    
    
    
    public function setGrade($submission_id, $user_id, $grade)
    {
        $r = $this->getMapper()->update($this->getModel()->setGrade($grade), ['submission_id' => $submission_id, 'user_id' => $user_id]);
        
    }
    
    public function getListBySubmissionId($submission_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        return $this->getMapper()->getListBySubmissionId($submission_id, $me);
    }
    
    /**
     * @param integer $submission_id
     * @param integer $user_id
     * @return integer
     */
    public function submit($submission_id, $user_id)
    {
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
}