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

        $select->columns(array('id', 'name', 'grade', 'grading_policy$nbr_comment' => new Expression('CAST(SUM(IF(grading_policy_grade_comment.id IS NOT NULL, 1, 0)) AS DECIMAL )')))
                ->join('user', 'user.id=user.id', array(), $select::JOIN_CROSS)
                ->join('grading_policy_grade', 'grading_policy_grade.grading_policy_id=grading_policy.id AND grading_policy_grade.user_id=user.id', array('grade'), $select::JOIN_LEFT)        
                ->join('grading_policy_grade_comment', 'grading_policy_grade_comment.grading_policy_grade_id=grading_policy_grade.id', array(), $select::JOIN_LEFT)
                ->where(array('user.id' => $user))
                ->where(array('grading_policy.course_id' => $course))
                ->group('grading_policy.id');

        return $this->selectWith($select);
    }
}
