<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class SubmissionUserCriteria extends AbstractMapper
{
    public function getProcessedGrades($submission)
    {
        $select = new Select('pg_user_criteria');
        $select->columns(['submission_user_criteria$user_id' => 'user_id', 'submission_user_criteria$criteria_id' => 'criteria_id', 'submission_user_criteria$points' => new Expression('IF(COUNT(DISTINCT pg_user_criteria.pg_id) = COUNT(DISTINCT submission_pg.user_id), AVG(pg_user_criteria.points), NULL)')])
            ->join('submission', 'pg_user_criteria.submission_id = submission.id', [])
            ->join('submission_pg', 'submission_pg.submission_id = submission.id', [])
            ->join(
                'submission_user_criteria',
                'submission_user_criteria.submission_id = pg_user_criteria.submission_id AND submission_user_criteria.criteria_id = pg_user_criteria.criteria_id',
                [], $select::JOIN_LEFT
            )
            ->join('item', 'submission.item_id = item.id', [])
            ->join('opt_grading', 'opt_grading.item_id = item.id', [])
            ->where(['opt_grading.mode' => 'average'])
            ->where(['pg_user_criteria.submission_id' => $submission])
            ->where(['( submission_user_criteria.overwritten IS NULL '])
            ->where([' submission_user_criteria.overwritten = FALSE )'], Predicate::OP_OR)
            ->group(['pg_user_criteria.criteria_id', 'pg_user_criteria.user_id']);

        return $this->selectWith($select);
    }
}
