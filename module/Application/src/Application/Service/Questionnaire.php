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
        
        if($res_questionnaire->count() <=0) {
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
     * @return \Application\Service\QuestionnaireQuestion
     */
    public function getServiceQuestionnaireQuestion()
    {
        return $this->getServiceLocator()->get('app_service_questionnaire_question');
    }
}