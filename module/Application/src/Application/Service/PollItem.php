<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class PollItem extends AbstractService
{
    public function add($poll_id, $data)
    {
        $ret = [];
        foreach ($data as $d) {
            $nb_point = (isset($d['nb_point']))?$d['nb_point']:null;
            $bank_question_id = (isset($d['bank_question_id']))?$d['bank_question_id']:null;
            $group_question = (isset($d['group_question']))?$d['group_question']:null;
            $nb = (isset($d['nb']))?$d['nb']:null;
            $is_mandatory = (isset($d['is_mandatory']))?$d['is_mandatory']:null;
            $order_id = (isset($d['order_id']))?$d['order_id']:null;
            
            $ret[] = $this->_add($poll_id, $nb_point, $bank_question_id, $group_question, $nb, $is_mandatory, $order_id);
        }
        
        return $ret;
    }
    
    public function _add($poll_id, $nb_point = null, $bank_question_id = null, $group_question = null, $nb = null, $is_mandatory = null, $order_id = null)
    {
        $group_question_id = ($group_question!==null)?$this->getServiceGroupQuestion()->add($group_question, $nb):null;
        
        $m_question = $this->getModel()
            ->setIsMandatory($is_mandatory)
            ->setPollId($poll_id)
            ->setBankQuestionId($bank_question_id)
            ->setNbPoint($nb_point)
            ->setGroupQuestionId($group_question_id)
            ->setOrderId($order_id);
    
        if ($this->getMapper()->insert($m_question) < 1) {
            throw new \Exception('Insert question error');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }
    
    /**
     *
     * @return \Application\Service\GroupQuestion
     */
    public function getServiceGroupQuestion()
    {
        return $this->getServiceLocator()->get('app_service_group_question');
    }
    
}