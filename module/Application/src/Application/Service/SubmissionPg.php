<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubmissionPg extends AbstractService
{
    
    public function add($submission, $users)
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        return  $this->getMapper()->insert($this->getModel()->setUserId($users)->setSubmissionId($submission)->setDate($date));
    }
    
    public function delete($submission, $users)
    {
        return  $this->getMapper()->delete($this->getModel()->setUserId($user)->setSubmissionId($submission));
    }
    
    public function checkGraded($submission, $user)
    {
        return  $this->getMapper()->checkGraded($submission, $user);
    }
    
    public function replace($submission, $users)
    {
        $this->getMapper()->deleteNotIn($submission, $users);
        foreach($users as $u){
            $this->add($submission, $u);
        }
        return 1;
    }
    
    /**
     * @invokable
     * 
     * @param integer $item_id
     */
    public function autoAssign($item_id)
    {
        $m_opt_grading = $this->getServiceOptGrading()->get($item_id);
        if(!$m_opt_grading->getHasPg() && !$m_opt_grading->getPgAuto()) {
            return false;
        }
        
        $ar_s = []; 
        $ar_u = [];
        $res_submission = $this->getServiceSubmission()->getList($item_id);
        foreach ($res_submission as $m_submission) {
            $ar_s[$m_submission->getId()]=[];
            foreach ($m_submission->getSubmissionUser() as $m_su) {
                $u = $m_su->getUserId();
                $ar_s[$m_submission->getId()][] = $u;
                $ar_u[] = $u;
            }
        }
        $nb = $m_opt_grading->getPgNb();
        
        
        
        
        
        
        $final = [];
        foreach ($ar_s as $s_id => $s_user) {
            $tmp = $ar_u;
            foreach ($s_user as $uu)
            unset($tmp[array_search($uu, $tmp)]);
            
            if(count($tmp) >= $nb) {
                $keys = array_rand($tmp, $nb);
                if(!is_array($keys)) {$keys = [$keys];}
                foreach ($keys as $k) {
                    $final[$s_id] = $ar_u[$k];
                    unset($ar_u[$k]);
                }
            }
        }
        
        
        print_r($nb);
        print_r($ar_u);
        print_r($ar_s);
        print_r($final);
    }
    
    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
    
    /**
     * @return \Application\Service\OptGrading
     */
    public function getServiceOptGrading()
    {
        return $this->getServiceLocator()->get('app_service_opt_grading');
    }
    
}