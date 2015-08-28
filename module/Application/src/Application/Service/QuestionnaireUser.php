<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class QuestionnaireUser extends AbstractService
{
    /**
     * 
     * @param integer $user
     * @param integer $questionnaire
     * @throws \Exception
     * 
     * @return \Application\Model\QuestionnaireUser
     */
    public function get($user, $questionnaire)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        
        $m_questionnaire_user = $this->getModel()
            ->setUserId($me)
            ->setPeerId($user)
            ->setQuestionnaireId($questionnaire);
        
        $res_questionnaire_user = $this->getMapper()->select($m_questionnaire_user);
        
        if ($res_questionnaire_user->count() <= 0) {
            if ($this->getMapper()->insert($m_questionnaire_user) <= 0) {
                throw new \Exception('Error insert questionnaire user');
            }
            
            $res_questionnaire_user = $this->getMapper()->select($m_questionnaire_user);
        }
        
        return $res_questionnaire_user->current();
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