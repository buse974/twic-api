<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class GradingPolicyGrade extends AbstractMapper
{
    public function getList($avg = array(), $filter = array(), $search = null, $user = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $selectProgram = new Select('program');
        $selectProgram->columns(array('id','name'))
        ->join('course', 'program.id = course.program_id', array('id', 'title'))
        ->join('course_user_relation', 'course.id = course_user_relation.course_id',array())
        ->join('user', 'course_user_relation.user_id = user.id', array('id', 'firstname', 'lastname', 'avatar'))
        ->join('user_role', 'user.id = user_role.user_id',array())
        ->where(array('user_role.role_id' => \Application\Model\Role::ROLE_STUDENT_ID));
        
        $select->columns(array(
                'grading_policy_grade$user' => 'user_id',
                'grading_policy_grade$avg' => new Expression('CAST(SUM(grading_policy.grade * grading_policy_grade.grade) / SUM(grading_policy.grade) AS INTEGER )'),
        ))
        ->join('grading_policy', 'grading_policy_grade.grading_policy_id=grading_policy.id', array('grading_policy_grade$course' => 'course_id'))
        ->join('course', 'course.id=grading_policy.course_id', array('grading_policy_grade$program' => 'program_id'))
        ->group(array('grading_policy.course_id', 'grading_policy_grade.user_id'));

        $sel = new Select(array('T' => $select));
        $sel->columns(array(
            'grading_policy_grade$avg' => new Expression('AVG(T.grading_policy_grade$avg)'),
        ))
        ->join(array('datas' => $selectProgram), 
                'T.grading_policy_grade$user=datas.user$id AND T.grading_policy_grade$course=datas.course$id AND T.grading_policy_grade$program=datas.program$id'
                , array(
                    'user$id'=>'user$id', 
                    'user$lastname'=>'user$lastname', 
                    'user$firstname'=>'user$firstname', 
                    'user$avatar'=>'user$avatar',
                    'course$id'=>'course$id',
                    'course$title'=>'course$title',
                    'program$id'=>'program$id', 
                    'program$name'=>'program$name'
                 )
                ,$sel::JOIN_RIGHT);

        if (isset($avg['program'])) {
            $sel->group('program$id');
        }
        if (isset($avg['user'])) {
            $sel->group('user$id');
        }
        if (isset($avg['course'])) {
            $sel->group('course$id');
        }
        if (isset($filter['program'])) {
            $sel->where(array('program$id' => $filter['program']));
        }
        if (isset($filter['user'])) {
            $sel->where(array('user$id' => $filter['user']));
        }
        if (isset($filter['course'])) {
            $sel->where(array('course$id' => $filter['course']));
        }
        if (null !== $search) {
            if (isset($avg['program'])) {
                $sel->where(array(' program$name LIKE ? ' => $search.'%'));
            } elseif (isset($avg['user'])) {
                $sel->where(array('( user$firstname LIKE ?' => $search.'%'))
                    ->where(array(' user$lastname LIKE ? )' => $search.'%'), Predicate::OP_OR);
            } elseif (isset($avg['course'])) {
                $sel->where(array('course$title LIKE ?' => $search.'%'));
            } else {
                $sel->where(array('( user$firstname LIKE ?' => $search.'%'))
                    ->where(array(' user$lastname LIKE ? ' => $search.'%'), Predicate::OP_OR)
                    ->where(array(' program$name LIKE ? ' => $search.'%'), Predicate::OP_OR)
                    ->where(array(' coursetitle LIKE ? )' => $search.'%'), Predicate::OP_OR);
            }
        }
        if(in_array(\Application\Model\Role::ROLE_STUDENT_STR, $user['roles'])){
            $sel->where(array('user$id' => $user['id']));
        }
        return $this->selectBridge($sel);
    }

}
