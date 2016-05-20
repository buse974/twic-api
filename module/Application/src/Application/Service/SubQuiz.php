<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\BankQuestionType as ModelBankQuestionType;

class SubQuiz extends AbstractService
{
    public function getBySubmission($submission_id)
    {
        $m_submission = $this->getServiceSubmission()->get(null,$submission_id);
        $res_sub_quiz = $this->getMapper()->getList(null, $submission_id);
        
        $ret = [];
        foreach ($res_sub_quiz as $m_sub_quiz) {     
           $ret[] = $this->get($m_sub_quiz->getId());
        }
        
        return $ret;
    }
    
    public function get($id) 
    {
        $m_sub_quiz = $this->getMapper()->get($id)->current();
        
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
        $ar['poll_items'] = $this->getServicePollItem()->getListLite($m_sub_quiz->getPollId())->toArray(['id']);
        
        return $ar;
    }
    
    /**
     * @invokable
     * 
     * @param integer $submission_id
     * @param integer $item_id
     */
    public function start($submission_id = null, $item_id = null)
    {
        if(null === $submission_id && null === $item_id) {
            return false;
        }

        $m_submission = $this->getServiceSubmission()->get($item_id,$submission_id);
        
        $m_poll = $this->getServicePoll()->getLiteByItem($m_submission->getItemId());
        $me = $this->getServiceUser()->getIdentity()['id'];
        
        $m_sub_quiz = $this->getModel()
            ->setUserId($me)
            ->setPollId($m_poll->getId())
            ->setStartDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setSubmissionId($m_submission->getId());
        
        $this->getMapper()->insert($m_sub_quiz);
        $sub_quiz_id = $this->getMapper()->getLastInsertValue();
        $res_poll_item = $this->getServicePollItem()->getList($m_poll->getId());
        foreach ($res_poll_item as $m_poll_item) {
            $m_group_question = $this->getServiceGroupQuestion()->getList($m_poll_item->getGroupQuestionId());
            if(null !== $m_group_question) { 
                $tab = $m_group_question->getBankQuestion();
                foreach (array_rand($tab, $m_group_question->getNb())as $i) {
                    $this->getServiceSubQuestion()->add($sub_quiz_id, $m_poll_item->getId(), $tab[$i], $m_poll_item->getGroupQuestionId());
                }
            } else {
                $this->getServiceSubQuestion()->add($sub_quiz_id, $m_poll_item->getId(), $m_poll_item->getBankQuestionId(), $m_poll_item->getGroupQuestionId());
            }
        }
        
        return $this->get($sub_quiz_id);
    }
    
    /**
     * @invokable
     * 
     * @param integer $sub_question_id
     * @param array $sub_answer
     * 
     * @return boolean
     */
    public function answer($sub_question_id, $sub_answer)
    {
        $sa = current($sub_answer);
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_sub_question = $this->getServiceSubQuestion()->get($sub_question_id);
        $m_sub_quiz = $this->getMapper()->select($this->getModel()->setId($m_sub_question->getSubQuizId()))->current();
        
        if(null === $m_sub_quiz ||
            $m_sub_quiz->getUserId() !== $user_id ||
            !( null === $m_sub_question->getAnsweredDate() || $m_sub_question->getAnsweredDate() instanceof IsNull)) {
           return false;
        }
        $res_sub_answer = $this->getServiceSubAnswer()->getListLite($sub_question_id);
        if($res_sub_answer->count() !== 0) {
            return false;
        }
        
        $m_poll_item = $this->getServicePollItem()->get($m_sub_question->getPollItemId());
        $m_bank_question = $this->getServiceBankQuestion()->get($m_poll_item->getBankQuestionId());

        $final_point = 0;
        $point = $m_poll_item->getNbPoint();
        $type = $m_bank_question->getBankQuestionTypeId();
        foreach ($sub_answer as $sa) { 
            $m_bank_answer_item = $this->getServiceBankAnswerItem()->get($sa['bank_question_item_id']);
            $is_ok = true;
            if($type === ModelBankQuestionType::TYPE_TEXT_INT) {
                if($sa['answer'] != $m_bank_answer_item->getAnswer()) {
                    $is_ok = false;
                }
            }
            if($is_ok === true) {
                $final_point +=  $point*($m_bank_answer_item->getPercent()/100);
            }
            $this->getServiceSubAnswer()->add($sub_question_id, $sa['bank_question_item_id'], (isset($sa['answer'])?$sa['answer']:null));
        }
        $this->getServiceSubQuestion()->updatePoint($sub_question_id, $final_point);

        
        
        $this->getServiceSubQuestion()->updateAnswered($sub_question_id);
        if($this->getMapper()->checkFinish($m_sub_quiz->getId())) {
            $total_final_grade = 0;
            $total_final = 0;
            $res_sub_question = $this->getServiceSubQuestion()->getListLite($m_sub_question->getSubQuizId());
            
            
            
            foreach ($res_sub_question as $m_sub_question) {
                $total_final_grade += $m_sub_question->getPoint();
            }
            
            $res_poll_item = $this->getServicePollItem()->getList($m_poll_item->getPollId());
            
            foreach ($res_poll_item as $m_poll_item) {
                $total_final += $m_poll_item->getNbPoint();
            }
            
            $this->getMapper()->update($this->getModel()->setGrade(100*$total_final_grade/$total_final)->setId($m_sub_question->getSubQuizId()));
            $this->getServiceSubmission()->submit($m_sub_quiz->getSubmissionId());
        }
       
        return true;
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
     * @return \Application\Service\GroupQuestion
     */
    public function getServiceGroupQuestion()
    {
        return $this->getServiceLocator()->get('app_service_group_question');
    }
    
    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
    
    /**
     * @return \Application\Service\BankAnswerItem
     */
    public function getServiceBankAnswerItem()
    {
        return $this->getServiceLocator()->get('app_service_bank_answer_item');
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