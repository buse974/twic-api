<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class Questionnaire extends AbstractMapper
{
    public function getByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'id', 
            'item_id', 
            'max_duration', 
            'max_time', 
            'questionnaire$created_date' => new Expression('DATE_FORMAT(questionnaire.created_date, "%Y-%m-%dT%TZ")')))
        ->where(array('questionnaire.item_id' => $item));

        return $this->selectWith($select);
    }

    public function getNbrQuestionNoCompleted($item, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('questionnaire$nb_no_completed' => new Expression('COUNT(1) - SUM(IF(answer.scale_id IS NULL, 0,1))')))
            ->join('questionnaire_question', 'questionnaire_question.questionnaire_id = questionnaire.id', array())
            ->join('question', 'questionnaire_question.question_id = question.id', array())
            ->join('submission', 'submission.item_id = questionnaire.item_id', array())
            ->join('submission_user', 'submission_user.submission_id = submission.id', array())
            ->join('questionnaire_user', 'questionnaire_user.questionnaire_id = questionnaire.id AND questionnaire_user.user_id = submission_user.user_id', array(), $select::JOIN_LEFT)
            ->join('answer', 'answer.questionnaire_user_id = questionnaire_user.id AND answer.question_id = question.id AND submission_user.user_id = answer.peer_id', array(), $select::JOIN_LEFT)
            ->where(array('questionnaire.item_id' => $item))
            ->where(array('submission_user.user_id' => $user));

        return $this->selectWith($select);
    }
}
