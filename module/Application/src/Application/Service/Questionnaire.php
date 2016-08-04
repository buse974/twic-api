<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Questionnaire
 *
 */

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as CI;
use Zend\Db\Sql\Predicate\IsNull;

/**
 * Class Questionnaire
 */
class Questionnaire extends AbstractService
{
    /**
     * Create Questionnaire
     * 
     * @param int $item_id
     * @throws \Exception
     * @return int
     */
    private function create($item_id)
    {
        $m_questionnaire = $this->getModel()
            ->setItemId($item_id)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_questionnaire) <= 0) {
            throw new \Exception('error create questionnaire');
        }

        $id = $this->getMapper()->getLastInsertValue();

        $this->getServiceQuestionnaireQuestion()->create($id);

        return $id;
    }

    /**
     * Get Bt item
     * 
     * @invokable
     * 
     * @param int $item
     * @return \Application\Model\Questionnaire
     */
    public function getByItem($item)
    {
        $m_item = $this->getServiceItem()->get($item);
        if ($m_item->getType() !== CI::TYPE_HANGOUT && $m_item->getType() !== CI::TYPE_EQCQ) {
            throw new  \Exception('No   Workgroup');
        }

        $res_questionnaire = $this->getMapper()->getByItem($item);
        if ($res_questionnaire->count() <= 0) {
            $this->create($item);
            $res_questionnaire = $this->getMapper()->getByItem($item);
        }

        $m_questionnaire = $res_questionnaire->current();
        $m_questionnaire->setQuestions($this->getServiceQuestion()->getList($m_questionnaire->getId()));
        
        
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($m_questionnaire->getId(), $item);

        return $m_questionnaire;
    }

    /**
     * Add a answer
     * 
     * @invokable
     *
     * @param int $item
     * @param int $user
     * @param int $question
     * @param int $scale
     * @return int
     */
    public function answer($item, $user, $question, $scale)
    {
        $m_submission = $this->getServiceSubmission()->getByItem($item);
        $m_questionnaire = $this->getMapper()->getByItem($item)->current();
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($m_questionnaire->getId(), $item);
        $m_questionnaire_question = $this->getServiceQuestionnaireQuestion()->getByQuestion($m_questionnaire->getId(), $question);

        $ret = $this->getServiceAnswer()->add(
            $question,
            $m_questionnaire_user->getId(),
            $m_questionnaire_question->getId(),
            $user,
            $scale);

        $nbrq = $this->getNbrQuestionNoCompleted($item);
        if ($nbrq === 0) {
            $this->getServiceSubmissionUser()->end($m_submission->getId());
            $this->getServiceSubmission()->submit($m_submission->getId());
            
            $has_all_finish = $this->getServiceSubmissionUser()->checkAllFinish($m_submission->getId());
            if ($has_all_finish) {
                $this->getServiceSubmission()->forceSubmit($m_submission->getId());
                $this->getServiceEvent()->eqcqAvailable($m_submission->getId());
            }
        }

        return $ret;
    }

    /**
     * Get number question No completed
     * 
     * @param int $item_id
     * @return null|int
     */
    public function getNbrQuestionNoCompleted($item_id)
    {
        $nbr = $tnbr = null;
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        $res_questionnaire = $this->getMapper()->getNbrTotal($item_id);
        if ($res_questionnaire->count() > 0) {
            $tnbr = $res_questionnaire->current()->getNbNoCompleted();
            $tnbr = ($tnbr instanceof IsNull) ? null : (int)$tnbr;
        }
        
        $res_questionnaire = $this->getMapper()->getNbrQuestionCompleted($item_id, $user_id);
        if ($res_questionnaire->count() > 0) {
            $nbr = $res_questionnaire->current()->getNbNoCompleted();
            $nbr = ($nbr instanceof IsNull) ? null : (int)$nbr;
        }
        
        return ($tnbr-$nbr);
    }

    /**
     * Get Answer
     * 
     * @invokable
     *
     * @param int $item
     * @param int $user
     */
    public function getAnswer($item, $user = null)
    {
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity()['id'];
        }

        $m_questionnaire = $this->getMapper()->getByItem($item)->current();
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($m_questionnaire->getId(), $item);

        $m_questionnaire_user->setAnswers($this->getServiceAnswer()
            ->getByQuestionnaireUser($m_questionnaire_user->getId()));

        return $m_questionnaire_user;
    }

    /**
     * Get Service Dimension
     * 
     * @return \Application\Service\Dimension
     */
    private function getServiceDimension()
    {
        return $this->getServiceLocator()->get('app_service_dimension');
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
     * Get Service SubmissionUser
     * 
     * @return \Application\Service\SubmissionUser
     */
    private function getServiceSubmissionUser()
    {
        return $this->getServiceLocator()->get('app_service_submission_user');
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
     * Get Service Question
     * 
     * @return \Application\Service\Question
     */
    private function getServiceQuestion()
    {
        return $this->getServiceLocator()->get('app_service_question');
    }

    /**
     * Get Service QuestionnaireUser
     * 
     * @return \Application\Service\QuestionnaireUser
     */
    private function getServiceQuestionnaireUser()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_user');
    }

    /**
     * Get Service Item
     * 
     * @return \Application\Service\Item
     */
    private function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }

    /**
     * Get Service Answer
     * 
     * @return \Application\Service\Answer
     */
    private function getServiceAnswer()
    {
        return $this->getServiceLocator()->get('app_service_answer');
    }

    /**
     * Get Service Event
     * 
     * @return \Application\Service\Event
     */
    private function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * Get Service QuestionnaireQuestion
     * 
     * @return \Application\Service\QuestionnaireQuestion
     */
    private function getServiceQuestionnaireQuestion()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_question');
    }
}
