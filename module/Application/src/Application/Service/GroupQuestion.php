<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GroupQuestion extends AbstractService
{
    public function add($group_question, $nb)
    {
        if($this->getMapper()->insert($this->getModel()->setNb($nb)) <= 0){
            throw new \Exception('error insert group');
        }
        
        $group_question_id = $this->getMapper()->getLastInsertValue();
        foreach ($group_question as $bank_question_id) {
            $this->getServiceQuestionRelation()->add($group_question_id, $bank_question_id);
        }
        
        return $group_question_id;
    }
    
    /**
     * @return \Application\Service\QuestionRelation
     */
    public function getServiceQuestionRelation()
    {
        return $this->getServiceLocator()->get('app_service_question_relation');
    }
}