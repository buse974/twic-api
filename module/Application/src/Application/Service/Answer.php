<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Answer extends AbstractService
{
    /**
     * @param integer $question
     * @param integer $questionnaire_user
     * @param integer $scale
     * 
     * @throws \Exception
     * 
     * @return integer
     */
    public function add($question, $questionnaire_user, $questionnaire_question, $scale)
    {
        $m_answer = $this->getModel()
            ->setQuestionId($question)
            ->setQuestionnaireQuestionId($questionnaire_question)
            ->setQuestionnaireUserId($questionnaire_user)
            ->setScale($scale)
            ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_answer) <= 0) {
            throw new \Exception('Error insert add answer');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }
}