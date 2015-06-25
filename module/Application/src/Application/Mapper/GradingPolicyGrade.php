<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;

class GradingPolicyGrade extends AbstractMapper
{
    public function getList($avg = array(), $filter = array(), $search = null)
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
        	'grading_policy_grade$avg' => new Expression('AVG(T.grading_policy_grade$avg)'),
        ))
        ->join('user', 'T.grading_policy_grade$user=user.id', array('id', 'lastname', 'firstname', 'avatar'))
        ->join('course', 'T.grading_policy_grade$course=course.id', array('id', 'title'))
        ->join('program', 'T.grading_policy_grade$program=program.id', array('id', 'name'));
        
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
        if(null !== $search) {
        	if (isset($avg['program'])) {
        		$sel->where(array(' program.name LIKE ? ' => $search . '%'));
        	} elseif (isset($avg['user'])) {
        		$sel->where(array('( user.firstname LIKE ?' => $search . '%'))
        		    ->where(array(' user.lastname LIKE ? )' => $search . '%'), Predicate::OP_OR);
        	} elseif(isset($avg['course'])) {
        		$sel->where(array('course.title LIKE ?' => $search . '%'));
        	} else {
        		$sel->where(array('( user.firstname LIKE ?' => $search . '%'))
        		    ->where(array(' user.lastname LIKE ? ' => $search . '%'), Predicate::OP_OR)
        		    ->where(array(' program.name LIKE ? ' => $search . '%'), Predicate::OP_OR)
        		    ->where(array(' course.title LIKE ? )' => $search . '%'), Predicate::OP_OR);
        	}
        }

        return $this->selectBridge($sel);
    }
}
