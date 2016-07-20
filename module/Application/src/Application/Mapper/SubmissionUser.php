<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;
use function foo\func;

class SubmissionUser extends AbstractMapper
{
    public function getListGrade($avg = array(), $filter = array(), $search = null, $user = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'submission_user$avg' => new Expression('SUM(item.coefficient * submission_user.grade) / SUM(item.coefficient)'), ))
            ->join('submission', 'submission_user.submission_id=submission.id', [])
            ->join('item', 'submission.item_id=item.id', [])
            ->join('user', 'submission_user.user_id=user.id', ['id', 'firstname', 'lastname', 'avatar'])
            ->join('course', 'item.course_id = course.id', array('id', 'title'))
            ->join('program', 'course.program_id = program.id', array('id', 'name'))
            ->where(array('program.deleted_date IS NULL'))
            ->where(array('course.deleted_date IS NULL'))
            ->where(array('submission_user.grade IS NOT NULL'));

        if (isset($avg['program'])) {
            $select->group('program.id');
        }
        if (isset($avg['user'])) {
            $select->group('user.id');
        }
        if (isset($avg['course'])) {
            $select->group('course.id');
        }
        if (isset($filter['program'])) {
            $select->where(array('program.id' => $filter['program']));
        }
        if (isset($filter['user'])) {
            $select->where(array('user.id' => $filter['user']));
        }
        if (isset($filter['course'])) {
            $select->where(array('course.id' => $filter['course']));
        }
        if (!empty($search)) {
            if (isset($avg['program'])) {
                $select->where(array(' program.name LIKE ? ' => '%'.$search.'%'));
            } elseif (isset($avg['user'])) {
                $select->where(array('( user.firstname LIKE ?' => '%'.$search.'%'))->where(array(' user.lastname LIKE ? )' => '%'.$search.'%'), Predicate::OP_OR);
            } elseif (isset($avg['course'])) {
                $select->where(array('course.title LIKE ?' => '%'.$search.'%'));
            } else {
                $select->where(array('( user$firstname LIKE ?' => '%'.$search.'%'))
                    ->where(array(' user.lastname LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array(' program.name LIKE ? ' => '%'.$search.'%'), Predicate::OP_OR)
                    ->where(array(' course.title LIKE ? )' => '%'.$search.'%'), Predicate::OP_OR);
            }
        }

        /*
            if (in_array(\Application\Model\Role::ROLE_STUDENT_STR, $user['roles'])) {
                $sel->where(array('user$id' => $user['id']));
            }
            
            if (array_key_exists(\Application\Model\Role::ROLE_ACADEMIC_ID, $user['roles'])) {
                $selectProgram->where(array('program.school_id' => $user['school']['id']));
            } elseif (in_array(\Application\Model\Role::ROLE_INSTRUCTOR_STR, $user['roles'])) {
                $selectProgram->join(array('course_instructor_relation' => 'course_user_relation'), 'course.id = course_instructor_relation.course_id', array())
                    ->where(array('course_instructor_relation.user_id' => $user['id']));
            }
        */

        //return $this->selectBridge($select);
        return $this->selectWith($select);
    }

    /**
     * @param int $submission_id
     * @param int $user_id
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getListBySubmissionId($submission_id, $user_id)
    {
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'submission_id',
            'user_id',
            'grade',
            'submission_user$submit_date' => new Expression('DATE_FORMAT(submission_user.submit_date, "%Y-%m-%dT%TZ")'),
            'submission_user$start_date' => new Expression('DATE_FORMAT(submission_user.start_date, "%Y-%m-%dT%TZ")'),
        ])
            ->join('user', 'user.id=submission_user.user_id', [
                'user$id' => new Expression('user.id'),
                'firstname',
                'gender',
                'lastname',
                'email',
                'has_email_notifier',
                'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
                'position',
                'interest',
                'avatar',
                'school_id',
                'user$contact_state' => $this->getMapperUser()->getSelectContactState($user_id),
                'user$contacts_count' => $this->getMapperUser()->getSelectContactCount(),
            ])
            ->where(array('submission_user.submission_id' => $submission_id));

        return $this->selectWith($select);
    }

    /**
     * 
     * @param unknown $item_id
     */
    public function getListByItemId($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'user_id'))
            ->join('submission', 'submission_user.submission_id=submission.id', [])
            ->where(array('submission.item_id' => $item_id));
        
        return $this->selectWith($select);
    }
    
    /**
     * @param int $submission
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getProcessedGrades($submission)
    {
        $select = new Select('submission_user_criteria');
        $select->columns([
            'submission_user$submission_id' => 'submission_id',
            'submission_user$user_id' => 'user_id',
            'submission_user$grade' => new Expression('IF(COUNT(DISTINCT criteria.id) = COUNT(DISTINCT submission_user_criteria.criteria_id), ROUND(SUM(submission_user_criteria.points) * 100 / SUM(criteria.points)), NULL)'), ])
           ->join('submission', 'submission_user_criteria.submission_id = submission.id', [])
           ->join('item', 'submission.item_id = item.id', [])
           ->join('grading_policy', 'item.grading_policy_id = grading_policy.id', [])
           ->join('criteria', 'criteria.grading_policy_id = grading_policy.id', [])
           ->where(['submission_user_criteria.submission_id' => $submission])
           ->group(['submission_user_criteria.submission_id', 'submission_user_criteria.user_id']);

        return $this->selectWith($select);
    }

    /**
     * @param int $submission
     *
     * @return bool
     */
    public function checkAllFinish($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('submission_id'))
            ->where(array('submission_user.end_date IS NULL'))
            ->where(array('submission_user.start_date IS NOT NULL'))
            ->where(array('submission_user.submission_id' => $submission_id));

        return ($this->selectWith($select)->count() === 0) ? true : false;
    }
    
    /**
     * @return \Application\Mapper\User
     */
    public function getMapperUser()
    {
        return $this->getServiceLocator()->get('app_mapper_user');
    }
}
