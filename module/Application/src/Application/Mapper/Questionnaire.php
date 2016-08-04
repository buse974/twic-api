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
            'questionnaire$created_date' => new Expression('DATE_FORMAT(questionnaire.created_date, "%Y-%m-%dT%TZ")'), ))
        ->where(array('questionnaire.item_id' => $item));

        return $this->selectWith($select);
    }

    public function getNbrTotal($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('questionnaire$nb_no_completed' => new Expression('COUNT(1)')))
            ->join('questionnaire_question', 'questionnaire_question.questionnaire_id = questionnaire.id', [])
            ->join('question', 'questionnaire_question.question_id = question.id', [])
            ->join('submission', 'submission.item_id = questionnaire.item_id', [])
            ->join('submission_user', 'submission_user.submission_id = submission.id', [])
            ->where(array('submission_user.start_date IS NOT NULL AND questionnaire.item_id = ? ' => $item));

        return $this->selectWith($select);
    }

    public function getNbrQuestionCompleted($item, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('questionnaire$nb_no_completed' => new Expression('COUNT(1)')))
            ->join('questionnaire_question', 'questionnaire_question.questionnaire_id = questionnaire.id', [])
            ->join('question', 'questionnaire_question.question_id = question.id', [])
            ->join('submission', 'submission.item_id = questionnaire.item_id', [])
            ->join('submission_user', 'submission_user.submission_id = submission.id', [])
            ->join('answer', 'answer.question_id=question.id AND answer.peer_id = submission_user.user_id AND answer.questionnaire_question_id = questionnaire_question.id', [], $select::JOIN_LEFT)
            ->join('questionnaire_user', 'questionnaire_user.id = answer.questionnaire_user_id', [], $select::JOIN_LEFT)
            ->where(array('submission_user.start_date IS NOT NULL AND questionnaire.item_id = ? ' => $item))
            ->where(array('questionnaire_user.user_id' => $user));

        return $this->selectWith($select);
    }
}
