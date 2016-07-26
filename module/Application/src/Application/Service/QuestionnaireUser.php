<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Questionnaire User
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Calss QuestionnaireUser
 */
class QuestionnaireUser extends AbstractService
{
    /**
     * Get Questionnaire User 
     * 
     * @param unknown $questionnaire_id
     * @param unknown $item_id
     * @throws \Exception
     * @return \Application\Model\QuestionnaireUser
     */
    public function get($questionnaire_id, $item_id)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];

        $m_questionnaire_user = $this->getModel()
            ->setUserId($me)
            ->setQuestionnaireId($questionnaire_id);

        $res_questionnaire_user = $this->getMapper()->select($m_questionnaire_user);
        if ($res_questionnaire_user->count() <= 0) {
            $m_questionnaire_user
                ->setSubmissionId($this->getServiceSubmission()->getByUserAndQuestionnaire($me, $questionnaire_id, $item_id)->getId())
                ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
            if ($this->getMapper()->insert($m_questionnaire_user) <= 0) {
                throw new \Exception('Error insert questionnaire user');
            }

            $res_questionnaire_user = $this->getMapper()->select($m_questionnaire_user);
        }

        return $res_questionnaire_user->current();
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
     * Get Service Submission
     * 
     * @return \Application\Service\Submission
     */
    private function getServiceSubmission()
    {
        return $this->getServiceLocator()->get('app_service_submission');
    }
}
