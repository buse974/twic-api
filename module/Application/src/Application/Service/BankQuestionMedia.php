<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class BankQuestionMedia extends AbstractService
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
        foreach ($data as $bqm) {
            $token = (isset($bqm['token'])) ? $bqm['token']:null;
            $link  = (isset($bqm['link'])) ? $bqm['link']:null;
            
            $ret[] = $this->_add($bank_question_id, $token, $link);
        }
    
        return $ret;
    }
    
    /**
     * 
     * @param integer $bank_question_id
     * @param string $token
     * @param string $link
     * @throws \Exception
     * 
     * @return integer
     */
    public function _add($bank_question_id, $token = null, $link = null)
    {
        $m_bank_question_media = $this->getModel()->setBankQuestionId($bank_question_id)->setToken($token)->setLink($link);
        
        if($this->getMapper()->insert($m_bank_question_media) <=0) {
            throw new \Exception('error insert media');
        }
        
        return $this->getMapper()->getLastInsertValue();
        
    }
    
    public function getList($bank_question_id)
    {
        return $this->getMapper()->select($this->getModel()->setBankQuestionId($bank_question_id));
    }
}