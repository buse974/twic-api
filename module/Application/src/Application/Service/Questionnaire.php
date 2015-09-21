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
     * @param integer $item_prog            
     */
    public function getByItemProg($item_prog)
    {
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);

        if($m_item_prog->getItem()->getType() !== CI::TYPE_WORKGROUP) {
            throw new  \Exception("No Workgroup");
        }
        
        $res_questionnaire = $this->getMapper()->getByItem($m_item_prog->getItem()->getId());
        
        if ($res_questionnaire->count() <= 0) {
            $this->create($m_item_prog->getItem()->getId());
            $res_questionnaire = $this->getMapper()->getByItem($m_item_prog->getItem()->getId());
        }
        
        $m_questionnaire = $res_questionnaire->current();
        
        $m_questionnaire->setQuestions($this->getServiceQuestion()
            ->getList($m_questionnaire->getId()));
        
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($m_questionnaire->getId());
        
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
        $m_questionnaire = $this->getMapper()->getByItem($m_item_prog->getItem()->getId())->current();
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($m_questionnaire->getId());
        $m_questionnaire_question = $this->getServiceQuestionnaireQuestion()->getByQuestion($m_questionnaire->getId(), $question);
        
        $ret = $this->getServiceAnswer()->add(
            $question, 
            $m_questionnaire_user->getId(), 
            $m_questionnaire_question->getId(), 
            $user, 
            $scale);
        
        $nbrq = $this->getNbrQuestionNoCompleted($item_prog);
        if(is_numeric($nbrq) && $nbrq == 0) {
            $this->getServiceItemProgUser()->end($item_prog);
            
            $has_all_finish = $this->getServiceItemProgUser()->checkAllFinish($item_prog);
            if($has_all_finish) {
                $this->getServiceItemAssignment()->submitByItemProg($item_prog);
                $this->getServiceEvent()->eqcqAvailable($item_prog);
            }
        }
        
        return $ret;
    }

    /**
     * 
     * @param integer $item_prog
     * @return NULL|integer
     */
    public function getNbrQuestionNoCompleted($item_prog)
    {
        $nbr = null;
        $user = $this->getServiceUser()->getIdentity()['id'];
        $res_questionnaire = $this->getMapper()->getNbrQuestionNoCompleted($item_prog, $user);
        
        if($res_questionnaire->count() > 0) {
            $nbr = $res_questionnaire->current()->getNbNoCompleted();
            if($nbr instanceof IsNull) {
                $nbr = null;
            }
        }
        
        return $nbr;
    }
    
    /**
     *
     * @invokable
     *
     * @param integer $item_prog            
     * @param integer $user            
     */
    public function getAnswer($item_prog, $user = null)
    {
        if (null === $user) {
            $user = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $m_item_prog = $this->getServiceItemProg()->get($item_prog);
        $m_questionnaire = $this->getMapper()->getByItem($m_item_prog->getItem()->getId())->current();
        $m_questionnaire_user = $this->getServiceQuestionnaireUser()->get($m_questionnaire->getId());
        
        $m_questionnaire_user->setAnswers($this->getServiceAnswer()
            ->getByQuestionnaireUser($m_questionnaire_user->getId()));
        
        return $m_questionnaire_user;
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
     * @return \Application\Service\ItemProgUser
     */
    public function getServiceItemProgUser()
    {
        return $this->getServiceLocator()->get('app_service_item_prog_user');
    }
    
    /**
     *
     * @return \Application\Service\ItemAssignment
     */
    public function getServiceItemAssignment()
    {
        return $this->getServiceLocator()->get('app_service_item_assignment');
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
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->getServiceLocator()->get('app_service_event');
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