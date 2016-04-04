<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class BankQuestionTag extends AbstractService
{
    /**
     *
     * @param integer $bank_question_id
     * @param $data
     *
     * @throws \Exception
     *
     * @return integer
     */
    public function add($bank_question_id, $data)
    {
        $ret = [];
        foreach ($data as $name) {
            $ret[] = $this->_add($bank_question_id, $name);
        }
    
        return $ret;
    }
    
    /**
     * @param integer $bank_question_id
     * @param string $name
     * @throws \Exception
     * 
     * @return integer
     */
    public function _add($bank_question_id, $name)
    {
        $m_bank_question_tag = $this->getModel()
            ->setBankQuestionId($bank_question_id)
            ->setName($name);
    
        if($this->getMapper()->insert($m_bank_question_tag) <=0) {
            throw new \Exception('error insert tag');
        }
    
        return $this->getMapper()->getLastInsertValue();
    
    }
}