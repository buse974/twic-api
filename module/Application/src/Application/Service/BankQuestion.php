<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class BankQuestion extends AbstractService
{
    public function create($course_id, $question, $bank_question_type, $bank_question_tag, $bank_question_media, $point)
    {
        /* 'question' => 'Ma question',
         'bank_question_type' => 3,
         'bank_question_tag' => 'maquestion',
         'bank_question_media' => [
         ['token' => 'token'],
         ['link' => 'link']
         ],
         'point' => 99,
         'bank_question_item' => [
         [
         'libelle' => 'oui',
         'answer' => 'super pas cool',
         'percent' => '(-100%) < > (100%)'
    
         ],
         ['libelle' => 'non']
         ]
         */
    }
    
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
            $bank_question_type_id  = (isset($bq['bank_question_type'])) ? $bq['bank_question_type']:null;
            $point = (isset($bq['point'])) ? $bq['point']:null;
            $bank_question_item  = (isset($bq['bank_question_item'])) ? $bq['bank_question_item']:null;
            $bank_question_tag = (isset($bq['bank_question_tag'])) ? $bq['bank_question_tag']:null;
            $bank_question_media  = (isset($bq['bank_question_media'])) ? $bq['bank_question_media']:null;
        
            $ret[] = $this->_add($course_id, $question, $bank_question_type_id, $point, $bank_question_item, $bank_question_tag, $bank_question_media);
        }
        
        return $ret;
    }
    
    public function _add($course_id, $question, $bank_question_type_id, $point, $bank_question_item, $bank_question_tag, $bank_question_media)
    {
        $m_bank_question = $this->getModel()
            ->setQuestion($question)
            ->setBankQuestionTypeId($bank_question_type_id)
            ->setPoint($point)
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