<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTime;
use DateTimeZone;

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
     *
     * @param int  $grading_policy
     */
    public function deleteFromGradingPolicy($grading_policy){
      
        return $this->getMapper()->delete($this->getModel()->setGradingPolicyId($grading_policy));      
      
    }
    
       /**   
     * @invokable
     *   
     * @param int  $item_assignment
     * 
     * @return array 
     */
    public function process($user){
        $res = 0;
        $this->getMapper()->delete($this->getModel()->setUserId($user));
        $res_grading_policy = $this->getServiceGradingPolicy()->processGrade($user); 
        foreach($res_grading_policy as $m_grading_policy){
            $m_grading_policy_grade = $this->getModel()
                    ->setGradingPolicyId($m_grading_policy->getId())
                    ->setGrade($m_grading_policy->getProcessedGrade())
                    ->setUserId($user)
                    ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));            
            $res += $this->getMapper()->insert($m_grading_policy_grade);
        }
        
        
        return $res;
        
    }
    
  
    
    

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     * @return \Application\Service\GradingPolicy
     */
    public function getServiceGradingPolicy()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy');
    }
    
   
}
