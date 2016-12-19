<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class SubQuiz extends AbstractMapper
{
    public function checkFinish($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns([])
            ->join('sub_question', 'sub_question.sub_quiz_id=sub_quiz.id', ['id'])
            ->where(array('sub_quiz.id' => $id))
            ->where(array('sub_question.answered_date IS NULL'));

        $res = $this->selectWith($select);
        if ($res->count() === 0) {
            $update = $this->tableGateway->getSql()->update();
            $update->set(['end_date' => new Expression('UTC_TIMESTAMP()')])
                ->where(['sub_quiz.id' => $id]);

            $this->updateWith($update);

            return true;
        }

        return false;
    }

    /**
     * @param int  $id
     * @param int  $submission_id
     * @param int  $user_id
     * @param bool $is_finish
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($id = null, $submission_id = null, $user_id = null, $is_finish = null, $is_timeover = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id',
            'poll_id',
            'sub_quiz$start_date' => new Expression('DATE_FORMAT(sub_quiz.start_date, "%Y-%m-%dT%TZ")'),
            'sub_quiz$end_date' => new Expression('DATE_FORMAT(sub_quiz.end_date, "%Y-%m-%dT%TZ")'),
            'user_id',
            'submission_id',
            'grade', ]);

        if (null !== $id) {
            $select->where(array('sub_quiz.id' => $id));
        }
        if (null !== $submission_id) {
            $select->where(array('sub_quiz.submission_id' => $submission_id));
        }
        if (null !== $user_id) {
            $select->where(array('sub_quiz.user_id' => $user_id));
        }
        if ($is_finish === true) {
            $select->where(array('sub_quiz.end_date IS NOT NULL'));
        }
        if ($is_finish === false) {
            $select->where(array('sub_quiz.end_date IS NULL'));
        }
        if ($is_timeover === true) {
            $select->join('poll', 'poll.id=sub_quiz.poll_id', [])
                   ->join('item', 'item.id=poll.item_id', [])
                   ->where(['sub_quiz.end_date IS NULL AND sub_quiz.grade IS NULL 
                            AND (
                            (poll.expiration_date IS NOT NULL AND poll.expiration_date < UTC_TIMESTAMP())
                            OR
                            (poll.time_limit IS NOT NULL AND DATE_ADD(sub_quiz.start_date, INTERVAL poll.time_limit MINUTE) < UTC_TIMESTAMP())
                            OR 
                            (item.cut_off IS NOT NULL AND item.cut_off < UTC_TIMESTAMP())
                            OR 
                            (item.cut_off IS NULL AND item.end IS NOT NULL AND item.end < UTC_TIMESTAMP()))']);
        }

        return  $this->selectWith($select);
    }

    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns([
            'id',
            'poll_id',
            'sub_quiz$start_date' => new Expression('DATE_FORMAT(sub_quiz.start_date, "%Y-%m-%dT%TZ")'),
            'sub_quiz$end_date' => new Expression('DATE_FORMAT(sub_quiz.end_date, "%Y-%m-%dT%TZ")'),
            'user_id',
            'submission_id',
            'grade', ])
            ->where(array('sub_quiz.id' => $id));

        return  $this->selectWith($select);
    }
}
