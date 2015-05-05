<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class User extends AbstractMapper
{
    public function get($user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'id',
            'firstname',
            'lastname',
            'email',
            'password',
            'birth_date',
            'position',
            'interest',
            'avatar',
            'school_id',
        ))
            ->join('school', 'school.id=user.school_id', array(
            'name',
            'short_name',
            'logo',
        ), $select::JOIN_LEFT)
            ->where(array(
            'user.id' => $user,
        ));

        return $this->selectWith($select);
    }

    public function getList($filter = null, $school = null, $user_school = null, $type = null, $level = null, $course = null, $program = null, $search = null, $nopragram = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'id',
            'firstname',
            'lastname',
            'email',
            'password',
            'birth_date',
            'position',
            'interest',
            'avatar',
        ))
            ->join('school', 'school.id=user.school_id', array(
            'name',
            'short_name',
            'logo',
        ), $select::JOIN_LEFT)
            ->quantifier('DISTINCT');

        if ($school !== null) {
            $select->where(array(
                'school.id' => $school,
            ));
        }

        if ($user_school) {
            $sub_select = $this->tableGateway->getSql()->select();
            $sub_select->columns(array(
                'school_id',
            ))->where(array(
                'user.id' => $user_school,
            ));
            $select->where(array(
                'school.id' => $sub_select,
            ));
        }

        if ($type !== null) {
            if (!is_array($type)) {
                $type = array($type => true);
            }
            $ts = array();
            foreach ($type as $key => $t) {
                if ($t) {
                    $ts[] = $key;
                }
            }

            if (!empty($ts)) {
                $select->join('user_role', 'user_role.user_id=user.id', array())
                    ->join('role', 'user_role.role_id=role.id', array())
                    ->where(array(
                    'role.name' => $ts,
                ));
            }
        }

        if ($program !== null || $level !== null || $course !== null || $search !== null || $nopragram !== null) {
            $select->join('program_user_relation', 'program_user_relation.user_id=user.id', array(), $select::JOIN_LEFT);
        }
        if ($level !== null || $course !== null || $search !== null) {
            $select->join('program', 'program_user_relation.program_id=program.id', array(), $select::JOIN_LEFT);
        }

        if ($course !== null) {
            $select->join('course', 'course.program_id=program.id', array(), $select::JOIN_INNER)->where(array(
                'course.id' => $course,
            ));
        }

        if ($program) {
            $select->where(array(
                'program_user_relation.program_id' => $program,
            ));
        }

        if ($nopragram) {
            if (!is_array($nopragram)) {
                foreach ($nopragram as $np) {
                    $select->where(array('program_user_relation.program_id <> ? ' => $np));
                }
            }
        }

        if ($level) {
            $select->where(array(
                'program.level' => $level,
            ));
        }

        if (!empty($search)) {
            $select->where(array('(program.deleted_date IS NULL && program.name LIKE ? ' => ''.$search.'%'));
            $select->where(array('user.firstname LIKE ? ' => ''.$search.'%'), Predicate::OP_OR);
            $select->where(array('user.lastname LIKE ? )' => ''.$search.'%'), Predicate::OP_OR);
        }

        $select->where('user.deleted_date IS NULL');

        return $this->selectWith($select);
    }
}
