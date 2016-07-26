<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Submission Quiz
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use Application\Model\BankQuestionType as ModelBankQuestionType;

/**
 * Class SubQuiz
 */
class SubQuiz extends AbstractService
{

    /**
     * Get SubmissionQuiz By submission
     *
     * @param int $submission_id            
     * @return []
     */
    public function getBySubmission($submission_id)
    {
        $m_submission = $this->getServiceSubmission()->get(null, $submission_id);
        $res_sub_quiz = $this->getMapper()->getList(null, $submission_id);
        
        $ret = [];
        foreach ($res_sub_quiz as $m_sub_quiz) {
            $ret[] = $this->get($m_sub_quiz->getId());
        }
        
        return $ret;
    }

    /**
     * Get SubmissionQuiz
     * 
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        $m_sub_quiz = $this->getMapper()
            ->get($id)
            ->current();
        
        $ar = $m_sub_quiz->toArray();
        $ar['sub_questions'] = $this->getServiceSubQuestion()->getListLite($m_sub_quiz->getId());
        $sub_question_ids = [];
        $bank_question_ids = [];
        foreach ($ar['sub_questions'] as $m_sub_question) {
            $sub_question_ids[] = $m_sub_question->getId();
            $bank_question_ids[] = $m_sub_question->getBankQuestionId();
        }
        $ar['sub_answers'] = $this->getServiceSubAnswer()
            ->getListLite($sub_question_ids)
            ->toArray(['bank_question_item_id']);
        $ar['bank_questions'] = $this->getServiceBankQuestion()
            ->getListLite($bank_question_ids)
            ->toArray(['id']);
        $ar['bq_items'] = $this->getServiceBankQuestionItem()->getList($bank_question_ids);
        $ar['medias'] = $this->getServiceBankQuestionMedia()->getListBankQuestion($bank_question_ids);
        $ar['poll'] = $this->getServicePoll()->getLite($m_sub_quiz->getPollId());
        $ar['poll_items'] = $this->getServicePollItem()
            ->getListLite($m_sub_quiz->getPollId())
            ->toArray(['id']);
        
        return $ar;
    }

    /**
     * Start SubmissionQuiz
     * 
     * @invokable
     *
     * @param int $submission_id            
     * @param int $item_id    
     * @return array        
     */
    public function start($submission_id = null, $item_id = null)
    {
        if (null === $submission_id && null === $item_id) {
            return false;
        }
        $m_submission = $this->getServiceSubmission()->get($item_id, $submission_id);
        
        $m_poll = $this->getServicePoll()->getLiteByItem($m_submission->getItemId());
        $me = $this->getServiceUser()->getIdentity()['id'];
        
        $m_sub_quiz = $this->getModel()
            ->setUserId($me)
            ->setPollId($m_poll->getId())
            ->setStartDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setSubmissionId($m_submission->getId());
        $this->getServiceSubmissionUser()->start($m_sub_quiz->getSubmissionId());
        
        $this->getMapper()->insert($m_sub_quiz);
        $sub_quiz_id = $this->getMapper()->getLastInsertValue();
        $res_poll_item = $this->getServicePollItem()->getList($m_poll->getId());
        foreach ($res_poll_item as $m_poll_item) {
            $m_group_question = $this->getServiceGroupQuestion()->getList($m_poll_item->getGroupQuestionId());
            if (null !== $m_group_question) {
                $tab = $m_group_question->getBankQuestion();
                $key = array_rand($tab, $m_group_question->getNb());
                if (! is_array($key)) {
                    $key = [$key];
                }
                foreach ($key as $k) {
                    $this->getServiceSubQuestion()->add($sub_quiz_id, $m_poll_item->getId(), $tab[$k], $m_poll_item->getGroupQuestionId());
                }
            } else {
                $this->getServiceSubQuestion()->add($sub_quiz_id, $m_poll_item->getId(), $m_poll_item->getBankQuestionId(), $m_poll_item->getGroupQuestionId());
            }
        }
        
        return $this->get($sub_quiz_id);
    }

    /**
     * Get SubmissionQuiz Started
     * 
     * @invokable
     * 
     * @return array
     */
    public function getStarted()
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $res_sub_quiz = $this->getMapper()->getList(null, null, $user_id, false);
        $ret = [];
        foreach ($res_sub_quiz as $m_sub_quiz) {
            $ret[] = $this->get($m_sub_quiz->getId());
        }
        
        return $ret;
    }

    /**
     * Add answer
     * 
     * @invokable
     *
     * @param int $sub_question_id            
     * @param array $sub_answer            
     *
     * @return bool
     */
    public function answer($sub_question_id, $sub_answer)
    {
        $sa = current($sub_answer);
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $m_sub_question = $this->getServiceSubQuestion()->get($sub_question_id);
        $m_sub_quiz = $this->getMapper()
            ->select($this->getModel()
            ->setId($m_sub_question->getSubQuizId()))
            ->current();
        
        if (null === $m_sub_quiz || $m_sub_quiz->getUserId() !== $user_id || ! (null === $m_sub_question->getAnsweredDate() || $m_sub_question->getAnsweredDate() instanceof IsNull)) {
            return false;
        }
        $res_sub_answer = $this->getServiceSubAnswer()->getListLite($sub_question_id);
        if ($res_sub_answer->count() !== 0) {
            return false;
        }
        
        $m_poll_item = $this->getServicePollItem()->get($m_sub_question->getPollItemId());
        $m_bank_question = $this->getServiceBankQuestion()->get($m_sub_question->getBankQuestionId());
        
        $final_point = 0;
        $point = $m_poll_item->getNbPoint();
        $type = $m_bank_question->getBankQuestionTypeId();
        $point_initial = $m_bank_question->getPoint();
        foreach ($sub_answer as $sa) {
            $m_bank_answer_item = $this->getServiceBankAnswerItem()->get($sa['bank_question_item_id']);
            $is_ok = true;
            if ($type === ModelBankQuestionType::TYPE_TEXT_INT) {
                $v1 = (isset($sa['answer']) ? $sa['answer'] : '');
                $v2 = (is_string($m_bank_answer_item->getAnswer()) ? $m_bank_answer_item->getAnswer() : '');
                if (strtolower(trim($v1)) != strtolower(trim($v2))) {
                    $is_ok = false;
                }
            }
            if ($is_ok === true) {
                $final_point += $m_bank_answer_item->getPercent();
            }
            $this->getServiceSubAnswer()->add($sub_question_id, $sa['bank_question_item_id'], (isset($sa['answer']) ? $sa['answer'] : null));
        }
        
        $this->getServiceSubQuestion()->updatePoint($sub_question_id, ($final_point * $point / $point_initial));
        $this->getServiceSubQuestion()->updateAnswered($sub_question_id);
        if ($this->getMapper()->checkFinish($m_sub_quiz->getId())) {
            $this->calc(null, null, $m_sub_quiz->getId());
        }
        
        return true;
    }

    /**
     * Calcule submissionQuiz
     * 
     * @param int $submission_id
     * @param int $item_id
     * @param int $sub_quiz_id
     * @return boolean
     */
    public function calc($submission_id = null, $item_id = null, $sub_quiz_id = null)
    {
        if (null === $submission_id && null === $item_id && null === $sub_quiz_id) {
            return false;
        }
        
        if ((null !== $submission_id || null !== $item_id) && null === $sub_quiz_id) {
            $m_submission = $this->getServiceSubmission()->get($item_id, $submission_id);
            $m_sub_quiz = $this->getBySubmission($m_submission->getId());
            $sub_quiz_id = $m_sub_quiz->getId();
        }
        
        $m_sub_quiz = $this->getMapper()
            ->get($sub_quiz_id)
            ->current();
        $user_id = $m_sub_quiz->getUserId();
        
        $total_final_grade = 0;
        $res_sub_question = $this->getServiceSubQuestion()->getListLite($sub_quiz_id);
        foreach ($res_sub_question as $m_sub_question) {
            $p = $m_sub_question->getPoint();
            if (! is_numeric($p)) {
                $p = 0;
            }
            $total_final_grade += $p;
        }
        
        $total_final = 0;
        $res_poll_item = $this->getServicePollItem()->getList($m_sub_quiz->getPollId());
        foreach ($res_poll_item as $m_poll_item) {
            $gq = $m_poll_item->getGroupQuestion();
            $nbq = 1;
            if (null !== $gq) {
                $nbq = $gq->getNb();
            }
            $total_final += $m_poll_item->getNbPoint() * $nbq;
        }
        
        $grade = 100 * $total_final_grade / $total_final;
        $this->getMapper()->update($this->getModel()
            ->setGrade($grade)
            ->setId($sub_quiz_id));
        $this->getServiceSubmissionUser()->setGrade($m_sub_quiz->getSubmissionId(), $user_id, $grade);
        $this->getServiceSubmission()->_submit($m_sub_quiz->getSubmissionId(), null, $user_id);
        
        return true;
    }

    /**
     * Rate submissionQuiz
     * 
     * @invokable
     *
     * @param int $id            
     * @param int $grade            
     * @param array $questions            
     * @return bool
     */
    public function rate($id, $grade, $questions)
    {
        $this->getMapper()->update($this->getModel()
            ->setGrade($grade)
            ->setId($id));
        $m_sub_quiz = $this->getMapper()
            ->get($id)
            ->current();
        foreach ($questions as $qid => $qgrade) {
            $this->getServiceSubQuestion()->updatePoint($qid, $qgrade);
        }
        
        return true;
    }

    /**
     * Check Grade
     * 
     * @invokable
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function checkGrade()
    {
        $res_sub_quiz = $this->getMapper()->getList(null, null, null, null, true);
        foreach ($res_sub_quiz as $m_sub_quiz) {
            $id = $m_sub_quiz->getId();
            $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
            $this->getMapper()->update($this->getModel()
                ->setId($id)
                ->setEndDate($date));
            $this->calc(null, null, $m_sub_quiz->getId());
        }
        
        return $res_sub_quiz;
    }

    /**
     * Get Service BankQuestionMedia
     * 
     * @return \Application\Service\BankQuestionMedia
     */
    private function getServiceBankQuestionMedia()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_media');
    }

    /**
     * Get Service BankQuestionItem
     * 
     * @return \Application\Service\BankQuestionItem
     */
    private function getServiceBankQuestionItem()
    {
        return $this->getServiceLocator()->get('app_service_bank_question_item');
    }

    /**
     * Get Service BankQuestion
     * 
     * @return \Application\Service\BankQuestion
     */
    private function getServiceBankQuestion()
    {
        return $this->getServiceLocator()->get('app_service_bank_question');
    }

    /**
     * Get Service GroupQuestion
     * 
     * @return \Application\Service\GroupQuestion
     */
    private function getServiceGroupQuestion()
    {
        return $this->getServiceLocator()->get('app_service_group_question');
    }

    /**
     * Get Service User
     * 
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * Get Service BankAnswerItem
     * 
     * @return \Application\Service\BankAnswerItem
     */
    private function getServiceBankAnswerItem()
    {
        return $this->getServiceLocator()->get('app_service_bank_answer_item');
    }

    /**
     * Get Service SubAnswer
     * 
     * @return \Application\Service\SubAnswer
     */
    private function getServiceSubAnswer()
    {
        return $this->getServiceLocator()->get('app_service_sub_answer');
    }

    /**
     * Get Service SubQuestion
     * 
     * @return \Application\Service\SubQuestion
     */
    private function getServiceSubQuestion()
    {
        return $this->getServiceLocator()->get('app_service_sub_question');
    }

    /**
     * Get Service SubmissionUser
     * 
     * @return \Application\Service\SubmissionUser
     */
    private function getServiceSubmissionUser()
    {
        return $this->getServiceLocator()->get('app_service_submission_user');
    }

    /**
     * Get Service Submission
     * 
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * Get Service Poll
     * 
     * @return \Application\Service\Poll
     */
    private function getServicePoll()
    {
        return $this->getServiceLocator()->get('app_service_poll');
    }

    /**
     * Get Service PollItem
     * 
     * @return \Application\Service\PollItem
     */
    private function getServicePollItem()
    {
        return $this->getServiceLocator()->get('app_service_poll_item');
    }
}
