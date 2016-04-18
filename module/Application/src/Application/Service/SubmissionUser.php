<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Symfony\Component\Console\Application;

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
     * 
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}