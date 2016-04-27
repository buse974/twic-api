<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class SubQuiz extends AbstractService
{
    public function getBySubmission($submission_id)
    {
        $m_submission = $this->getServiceSubmission()->get(null,$submission_id);
        $item_id = $m_submission->getItemId(); 
        
        $res_sub_quiz = $this->getMapper()->select($this->getModel()->setSubmissionId($submission_id));
        
        $ret = [];
        foreach ($res_sub_quiz as $m_sub_quiz) {
            $ar = $m_sub_quiz->toArray();
            $ar['sub_questions'] = $this->getServiceSubQuestion()->getListLite($m_sub_quiz->getId());
            
            $sub_question_ids = [];
            $bank_question_ids = [];
            foreach ($ar['sub_questions'] as $m_sub_question) {
                $sub_question_ids[] = $m_sub_question->getId();
                $bank_question_ids[] = $m_sub_question->getBankQuestionId();
            }
            $ar['sub_answers'] = $this->getServiceSubAnswer()->getListLite($sub_question_ids)->toArray(['bank_question_item_id']);
            $ar['bank_questions'] = $this->getServiceBankQuestion()->getListLite($bank_question_ids)->toArray(['id']);
            $ar['bq_items'] = $this->getServiceBankQuestionItem()->getList($bank_question_ids);
            $ar['medias'] = $this->getServiceBankQuestionMedia()->getListBankQuestion($bank_question_ids);
            $ar['poll'] = $this->getServicePoll()->getLite($m_sub_quiz->getPollId());
            $ar['poll_items'] = $this->getServicePollItem()->getListLite($poll_id)->toArray(['id']);
                
            $ret[] = $ar;
        }
        
        
        
        
        
        
        return $this->getServicePoll()->getByItem($item_id);
    }
     
    /**
     * @return \Application\Service\BankQuestionMedia
     */
    public function getServiceBankQuestionMedia()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_media');
    }
    
    /**
     * @return \Application\Service\BankQuestionItem
     */
    public function getServiceBankQuestionItem()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_item');
    }
    
    /**
     * @return \Application\Service\BankQuestion
     */
    public function getServiceBankQuestion()
    {
        return $this->getServiceLocator()->get('app_service_bank_question');
    }
    
    /**
     * @return \Application\Service\SubAnswer
     */
    public function getServiceSubAnswer()
    {
        return $this->getServiceLocator()->get('app_service_sub_answer');
    }
    
    /**
     * @return \Application\Service\SubQuestion
     */
    public function getServiceSubQuestion()
    {
        return $this->getServiceLocator()->get('app_service_sub_question');
    }
    
    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
    
    /**
     * 
     * @return \Application\Service\Poll
     */
    public function getServicePoll()
    {
        return $this->getServiceLocator()->get('app_service_poll');
    }
    
    /**
     *
     * @return \Application\Service\PollItem
     */
    public function getServicePollItem()
    {
        return $this->getServiceLocator()->get('app_service_poll_item');
    }
}