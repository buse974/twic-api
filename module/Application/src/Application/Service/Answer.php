<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Answer extends AbstractService
{

    /**
     * 
     * @param integer $question
     * @param integer $questionnaire_user
     * @param integer $questionnaire_question
     * @param integer $peer
     * @param integer $scale
     * @throws \Exception
     * @return integer
     */
    public function add($question, $questionnaire_user, $questionnaire_question, $peer, $scale)
    {
        $m_answer = $this->getModel()
            ->setQuestionId($question)
            ->setQuestionnaireQuestionId($questionnaire_question)
            ->setQuestionnaireUserId($questionnaire_user)
            ->setScaleId($scale)
            ->setPeerId($peer)
            ->setType((($peer==$this->getServiceUser()->getIdentity()['id']) ? 'SELF': 'PEER'))
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_answer) <= 0) {
            throw new \Exception('Error insert add answer');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     * 
     * @param integer $item_prog
     * @param integer $peer
     */
    public function getList($item_prog = null, $peer = null)
    {
        return $this->getMapper()->getList($item_prog, $peer);    
    }

    /**
     * 
     * @param integer $questionnaire_user
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByQuestionnaireUser($questionnaire_user)
    {
        $m_answer = $this->getModel()->setQuestionnaireUserId($questionnaire_user);

        return $this->getMapper()->select($m_answer);
    }
    
    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}