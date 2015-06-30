<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Expression;

class GradingPolicy extends AbstractMapper
{
    public function getListByCourse($course, $user)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'name', 'grade', 'grading_policy$nbr_comment' => new Expression('CAST(SUM(IF(grading_policy_grade_comment.id IS NOT NULL, 1, 0)) AS INTEGER )')))
                ->join('grading_policy_grade', 'grading_policy_grade.grading_policy_id=grading_policy.id', array('grade'), $select::JOIN_LEFT)
                ->join('grading_policy_grade_comment', 'grading_policy_grade_comment.grading_policy_grade_id=grading_policy_grade.id', array(), $select::JOIN_LEFT)
                ->where(array(' ( grading_policy_grade.user_id = ? ' => $user))
                ->where(array(' grading_policy_grade.user_id IS NULL ) '), Predicate::OP_OR)
                ->where(array('grading_policy.course_id' => $course))
                ->group('grading_policy.id');

        return $this->selectWith($select);
    }
}
