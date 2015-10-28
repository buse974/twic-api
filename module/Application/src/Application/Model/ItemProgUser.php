<?php

namespace Application\Model;

use Application\Model\Base\ItemProgUser as BaseItemProgUser;

class ItemProgUser extends BaseItemProgUser
{
    protected $item_prog;
    protected $questionnaire;
    protected $questionnaire_user;

    public function exchangeArray(array &$data)
    {
        parent::exchangeArray($data);

        $this->item_prog = $this->requireModel('app_model_item_prog', $data);
        $this->questionnaire = $this->requireModel('app_model_questionnaire', $data);
        $this->questionnaire_user = $this->requireModel('app_model_questionnaire_user', $data);
        
    }

    public function getQuestionnaireUser()
    {
        return $this->questionnaire_user;
    }
    
    public function setQuestionnaireUser($questionnaire_user)
    {
        $this->questionnaire_user = $questionnaire_user;
    
        return $this;
    }
    
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire($questionnaire)
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    public function getItemProg()
    {
        return $this->item_prog;
    }

    public function setItemProg($item_prog)
    {
        $this->item_prog = $item_prog;

        return $this;
    }
}
