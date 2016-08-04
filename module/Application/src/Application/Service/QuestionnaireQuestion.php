<?php
/**
 * TheStudnet (http://thestudnet.com).
 *
 * Questionnaire Question
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class QuestionnaireQuestion.
 */
class QuestionnaireQuestion extends AbstractService
{
    /**
     * Create Questionnaire Question.
     * 
     * @param int $questionnaire
     *
     * @return bool
     */
    public function create($questionnaire)
    {
        $m_questionnaire_question = $this->getModel()->setQuestionnaireId($questionnaire);

        $res_component = $this->getServiceComponent()->getList();
        foreach ($res_component as $m_component) {
            $m_question = $this->getServiceQuestion()->getRand($m_component->getId());
            $m_questionnaire_question->setQuestionId($m_question->getId());

            $this->getMapper()->insert($m_questionnaire_question);
        }

        return true;
    }

    /**
     * Get Questionnaire Question.
     * 
     * @param int $questionnaire
     * @param int $question
     *
     * @return \Application\Model\QuestionnaireQuestion
     */
    public function getByQuestion($questionnaire, $question)
    {
        $m_questionnaire_question = $this->getModel()->setQuestionnaireId($questionnaire)->setQuestionId($question);

        return $this->getMapper()->select($m_questionnaire_question)->current();
    }

    /**
     * Get Service Component.
     * 
     * @return \Application\Service\Component
     */
    private function getServiceComponent()
    {
        return $this->getServiceLocator()->get('app_service_component');
    }

    /**
     * Get Service Question.
     * 
     * @return \Application\Service\Question
     */
    private function getServiceQuestion()
    {
        return $this->getServiceLocator()->get('app_service_question');
    }
}
