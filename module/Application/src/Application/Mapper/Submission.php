<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Submission extends AbstractMapper
{
    /**
     * 
     * @param integer $user
     * @param integer $questionnaire
     * 
     * @return \Application\Model\Submission
     */
    public function getByUserAndQuestionnaire($user, $questionnaire)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('questionnaire', 'questionnaire.item_id=submission.item_id', array())
            ->join('submission_user', 'submission_user.submission_id=submission.id', array())
            ->where(array('submission_user.user_id' => $user))
            ->where(array('questionnaire.id' => $questionnaire));
    
        return $this->selectWith($select)->current();
    }
}