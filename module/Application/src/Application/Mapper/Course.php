<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class Course extends AbstractMapper
{
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture', 'objectives', 'teaching', 'attendance', 'duration', 'video_link', 'video_token',
            'learning_outcomes',
            'course$start_date' => new Expression('DATE_FORMAT(MIN(start_date), "%Y-%m-%dT%TZ")'),
            'course$end_date' => new Expression('DATE_FORMAT(MAX(start_date), "%Y-%m-%dT%TZ")'),
            'notes', ))
            ->join('item', 'item.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join('item_prog', 'item_prog.item_id=item.id', array(), $select::JOIN_LEFT)
            ->join(array('course_user' => 'user'), 'course_user.id=course.creator_id', array('id', 'firstname', 'lastname', 'email'))
            ->join(array('course_user_school' => 'school'), 'course_user_school.id=course_user.school_id', array('id', 'name', 'logo'), $select::JOIN_LEFT)
            ->join(array('course_program' => 'program'), 'course_program.id=course.program_id', array('id', 'name'))
            ->join(array('course_school' => 'school'), 'course_program.school_id=course_school.id', array('id', 'name', 'logo'), $select::JOIN_LEFT)
            ->where(array('course.id' => $id))
            ->group('course.id');

        return $this->selectWith($select);
    }

    public function getList($program = null, $search = null, $filter = null, $is_academic = false, $user = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture', 'objectives', 'teaching', 'attendance', 'duration', 'video_link', 'video_token', 'learning_outcomes', 'notes', 'program_id', 'course$start_date' => new Expression('DATE_FORMAT(MIN(start_date), "%Y-%m-%dT%TZ")'), 'course$end_date' => new Expression('DATE_FORMAT(MAX(start_date), "%Y-%m-%dT%TZ")')))
            ->join('item', 'item.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join('item_prog', 'item_prog.item_id=item.id', array(), $select::JOIN_LEFT)
            ->join('program', 'program.id=course.program_id', array())
            ->where(array('course.deleted_date IS NULL'))
            ->where(array('program.deleted_date IS NULL'))
            ->group('course.id');

        if ($program) {
            $select->where(array('course.program_id' => $program));
        }

        if (null !== $user && $is_academic === false) {
            $select->join('course_user_relation', 'course_user_relation.course_id=course.id', [])->where(['course_user_relation.user_id' => $user]);
        }

        if (null == !$search) {
            $select->where(array('course.title LIKE ? ' => '%'.$search.'%'));
        }

        return $this->selectWith($select);
    }

    /**
     * @param int $user
     */
    public function getListDetail($user, $me)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture'))
            ->join('program', 'course.program_id=program.id', array('id', 'name'), $select::JOIN_INNER)
            ->join('item', 'item.course_id=course.id', array(), $select::JOIN_INNER)
            ->join('item_prog', 'item_prog.item_id=item.id', array(), $select::JOIN_INNER)
            ->join('item_prog_user', 'item_prog.id=item_prog_user.item_prog_id', array(), $select::JOIN_INNER)
            ->join('grading_policy', 'grading_policy.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join('grading_policy_grade', 'grading_policy.id=grading_policy_grade.grading_policy_id AND grading_policy_grade.user_id=item_prog_user.user_id', array('course$avg' => new Expression('CAST(SUM(grading_policy.grade * grading_policy_grade.grade)/SUM(IF(grading_policy_grade.grade IS NULL,0, grading_policy.grade)) AS DECIMAL )')), $select::JOIN_LEFT)
            ->where(array('item_prog_user.user_id' => $user))
            ->group('course.id');

        if (in_array(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $me['roles'])) {
            $select->join('course_user_relation', 'course.id = course_user_relation.course_id', array())->where(array('course_user_relation.user_id' => $me['id']));
        } elseif (in_array(\Application\Model\Role::ROLE_ACADEMIC_STR, $me['roles'])) {
            $select->where(array('program.school_id ' => $me['school']['id']));
        }

        return $this->selectWith($select);
    }

    /**
     * @param int $user
     */
    public function getListRecord($user, $is_student = false)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture'))
            ->join('program', 'course.program_id=program.id', array('id', 'name'), $select::JOIN_INNER)
            ->join(array('course_school' => 'school'), 'program.school_id=course_school.id', array('id', 'logo', 'name'), $select::JOIN_INNER)
            ->join('course_user_relation', 'course_user_relation.course_id=course.id', array(), $select::JOIN_INNER)
            ->join('item', 'item.course_id=course.id', array(), $select::JOIN_INNER)
            ->join('item_prog', 'item_prog.item_id=item.id', array(), $select::JOIN_INNER)
            ->join('videoconf', 'item_prog.id=videoconf.item_prog_id', array(), $select::JOIN_INNER)
            ->join('videoconf_archive', 'videoconf.id=videoconf_archive.videoconf_id', array(), $select::JOIN_INNER)
            ->where(array('course_user_relation.user_id' => $user))
            ->where(array('videoconf_archive.archive_link IS NOT NULL'))
            ->group('course.id');

        if ($is_student !== false) {
            $select->join('item_prog_user', 'item_prog.id=item_prog_user.item_prog_id', array(), $select::JOIN_INNER)->where(array('item_prog_user.user_id' => $user));
        }

        return $this->selectWith($select);
    }
}
