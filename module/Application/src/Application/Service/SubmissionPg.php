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
        $res_submission = $this->getServiceSubmission()->getList($item_id);
        
        $ar_s = []; 
        
        foreach ($res_submission as $m_submission) {
            $ar_s[$m_submission->getId()]=[];
            
            foreach ($m_submission->getSubmissionUser() as $m_su) {
                $ar_s[$m_submission->getId()][] = $m_su->getUserId();
            }
        }
        
        print_r($ar_s);
    }
    
    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
}