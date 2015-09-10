<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicyGrade extends AbstractService
{
    /**
     * @invokable
     *
     * @param array  $avg
     * @param array  $filter
     * @param string $search
     */
    public function getList($avg = array(), $filter = array(), $search = null)
    {
        $mapper = $this->getMapper();
        $res_gradingpolicygrade = $mapper->usePaginator($filter)->getList($avg, $filter, $search, $this->getServiceUser()->getIdentity());

        return array('count' => $mapper->count(),'list' => $res_gradingpolicygrade);
    }

    /**
     * @param int $grading_policy
     */
    public function deleteFromGradingPolicy($grading_policy)
    {
        return $this->getMapper()->delete($this->getModel()->setGradingPolicyId($grading_policy));
    }

    /**
     * @param int $item_assignment
     * @param int $user
     */
    public function process($item_assignment, $user)
    {
        $this->getServiceEvent()->assignmentGraded($item_assignment);
        
        return $this->getMapper()->updateGrade($item_assignment, $user);
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * @return \Application\Service\GradingPolicy
     */
    public function getServiceGradingPolicy()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy');
    }
}
