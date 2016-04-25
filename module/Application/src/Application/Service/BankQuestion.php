<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class BankQuestion extends AbstractService
{
    /**
     * @invokable
     * 
     * @param integer $course_id
     * @param array $data
     */
    public function add($course_id, $data) 
    {
        $ret = [];
        foreach ($data as $bq) {
            $question = (isset($bq['question'])) ? $bq['question']:null;
            $bank_question_type_id  = (isset($bq['bank_question_type_id'])) ? $bq['bank_question_type_id']:null;
            $point = (isset($bq['point'])) ? $bq['point']:null;
            $bank_question_item  = (isset($bq['bank_question_item'])) ? $bq['bank_question_item']:null;
            $bank_question_tag = (isset($bq['bank_question_tag'])) ? $bq['bank_question_tag']:null;
            $bank_question_media  = (isset($bq['bank_question_media'])) ? $bq['bank_question_media']:null;
            $name = (isset($bq['name'])) ? $bq['name']:null;
        
            $ret[] = $this->_add($course_id, $question, $bank_question_type_id, $point, $bank_question_item, $bank_question_tag, $bank_question_media, $name);
        }
        
        return $ret;
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * @param string $question
     * @param integer $bank_question_type_id
     * @param integer $point
     * @param array $bank_question_item
     * @param array $bank_question_tag
     * @param array $bank_question_media
     * @param string $name
     */
    public function update($id, $question = null, $bank_question_type_id = null, $point = null, $bank_question_item = null, $bank_question_tag = null, $bank_question_media = null, $name = null)
    {
        $m_bank_question = $this->getModel()
            ->setId($id)
            ->setQuestion($question)
            ->setBankQuestionTypeId($bank_question_type_id)
            ->setPoint($point)
            ->setName($name);
        
        $ret = $this->getMapper()->update($m_bank_question);
        
        if(null !== $bank_question_media) {
            $this->getServiceBankQuestionMedia()->replace($id, $bank_question_media);
        }
        
        if(null !== $bank_question_tag) {
            $this->getServiceBankQuestionTag()->replace($id, $bank_question_tag);
        }
        
        if(null !== $bank_question_item) {
            $this->getServiceBankQuestionItem()->replace($id, $bank_question_item);
        }
        
        return $ret;
    }
    
    /**
     * @invokable
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    public function _add($course_id, $question, $bank_question_type_id, $point, $bank_question_item, $bank_question_tag, $bank_question_media, $name)
    {
        $m_bank_question = $this->getModel()
            ->setQuestion($question)
            ->setBankQuestionTypeId($bank_question_type_id)
            ->setPoint($point)
            ->setName($name)
            ->setCourseId($course_id);
        
        if($this->getMapper()->insert($m_bank_question) <= 0) {
            throw new \Exception('error add bank question');
        }
        
        $bank_question_id = $this->getMapper()->getLastInsertValue();
        
        $this->getServiceBankQuestionMedia()->add($bank_question_id, $bank_question_media);
        $this->getServiceBankQuestionTag()->add($bank_question_id, $bank_question_tag);
        $this->getServiceBankQuestionItem()->add($bank_question_id, $bank_question_item);
        
        return $bank_question_id;
    }
        
    /**
     * @invokable
     * 
     * @param integer $course_id
     */
    public function getList($course_id)
    {
        $res_bank_question = $this->getMapper()->select($this->getModel()->setCourseId($course_id));
        
        foreach ($res_bank_question as $m_bank_question) {
            $bank_question_id = $m_bank_question->getId();
            $m_bank_question->setBankQuestionItem($this->getServiceBankQuestionItem()->getList($bank_question_id)); 
            $m_bank_question->setBankQuestionMedia($this->getServiceBankQuestionMedia()->getList($bank_question_id));
            $m_bank_question->setBankQuestionTag($this->getServiceBankQuestionTag()->getList($bank_question_id));
        }
        
        return $res_bank_question;
    }
    
    /**
     * 
     * @return \Application\Service\BankQuestionMedia
     */
    public function getServiceBankQuestionMedia()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_media');
    }
    
    /**
     *
     * @return \Application\Service\BankQuestionTag
     */
    public function getServiceBankQuestionTag()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_tag');
    }
    
    /**
     *
     * @return \Application\Service\BankQuestionItem
     */
    public function getServiceBankQuestionItem()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_item');
    }
    
}