<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
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

    public function getBySubmission($submission)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'name', 'grade'))
                ->join('item', 'item.grading_policy_id=grading_policy.id', array(), $select::JOIN_LEFT)
                ->join('submission', 'submission.item_id=item.id', array(), $select::JOIN_LEFT)
                ->where(array('submission.id' => $submission));

        return $this->selectWith($select);
    }
    public function deleteNotIn($ids, $course)
    {
        $delete = $this->tableGateway->getSql()->delete();
        $delete->where(['course_id' => $course]);
        if (null !== $ids && count($ids) > 0) {
            $delete->where->notIn('id', $ids);
        }
        syslog(1, $this->printSql($delete));

        return $this->deleteWith($delete);
    }

    /*
     *     public function getList($avg = array(), $filter = array(), $search = null, $user = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $selectProgram = new Select('program');
        $selectProgram->columns(array('id', 'name'))
            ->join('course', 'program.id = course.program_id', array('id', 'title'))
            ->join('course_user_relation', 'course.id = course_user_relation.course_id', array())
            ->join('user', 'course_user_relation.user_id = user.id', array('id', 'firstname', 'lastname', 'avatar'))
            ->join('user_role', 'user.id = user_role.user_id', array())
            ->where(array('user_role.role_id' => \Application\Model\Role::ROLE_STUDENT_ID))
            ->where(array('program.deleted_date IS NULL'))
            ->where(array('course.deleted_date IS NULL'));  

        if (array_key_exists(\Application\Model\Role::ROLE_ACADEMIC_ID, $user['roles'])) {
            $selectProgram->where(array('program.school_id' => $user['school']['id']));
        } elseif (in_array(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $user['roles'])) {
            $selectProgram->join(array('course_instructor_relation' => 'course_user_relation'), 'course.id = course_instructor_relation.course_id', array())
            ->where(array('course_instructor_relation.user_id' => $user['id']));
        }

        $select->columns(array('grading_policy_grade$user' => 'user_id', 'grading_policy_grade$avg' => new Expression('CAST(SUM(grading_policy.grade * grading_policy_grade.grade) / SUM(grading_policy.grade) AS DECIMAL )')))
            ->join('grading_policy', 'grading_policy_grade.grading_policy_id=grading_policy.id', array('grading_policy_grade$course' => 'course_id'))
            ->join('course', 'course.id=grading_policy.course_id', array('grading_policy_grade$program' => 'program_id'))
            ->group(array('grading_policy.course_id', 'grading_policy_grade.user_id'));

        $sel = new Select(array('T' => $select));
        $sel->columns(array('grading_policy_grade$avg' => new Expression('AVG(T.grading_policy_grade$avg)')))->join(array('datas' => $selectProgram), 'T.grading_policy_grade$user=datas.user$id AND T.grading_policy_grade$course=datas.course$id AND T.grading_policy_grade$program=datas.program$id', array('user$id' => 'user$id', 'user$lastname' => 'user$lastname', 'user$firstname' => 'user$firstname', 'user$avatar' => 'user$avatar', 'course$id' => 'course$id', 'course$title' => 'course$title', 'program$id' => 'program$id', 'program$name' => 'program$name'), $sel::JOIN_RIGHT);

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
                $sel->where(array(' program$name LIKE ? ' => '%'.$search.'%'));
            } elseif (isset($avg['user'])) {
                $sel->where(array('( user$firstname LIKE ?' => '%'.$search.'%'))->where(array(' user$lastname LIKE ? )' => '%'.$search.'%'), Predicate::OP_OR);
            } elseif (isset($avg['course'])) {
                $sel->where(array('course$title LIKE ?' => '%'.$search.'%'));
            } else {
                $sel->where(array('( user$firstname LIKE ?' => '%'.$search.'%'))
                    ->where(array(' user$lastname LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array(' program$name LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array(' course$title LIKE ? )' => '%'.$search.'%'), Predicate::OP_OR);
            }
        }
        if (in_array(\Application\Model\Role::ROLE_STUDENT_STR, $user['roles'])) {
            $sel->where(array('user$id' => $user['id']));
        }

        return $this->selectBridge($sel);
    }

    public function updateGrade($submission, $user = null)
    {
        $res = [];

        $subselect = new Select('grading_policy');
        $subselect->columns(array('id'))
            ->join('item', 'grading_policy.id = item.grading_policy_id', array())
            ->join('submission', 'item.id = submission.item_id', array())
            ->where(array('submission.id' => $submission));

        $selectgp = new Select('grading_policy');
        $selectgp->columns(array('grade' => new Expression('SUM(submission_user.grade) / COUNT(submission_user.grade)')))
            ->join('item', 'grading_policy.id = item.grading_policy_id', array())
            ->join('submission', 'item.id = submission.item_id', array())
            ->join('submission_user', 'submission.id = submission_user.submission_id ', array())
            ->where(array('grading_policy.id' => $subselect));

        if(null !== $user){
            $selectgp->where(array('submission_user.user_id' => $user));
        }
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->where(array('user_id' => $user))
            ->where(array('grading_policy_id' => $subselect));

        if ($this->selectWith($select)->count() > 0) {
            $update = $this->tableGateway->getSql()->update();
            $update->set(array('grade' => $selectgp))
                ->where(array('user_id' => $user))
                ->where(array('grading_policy_id' => $subselect));
            $res = $this->updateWith($update);
        } else {
            $insert = $this->tableGateway->getSql()->insert();
            $insert->values(array('grade' => $selectgp, 'user_id' => $user, 'grading_policy_id' => $subselect, 'created_date' => (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')));

            $res = $this->insertWith($insert);
        }

        return $res;
    }
     */
}
