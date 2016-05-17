<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Application\Model\Item as CI;
use Zend\Db\Sql\Predicate\IsNull;

class Questionnaire extends AbstractService
{
    private function create($item)
    {
        $m_questionnaire = $this->getModel()
            ->setItemId($item)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        if ($this->getMapper()->insert($m_questionnaire) <= 0) {
            throw new \Exception('error create questionnaire');
        }

        $id = $this->getMapper()->getLastInsertValue();

        $this->getServiceQuestionnaireQuestion()->create($id);

        return $id;
    }

    /**
     * @invokable
     *
     * @param int $item
     */
    public function getByItem($item)
    {
        $m_item = $this->getServiceItem()->get($item);
        
        if ($m_item->getType() !== CI::TYPE_WORKGROUP) {
            throw new  \Exception('No Workgroup');
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
     * @invokable
     *
     * @param int $item
     * @param int $user
     * @param int $question
     * @param int $scale
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
        if (is_numeric($nbrq) && $nbrq == 0) {
            $this->getServiceSubmissionUser()->end($m_submission->getId());
            $has_all_finish = $this->getServiceSubmissionUser()->checkAllFinish($m_submission->getId());
            if ($has_all_finish) {
                $this->getServiceSubmissionUser()->submit($m_submission->getId());
               // $this->getServiceEvent()->eqcqAvailable($item);
            }
        }

        return $ret;
    }

    /**
     * @param integer $item
     *
     * @return NULL|integer
     */
    public function getNbrQuestionNoCompleted($item)
    {
        $nbr = null;
        $user = $this->getServiceUser()->getIdentity()['id'];
        $res_questionnaire = $this->getMapper()->getNbrQuestionNoCompleted($item, $user);

        if ($res_questionnaire->count() > 0) {
            $nbr = $res_questionnaire->current()->getNbNoCompleted();
            if ($nbr instanceof IsNull) {
                $nbr = null;
            }
        }

        return $nbr;
    }

    /**
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
     * @return \Application\Service\Dimension
     */
    public function getServiceDimension()
    {
        return $this->getServiceLocator()->get('app_service_dimension');
    }
    
    /**
     * @return \Application\Service\Submission
     */
    public function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * @return \Application\Service\SubmissionUser
     */
    public function getServiceSubmissionUser()
    {
        return $this->getServiceLocator()->get('app_service_submission_user');
    }
    
    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }

    /**
     * @return \Application\Service\Question
     */
    public function getServiceQuestion()
    {
        return $this->getServiceLocator()->get('app_service_question');
    }

    /**
     * @return \Application\Service\QuestionnaireUser
     */
    public function getServiceQuestionnaireUser()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_user');
    }

    /**
     * @return \Application\Service\ItemProgUser
     */
    public function getServiceItemProgUser()
    {
        return $this->getServiceLocator()->get('app_service_submission_user');
    }

    /**
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceItemAssignment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment');
    }

    /**
     * @return \Application\Service\Item
     */
    public function getServiceItem()
    {
        return $this->getServiceLocator()->get('app_service_item');
    }
    
    /**
     * @return \Application\Service\Answer
     */
    public function getServiceAnswer()
    {
        return $this->getServiceLocator()->get('app_service_answer');
    }

    /**
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
    }

    /**
     * @return \Application\Service\QuestionnaireQuestion
     */
    public function getServiceQuestionnaireQuestion()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_question');
    }
}
