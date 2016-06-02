<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;

class SubmissionUser extends AbstractMapper
{
    
    public function getListGrade($avg = array(), $filter = array(), $search = null, $user = null)
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

        $select->columns(array('submission_user$user' => 'user_id', 'submission_user$avg' => new Expression('CAST(SUM(grading_policy.grade * submission_user.grade) / SUM(grading_policy.grade) AS DECIMAL )'))) 
            ->join('submission', 'submission_user.submission_id=submission.id', [])
            ->join('item', 'submission.item_id=item.id', [])
            ->join('grading_policy', 'item.grading_policy_id=grading_policy.id', array('submission_user$course' => 'course_id'))
            ->join('course', 'course.id=grading_policy.course_id', array('submission_user$program' => 'program_id'))
            ->where('submission_user.grade IS NOT NULL')
            ->group(array('grading_policy.course_id', 'submission_user.user_id'));
        
        $sel = new Select(array('T' => $select));
        $sel->columns(array('submission_user$avg' => new Expression('AVG(T.submission_user$avg)')))
            ->join(array('datas' => $selectProgram), 'T.submission_user$user=datas.user$id AND T.submission_user$course=datas.course$id AND T.submission_user$program=datas.program$id', array('user$id' => 'user$id', 'user$lastname' => 'user$lastname', 'user$firstname' => 'user$firstname', 'user$avatar' => 'user$avatar', 'course$id' => 'course$id', 'course$title' => 'course$title', 'program$id' => 'program$id', 'program$name' => 'program$name'), $sel::JOIN_RIGHT);

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

    
    /**
     * @param integer $submission_id
     * @param integer $user_id
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
            'submission_user$start_date' => new Expression('DATE_FORMAT(submission_user.start_date, "%Y-%m-%dT%TZ")')        
        ])
            ->join('user', 'user.id=submission_user.user_id', ['user$id' => new Expression('user.id'),
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
                'user$contact_state' => $this->getSelectContactState($user_id)
            ])
            ->where(array('submission_user.submission_id' => $submission_id));
    
        return $this->selectWith($select);
    }
        
    /**
     * @param integer $user
     * @return \Zend\Db\Sql\Select
     */
    private function getSelectContactState($user)
    {
        $select = new Select('user');
        $select->columns(array('user$contact_state' =>  new Expression(
            'IF(contact.accepted_date IS NOT NULL, 3,
	         IF(contact.request_date IS NOT  NULL AND contact.requested <> 1, 2,
		     IF(contact.request_date IS NOT  NULL AND contact.requested = 1, 1,0)))')))
    		     ->join('contact', 'contact.contact_id = user.id', array())
    		     ->where(array('user.id=`user$id`'))
    		     ->where(['contact.user_id' => $user ]);
    
    	return $select;
    }
    
    /**
     * @param integer $submission
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getProcessedGrades($submission)
    {
        $select = new Select('submission_user_criteria');
        $select->columns([
            'submission_user$submission_id' => 'submission_id',
            'submission_user$user_id' => 'user_id',
            'submission_user$grade' => new Expression('IF(COUNT(DISTINCT criteria.id) = COUNT(DISTINCT submission_user_criteria.criteria_id), ROUND(SUM(submission_user_criteria.points) * 100 / SUM(criteria.points)), NULL)')])
           ->join('submission','submission_user_criteria.submission_id = submission.id',[])
           ->join('item', 'submission.item_id = item.id', [])
           ->join('grading_policy', 'item.grading_policy_id = grading_policy.id', [])
           ->join('criteria', 'criteria.grading_policy_id = grading_policy.id', [])
           ->where(['submission_user_criteria.submission_id' => $submission])
           ->group(['submission_user_criteria.submission_id', 'submission_user_criteria.user_id']);
        
        return $this->selectWith($select);
    }
    
    /**
     * @param integer $submission
     * @return boolean
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
}