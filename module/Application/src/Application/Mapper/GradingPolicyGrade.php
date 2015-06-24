<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class GradingPolicyGrade extends AbstractMapper
{
    public function getList($avg = array(), $filter = array())
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array(
                'grading_policy_grade$user' => 'user_id',
                'grading_policy_grade$avg' => new Expression('SUM(grading_policy.grade/100 * grading_policy_grade.grade)'),
        ))
        ->join('grading_policy', 'grading_policy_grade.grading_policy_id=grading_policy.id', array('grading_policy_grade$course' => 'course_id'))
        ->join('course', 'course.id=grading_policy.course_id', array('grading_policy_grade$program' => 'program_id'))
        ->group(array('grading_policy.course_id', 'grading_policy_grade.user_id'));

        $sel = new Select(array('T' => $select));
        $sel->columns(array(
                'grading_policy_grade$program' => 'grading_policy_grade$program',
                'grading_policy_grade$course' => 'grading_policy_grade$course',
                'grading_policy_grade$user' => 'grading_policy_grade$user',
                'grading_policy_grade$avg' => new Expression('AVG(T.grading_policy_grade$avg)'),
        ))
        ->join('grading', 'T.grading_policy_grade$avg BETWEEN grading.min AND grading.max', array('grading_policy_grade$letter' => 'letter'), $select::JOIN_LEFT);

        if (isset($avg['program'])) {
            $sel->group('grading_policy_grade$program');
        }
        if (isset($avg['user'])) {
            $sel->group('grading_policy_grade$user');
        }
        if (isset($avg['course'])) {
            $sel->group('grading_policy_grade$course');
        }
        if (isset($filter['program'])) {
            $sel->where(array('grading_policy_grade$program' => $filter['program']));
        }
        if (isset($filter['user'])) {
            $sel->where(array('grading_policy_grade$user' => $filter['user']));
        }
        if (isset($filter['course'])) {
            $sel->where(array('grading_policy_grade$course' => $filter['course']));
        }

        return $this->tableGateway->selectBridge($sel);
    }
}
