<?php
namespace Application\Service;

use Dal\Service\AbstractService;

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
     * @param integer $item_prog            
     */
    public function getByItemProg($item_prog)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        
        $m_questionnaire = $this->getModel()->setItemId($m_item_prog->getItem());
        
        $res_questionnaire = $this->getMapper()->select($m_questionnaire);
        
        if ($res_questionnaire->count() <= 0) {
            $this->create($m_item_prog->getItem());
            $res_questionnaire = $this->getMapper()->select($m_questionnaire);
        }
        
        $m_questionnaire = $res_questionnaire->current();
        
        $m_questionnaire->setQuestions($this->getServiceQuestion()
            ->getList($m_questionnaire->getId()));
        
        return $m_questionnaire;
    }

    /**
     * 
     * @invokable
     * 
     * @param integer $item_prog
     * @param integer $user
     * @param integer $question
     * @param integer $scale
     */
    public function answer($item_prog, $user, $question, $scale)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_questionnaire = $this->getModel()->setItemId($m_item_prog->getItem());
        $m_questionnaire = $this->getMapper()
            ->select($m_questionnaire)
            ->current();
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($user, $m_questionnaire->getId());
        
        $m_questionnaire_question = $this->getServiceQuestionnaireQuestion()->getByQuestion($m_questionnaire->getId(), $question);
        
        return $this->getServiceAnswer()->add($question, $m_questionnaire_user->getId(), $m_questionnaire_question->getId(), $scale);
    }

    /**
     *
     * @return \Application\Service\Dimension
     */
    public function getServiceDimension()
    {
        return $this->getServiceLocator()->get('app_service_dimension');
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     *
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_item_prog');
    }

    /**
     *
     * @return \Application\Service\Question
     */
    public function getServiceQuestion()
    {
        return $this->getServiceLocator()->get('app_service_question');
    }

    /**
     *
     * @return \Application\Service\QuestionnaireUser
     */
    public function getServiceQuestionnaireUser()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_user');
    }

    /**
     *
     * @return \Application\Service\Answer
     */
    public function getServiceAnswer()
    {
        return $this->getServiceLocator()->get('app_service_answer');
    }

    /**
     *
     * @return \Application\Service\QuestionnaireQuestion
     */
    public function getServiceQuestionnaireQuestion()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_question');
    }
}