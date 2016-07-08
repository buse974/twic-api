<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Role as ModelRole;

class User extends AbstractMapper
{
    /**
     * @param int $item_id
     */
    public function getListUsersByItemOfCourseUser($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id',
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
        ])
        ->join('course_user_relation', 'course_user_relation.user_id=user.id', array())
        ->join('item', 'item.course_id=course_user_relation.course_id', array())
        ->join('user_role', 'user_role.user_id=user.id', [])
        ->where(array('item.id' => $item_id))
        ->where(array('user_role.role_id' => ModelRole::ROLE_STUDENT_ID));

        return $this->selectWith($select);
    }

    public function getListUsersGroupByItemAndUser($item_id, $user)
    {
        $sub = $this->tableGateway->getSql()->select();
        $sub->columns([])
            ->join('group_user', 'group_user.user_id=user.id', array('group_id'))
            ->join('set_group', 'set_group.group_id=group_user.group_id', array())
            ->join('item', 'item.set_id=set_group.set_id', array())
            ->where(array('group_user.user_id' => $user))
            ->where(array('item.id' => $item_id));

        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user$id' => new Expression('user.id'),
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
            'user$contact_state' => $this->getSelectContactState($user),
        ])
            ->join('group_user', 'group_user.user_id=user.id', array())
            ->where(array('group_user.group_id' => $sub));

        return $this->selectWith($select);
    }

    public function doBelongsByGroup($group_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
        ->join('group_user', 'group_user.user_id=user.id', [])
        ->where(array('group_user.group_id' => $group_id))
        ->where(array('group_user.user_id' => $user_id));

        return $this->selectWith($select)->count() > 0;
    }

    public function doBelongsByItemHaveSubmission($item_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
        ->join('submission_user', 'submission_user.user_id=user.id', [])
        ->join('submission', 'submission_user.submission_id=submission.id', [])
        ->where(['submission.item_id' => $item_id])
        ->where(['user.id' => $user_id]);

        return $this->selectWith($select)->count() > 0;
    }

    public function doBelongsBySet($set_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
            ->join('group_user', 'group_user.user_id=user.id', [])
            ->join('set_group', 'group_user.group_id=set_group.group_id', [])
            ->where(array('set_group.set_id' => $set_id))
            ->where(array('user.id' => $user_id));

        return $this->selectWith($select)->count() > 0;
    }

    public function doBelongsByItemOfCourseUser($item_id, $user_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id'])
            ->join('course_user_relation', 'course_user_relation.user_id=user.id', array())
            ->join('item', 'item.course_id=course_user_relation.course_id', array())
            ->join('user_role', 'user_role.user_id=user.id', [])
            ->where(array('item.id' => $item_id))
            ->where(array('user.id' => $user_id));

        return $this->selectWith($select)->count() > 0;
    }

    public function getListUsersByGroup($group_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id',
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
        ])
        ->join('group_user', 'group_user.user_id=user.id', array())
        ->where(array('group_user.group_id' => $group_id));

        return $this->selectWith($select);
    }

    public function getListUsersBySet($set_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id',
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
        ])
        ->join('group_user', 'group_user.user_id=user.id', array())
        ->join('set_group', 'group_user.group_id=set_group.group_id', array())
        ->where(array('set_group.set_id' => $set_id));

        return $this->selectWith($select);
    }

    public function getListUsersBySubmission($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user$id' => new Expression('user.id'),
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
        ])
        ->join('submission_user', 'submission_user.user_id=user.id', array())
        ->where(array('submission_user.submission_id' => $submission_id));

        return $this->selectWith($select);
    }

    public function get($user, $me)
    {
        $columns = array(
            'user$id' => new Expression('user.id'),
            'firstname',
            'gender',
            'lastname',
            'email',
            'background',
            'has_email_notifier',
            'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
            'position',
            'interest',
            'avatar',
            'school_id',
            'user$contacts_count' => $this->getSelectContactCount(),
            'user$contact_state' => $this->getSelectContactState($me), );

        $select = $this->tableGateway->getSql()->select();
        $select->columns($columns)
            ->join('school', 'school.id=user.school_id', array('id', 'name', 'short_name', 'logo', 'background'), $select::JOIN_LEFT)
            ->join(array('nationality' => 'country'), 'nationality.id=user.nationality', array('id', 'short_name'), $select::JOIN_LEFT)
            ->join(array('origin' => 'country'), 'origin.id=user.origin', array('id', 'short_name'), $select::JOIN_LEFT)
            ->where(['user.id' => $user])
            ->quantifier('DISTINCT');

        return $this->selectWith($select);
    }

    public function getListLite($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))->where(array('user.id' => $id));

        return $this->selectWith($select);
    }

    public function getList($filter = null, $event = null, $user_school, $type = null, $level = null, $course = null, $program = null, $search = null, $noprogram = null, $nocourse = null, $schools = null, $order = null, array $exclude = null, $message = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array(
            'user$id' => new Expression('user.id'),
            'firstname', 'lastname', 'email',
            'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
            'position', 'interest', 'avatar',
            'user$contact_state' => $this->getSelectContactState($user_school),
            'user$contacts_count' => $this->getSelectContactCount(), ))
            ->join('school', 'school.id=user.school_id', array('id', 'name', 'short_name', 'logo', 'background'), $select::JOIN_LEFT)
            ->group('user.id')
            ->quantifier('DISTINCT');

        switch ($order) {
            case 'firstname':
                $select->order('user.firstname ASC');
                break;
            case 'random':
                $select->order(new Expression('RAND()'));
                break;
        }
        if ($exclude) {
            $select->where->notIn('user.id', $exclude);
        }
        if (null !== $event) {
            $select->join('like', 'like.user_id=user.id', array())
                ->where(array('like.event_id' => $event))
                ->where(array('like.is_like IS TRUE'));
        }
        if (null !== $schools && $schools !== false) {
            $select->where(array('school.id' => $schools));
        } elseif ($schools !== false) {
            $sub_select = $this->tableGateway->getSql()->select();
            $sub_select->columns(array('school_id'))->where(array('user.id' => $user_school));
            $select->where(array('school.id' => $sub_select));
        }
        if (null !== $type) {
            $select->join('user_role', 'user_role.user_id=user.id', array())
                ->join('role', 'user_role.role_id=role.id', array())
                ->where(array('role.name' => $type));
        }
        if (!empty($program) || $level !== null || $course !== null || $search !== null) {
            $select->join('program_user_relation', 'program_user_relation.user_id=user.id', array(), $select::JOIN_LEFT);
            if ($level !== null || $course !== null || $search !== null) {
                $select->join('program', 'program_user_relation.program_id=program.id', array(), $select::JOIN_LEFT);
                if ($course !== null) {
                    $select->join('course_user_relation', 'course_user_relation.user_id=user.id', array(), $select::JOIN_LEFT);
                    $select->where(array('course_user_relation.course_id' => $course));
                }
                if (null !== $level) {
                    $select->where(array('program.level' => $level));
                }
                if (null !== $search) {
                    $select->where(array('(program.deleted_date IS NULL && program.name LIKE ? ' => ''.$search.'%'));
                    $select->where(array('CONCAT_WS(" ", user.firstname, user.lastname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR);
                    $select->where(array('CONCAT_WS(" ", user.lastname, user.firstname) LIKE ? )' => ''.$search.'%'), Predicate::OP_OR);
                }
            }
            if (!empty($program)) {
                $select->where(array('program_user_relation.program_id' => $program));
            }
        }

        if (null !== $noprogram) {
            $selectUser = $this->tableGateway->getSql()->select();

            $selectUser->columns(array('id'))
                ->join('program_user_relation', 'program_user_relation.user_id = user.id', array())
                ->where(array('program_user_relation.program_id' => $noprogram))
                ->where(array('user.deleted_date IS NULL'));
            $select->where(array('user.id NOT IN ? ' => $selectUser));
        }

        if (null !== $nocourse) {
            $selectUser = $this->tableGateway->getSql()->select();
            $selectUser->columns(array('id'))
                ->join('course_user_relation', 'course_user_relation.user_id = user.id', array())
                ->where(array('course_user_relation.course_id' => $nocourse))
                ->where(array('user.deleted_date IS NULL'));
            $select->where(array('user.id NOT IN ? ' => $selectUser));
        }

        if (null !== $message) {
            $select->join('message_user', 'message_user.user_id=user.id', array(), $select::JOIN_LEFT)
                ->join('message', 'message_user.message_id=message.id', array(), $select::JOIN_LEFT)
                ->where(array('message.id' => $message[1]))
                ->where(array(' ( message_user.type = ? ' => $message[0]))
                ->where(array(' message_user.type = ? ) ' => 'RS'), Predicate::OP_OR);
        }

        $select->where('user.deleted_date IS NULL')
            ->where('school.deleted_date IS NULL')
            ->order(array('user.id' => 'DESC'));

        return $this->selectWith($select);
    }

    public function getListContact($me, $type = null, $date = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 
            'firstname', 
            'lastname', 
            'school_id', 'email',
            'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'), 
            'position', 
            'interest', 
        'avatar'))
            ->join('contact', 'contact.contact_id=user.id', array('request_date', 'accepted_date', 'deleted_date', 'requested', 'accepted', 'deleted'))
            ->where('user.deleted_date IS NULL')
            ->where(array('contact.user_id' => $me))
            ->order(array('user.id' => 'DESC'))
            ->quantifier('DISTINCT');

        switch ($type) {
            case 1 : // on me demande en contact
                $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NULL AND contact.deleted_date IS NULL AND requested IS false AND accepted IS false AND deleted IS false'));
                break;
            case 2 : // j'ai demander en contact
                $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NULL AND contact.deleted_date IS NULL AND requested IS true AND accepted IS false AND deleted IS false'));
                break;
            case 3 : // on ma refuser en contact
                $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NULL AND contact.deleted_date IS NOT NULL AND requested IS true AND accepted IS false AND deleted IS false'));
                break;
            case 4 : // on ma suprimé alors que je suis en contact
                $select->where(array('contact.request_date IS NOT NULL AND contact.accepted_date IS NOT NULL AND contact.deleted_date IS NOT NULL AND deleted IS false'));
                break;
            case 5 : // contact ok
                $select->where(array('contact.accepted_date IS NOT NULL AND contact.deleted_date IS NULL'));
                break;
        }

        if ($date) {
            $select->where(array('( contact.request_date < ? ' => $date, ' contact.accepted_date < ? ' => $date, ' contact.deleted_date < ? ) ' => $date));
        }

        return $this->selectWith($select);
    }

    public function getListByItemAssignment($item_assignment)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('school', 'school.id=user.school_id', array('id', 'name', 'short_name', 'logo', 'background'), $select::JOIN_LEFT)
            ->join('submission_user', 'submission_user.user_id=user.id', array())
            ->join('item_assignment_relation', 'item_assignment_relation.submission_user_id=submission_user.id', array())
            ->where(array('item_assignment_relation.item_assignment_id' => $item_assignment));

        return $this->selectWith($select);
    }

    public function getListByItem($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['user$id' => new Expression('user.id'),
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
        ])
            ->join('submission_user', 'submission_user.user_id=user.id', [])
            ->join('submission', 'submission.id=submission_user.submission_id', [])
            ->where(['submission.item_id' => $item_id]);

        return $this->selectWith($select);
    }

    public function getListByItemProgWithInstrutor($submission)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('user_role', 'user_role.user_id=user.id', array('role$id' => 'role_id'))
            ->join('school', 'user.school_id=school.id', array())
            ->join('program', 'program.school_id=school.id', array())
            ->join('course', 'course.program_id=program.id', array())
            ->join('item', 'item.course_id=course.id', array())
            ->join('submission', 'submission.item_id=item.id', array())
            ->join('course_user_relation', 'course_user_relation.user_id=user.id AND course_user_relation.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join('submission_user', 'submission_user.user_id=user.id AND submission_user.submission_id = submission.id', array('started_date', 'finished_date'), $select::JOIN_LEFT)
            ->where(array('submission.id' => $submission))
            ->where(array(' (( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_INSTRUCTOR_ID))
            ->where(array('course_user_relation.user_id IS NOT NULL ) '))
            ->where(array(' ( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_STUDENT_ID), Predicate::OP_OR)
            ->where(array('submission_user.id IS NOT NULL ) )'));

        return $this->selectWith($select);
    }

    public function getInstructorByItem($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('user_role', 'user_role.user_id=user.id', array('role$id' => 'role_id'))
            ->join('course_user_relation', 'course_user_relation.user_id=user.id', array())
            ->join('item', 'item.course_id=course_user_relation.course_id', array())
            ->where(array('item.id' => $item_id))
            ->where(array('user_role.role_id' => \Application\Model\Role::ROLE_INSTRUCTOR_ID));
    
        return $this->selectWith($select);
    }
    
    public function getListBySubmissionWithInstrutorAndAcademic($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('user_role', 'user_role.user_id=user.id', [])
            ->join('school', 'user.school_id=school.id', [])
            ->join('course_user_relation', 'course_user_relation.user_id=user.id', [], $select::JOIN_LEFT)
            ->join('submission_user', 'submission_user.user_id=user.id', [], $select::JOIN_LEFT)
            ->join('submission', 'submission.id=submission_user.submission_id', [],  $select::JOIN_LEFT)
            ->join('item', 'submission.item_id=item.id', [], $select::JOIN_LEFT)
            ->join('course', 'item.course_id=course.id OR course_user_relation.course_id=course.id', [])
            ->join('program', 'course.program_id=program.id', [])
            ->where(array('submission.id' => $submission_id))
            ->where(array(' ( submission.id IS NULL AND user_role.role_id = 5 ) '), Predicate::OP_OR)
            ->quantifier('DISTINCT');

        return $this->selectWith($select);
    }

    public function getListByItemProgWithInstrutorAndAcademic($submission)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('user_role', 'user_role.user_id=user.id', array())
            ->join('school', 'user.school_id=school.id', array())
            ->join('program', 'program.school_id=school.id', array())
            ->join('course', 'course.program_id=program.id', array())
            ->join('item', 'item.course_id=course.id', array())
            ->join('submission', 'submission.item_id=item.id', array())
            ->join('course_user_relation', 'course_user_relation.user_id=user.id AND course_user_relation.course_id=course.id', array(), $select::JOIN_LEFT)
            ->join('submission_user', 'submission_user.user_id=user.id AND submission_user.submission_id = submission.id', array('started_date', 'finished_date'), $select::JOIN_LEFT)
            ->where(array('submission.id' => $submission))
            ->where(array(' (( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_INSTRUCTOR_ID))
            ->where(array('course_user_relation.user_id IS NOT NULL ) '))
            ->where(array(' ( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_STUDENT_ID), Predicate::OP_OR)
            ->where(array('submission_user.id IS NOT NULL ) )'));

        return $this->selectWith($select);
    }

    public function getListUserBycourseWithStudentAndInstructorAndAcademic($course)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('user_role', 'user_role.user_id=user.id', array())
            ->join('school', 'user.school_id=school.id', array())
            ->join('program', 'program.school_id=school.id', array())
            ->join('course', 'course.program_id=program.id', array())
            ->join('course_user_relation', 'course_user_relation.user_id=user.id AND course_user_relation.course_id=course.id', array(), $select::JOIN_LEFT)
            ->where(array('course.id' => $course))
            ->where(array(' ( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_ACADEMIC_ID))
            ->where(array('  course_user_relation.user_id IS NOT NULL ? ) '), Predicate::OP_OR);

        return $this->selectWith($select);
    }

    public function getListUserBycourseWithInstructorAndAcademic($course)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('user_role', 'user_role.user_id=user.id', array())
            ->join('school', 'user.school_id=school.id', array())
            ->join('program', 'program.school_id=school.id', array())
            ->join('course', 'course.program_id=program.id', array())
            ->join('course_user_relation', 'course_user_relation.user_id=user.id AND course_user_relation.course_id=course.id', array(), $select::JOIN_LEFT)
            ->where(array('course.id' => $course))
            ->where(array(' ( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_ACADEMIC_ID))
            ->where(array('  ( course_user_relation.user_id IS NOT NULL ?  '), Predicate::OP_OR)
            ->where(array('  user_role.role_id  = ? )) ' => \Application\Model\Role::ROLE_INSTRUCTOR_ID));

        return $this->selectWith($select);
    }

    public function getListBySchool($school)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->where(array('user.deleted_date IS NULL'))
            ->where(array('user.school_id' => $school));

        return $this->selectWith($select);
    }

    /**
     * Get all students for the instructor.
     *
     * @param int $instructor
     *
     * @return array
     */
    public function getStudentList($instructor)
    {
        $sub_select = new Select('course_user_relation');
        $sub_select->columns(array('course_id'))->where(array('course_user_relation.user_id' => $instructor));

        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('course_user_relation', 'user.id = course_user_relation.user_id', array())
            ->join('user_role', 'user.id = user_role.user_id', array())
            ->where(array('course_user_relation.course_id IN ?' => $sub_select))
            ->where(array('user_role.role_id' => \Application\Model\Role::ROLE_STUDENT_ID))
            ->quantifier('DISTINCT');

        return $this->selectWith($select);
    }

    /**
     * @param int $conversation
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('conversation_user', 'conversation_user.user_id = user.id', array())
            ->where(array('conversation_user.conversation_id' => $conversation));

        return $this->selectWith($select);
    }

    public function getEmailUnique($email, $user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('user$nb_user' => new Expression('COUNT(true)')))
            ->where(array('user.email' => $email))
            ->where(array('user.deleted_date IS NULL'));

        if (null !== $user) {
            $select->where(array('user.id <> ?' => $user));
        }

        return $this->selectWith($select);
    }

    public function nbrBySchool($school)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('school_id', 'user$nb_user' => new Expression('COUNT(true)')))
            ->join('user_role', 'user_role.user_id = user.id', array('user$role_id' => 'role_id'))
            ->where(array('user.school_id' => $school))
            ->group(array('user.school_id', 'user_role.role_id'));

        return $this->selectWith($select);
    }

    /**
     * @param int $user
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelectContactState($user)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('user$contact_state' => new Expression(
            'IF(contact.accepted_date IS NOT NULL AND contact.deleted_date IS NULL, 3,
	         IF(contact.request_date IS NOT  NULL AND contact.requested <> 1 AND contact.deleted_date IS NULL, 2,
		     IF(contact.request_date IS NOT  NULL AND contact.requested = 1 AND contact.deleted_date IS NULL, 1,0)))')))
                 ->join('contact', 'contact.contact_id = user.id', array())
                 ->where(array('user.id=`user$id`'))
                 ->where(['contact.user_id' => $user]);

        return $select;
    }
    
    /**
     * @return \Zend\Db\Sql\Select
     */
    public function getSelectContactCount()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('user$contacts_count' => new Expression('COUNT(1)')))
            ->join('contact', 'contact.contact_id = user.id', [])
            ->where(array('contact.user_id = `user$id` AND user.deleted_date IS NULL AND contact.accepted_date IS NOT NULL AND contact.deleted_date IS NULL'));

        return $select;
    }
    
    /**
     * @param int $submission_id
     */
    public function getListPairGraders($submission_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns([
            'id',
            'firstname',
            'lastname',
            'avatar',
        ])
        ->join('submission_pg', 'submission_pg.user_id=user.id', [])
        ->where(array('submission_pg.submission_id' => $submission_id));

        return $this->selectWith($select);
    }
}
