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
     * 
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}