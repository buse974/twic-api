<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Answer
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Answer
 */
class Answer extends AbstractService
{

    /**
     * Add Answer
     *
     * @param int $question_id            
     * @param int $questionnaire_user_id            
     * @param int $questionnaire_question_id            
     * @param int $peer_id            
     * @param int $scale_id            
     * @throws \Exception
     * @return int
     */
    public function add($question_id, $questionnaire_user_id, $questionnaire_question_id, $peer_id, $scale_id)
    {
        $m_answer = $this->getModel()
            ->setQuestionId($question_id)
            ->setQuestionnaireQuestionId($questionnaire_question_id)
            ->setQuestionnaireUserId($questionnaire_user_id)
            ->setScaleId($scale_id)
            ->setPeerId($peer_id)
            ->setType((($peer_id == $this->getServiceUser()
            ->getIdentity()['id']) ? 'SELF' : 'PEER'))
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_answer) <= 0) {
            throw new \Exception('Error insert add answer');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * Get List Answer
     *
     * @invokable
     *
     * @param integer $submission_id            
     * @param integer $peer            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($submission_id = null, $peer = null)
    {
        $user_id = $this->getServiceUser()->getIdentity()['id'];
        
        return $this->getMapper()->getList($submission_id, $user_id, $peer);
    }

    /**
     * Get Answer By QuestionnaireUser
     *
     * @param int $questionnaire_user_id            
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getByQuestionnaireUser($questionnaire_user_id)
    {
        return $this->getMapper()->select($this->getModel()
            ->setQuestionnaireUserId($questionnaire_user_id));
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
}
