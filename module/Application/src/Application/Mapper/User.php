<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

class User extends AbstractMapper
{
    public function get($user, $me)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'email', 'password', 'birth_date', 'position', 'interest', 'avatar', 'school_id',
            'user$contact_state' => new Expression('(contact.accepted_date IS NOT NULL OR other_contact.request_date IS NOT NULL) << 1'
                . ' | (contact.accepted_date IS NOT NULL OR contact.request_date IS NOT NULL)')
        ))
            ->join('school', 'school.id=user.school_id', array('id', 'name', 'short_name', 'logo'), $select::JOIN_LEFT)
            ->join(array('uu' => 'user'), 'uu.id=uu.id', array(), $select::JOIN_CROSS)
            ->join('contact', 'contact.contact_id = user.id AND contact.user_id=uu.id', array(), $select::JOIN_LEFT)
            ->join(array('other_contact' => 'contact'), 'other_contact.user_id = user.id AND other_contact.contact_id=uu.id', array(), $select::JOIN_LEFT)
            ->where(array('uu.id' => $me))
            ->where(array('user.id' => $user));


        return $this->selectWith($select);
    }

    public function getList($filter = null, $school = null, $user_school = null, $type = null, $level = null, $course = null, $program = null, $search = null, $noprogram = null, $nocourse = null, $schools = true, $order = null, array $exclude = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'email', 'password', 'birth_date', 'position', 'interest', 'avatar',
            'user$contact_state' => new Expression('(contact.accepted_date IS NOT NULL OR other_contact.request_date IS NOT NULL) << 1'
        . ' | (contact.accepted_date IS NOT NULL OR contact.request_date IS NOT NULL)')
        ))
            ->join('school', 'school.id=user.school_id', array('id', 'name', 'short_name', 'logo'), $select::JOIN_LEFT)
            ->join(array('uu' => 'user'), 'uu.id=uu.id', array(), $select::JOIN_CROSS)
            ->join('contact', 'contact.contact_id = user.id AND contact.user_id=uu.id', array(), $select::JOIN_LEFT)
            ->join(array('other_contact' => 'contact'), 'other_contact.user_id = user.id AND other_contact.contact_id=uu.id', array(), $select::JOIN_LEFT)
            ->quantifier('DISTINCT');

        switch ($order) {
            case 'firstname':
                $select->order(['user.firstname' => 'ASC']);
                break;
            case 'random':
                $select->order(new Expression('RAND()'));
                break;
        }

        if ($exclude) {
            $select->where->notIn('user.id',$exclude);
        }

        if ($school !== null) {
            $select->where(array('school.id' => $school));
        }
        
        if ($user_school) {
            $select->where(array('uu.id' => $user_school));
        }

        
        
        if ($user_school && $schools===true) {
            $sub_select = $this->tableGateway->getSql()->select();
            $sub_select->columns(array('school_id'))->where(array('user.id' => $user_school));
            $select->where(array('school.id' => $sub_select));
        }

        if (is_array($schools)) {
            $select->where->in('school.id', $schools);
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
                    ->where(array('role.name' => $ts));
            }
        }

        if ($program !== null || $level !== null || $course !== null || $search !== null) {
            $select->join('program_user_relation', 'program_user_relation.user_id=user.id', array(), $select::JOIN_LEFT);
        }

        if ($level !== null || $course !== null || $search !== null) {
            $select->join('program', 'program_user_relation.program_id=program.id', array(), $select::JOIN_LEFT);
        }

        if ($course !== null) {
            $select->join('course_user_relation', 'course_user_relation.user_id=user.id', array(), $select::JOIN_LEFT);
            $select->where(array('course_user_relation.course_id' => $course));
        }

        if ($program) {
            $select->where(array('program_user_relation.program_id' => $program));
        }

        if ($noprogram !== null) {
            $selectUser = $this->tableGateway->getSql()->select();

            $selectUser->columns(array('id'))
                ->join('program_user_relation', 'program_user_relation.user_id = user.id', array())
                ->where(array('program_user_relation.program_id' => $noprogram))
                ->where(array('user.deleted_date IS NULL'));
            $select->where(array('user.id NOT IN ? ' => $selectUser));
        }

        if ($nocourse !== null) {
            $selectUser = $this->tableGateway->getSql()->select();

            $selectUser->columns(array('id'))
                ->join('course_user_relation', 'course_user_relation.user_id = user.id', array())
                ->where(array('course_user_relation.course_id' => $nocourse))
                ->where(array('user.deleted_date IS NULL'));
            $select->where(array('user.id NOT IN ? ' => $selectUser));
        }

        if ($level) {
            $select->where(array('program.level' => $level));
        }

        if (!empty($search)) {
            $select->where(array('(program.deleted_date IS NULL && program.name LIKE ? ' => ''.$search.'%'));
            $select->where(array('user.firstname LIKE ? ' => ''.$search.'%'), Predicate::OP_OR);
            $select->where(array('user.lastname LIKE ? )' => ''.$search.'%'), Predicate::OP_OR);
        }

        $select->where('user.deleted_date IS NULL')->order(array('user.id' => 'DESC'));

        return $this->selectWith($select);
    }

    public function getListByItemAssignment($item_assignment)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('item_prog_user', 'item_prog_user.user_id=user.id', array())
            ->join('item_assignment_relation', 'item_assignment_relation.item_prog_user_id=item_prog_user.id', array())
            ->where(array('item_assignment_relation.item_assignment_id' => $item_assignment));

        return $this->selectWith($select);
    }

    /**
     * Get user list from item prog.
     *
     * @invokable
     *
     * @param int $item_prog
     *
     * @return array
     */
    public function getListByItemProg($item_prog)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar'))
            ->join('item_prog_user', 'item_prog_user.user_id=user.id', array())
            ->where(array('item_prog_user.item_prog_id' => $item_prog));

        return $this->selectWith($select);
    }

    /**
     * Get user list for item_prog and those available.
     *
     * @param int $item_prog
     * @param int $item
     * @param int $course
     *
     * @return array
     */
    public function getListForItemProg($item_prog, $item, $course)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'avatar', 'user$available' => new Expression('MIN(IF(item_prog_user.item_prog_id = '.$item_prog.' OR item_prog_user.item_prog_id IS NULL, 1, 0))'), 'user$selected' => new Expression('MAX(IF(item_prog_user.item_prog_id = '.$item_prog.', 1, 0))')))
            ->join('course_user_relation', 'user.id = course_user_relation.user_id', array())
            ->join('course', 'course.id = course_user_relation.course_id', array())
            ->join('item', 'course.id = item.course_id', array())
            ->join('item_prog', 'item.id = item_prog.item_id', array(), $select::JOIN_LEFT)
            ->join('item_prog_user', 'item_prog.id = item_prog_user.item_prog_id AND user.id = item_prog_user.user_id', array(), $select::JOIN_LEFT)
            ->join('user_role', 'user.id = user_role.user_id', array())
            ->where(array('item.id ' => $item))
            ->where(array('course.id' => $course))
            ->where(array('user_role.role_id' => \Application\Model\Role::ROLE_STUDENT_ID))
            ->group(array('user.id'));

        return $this->selectWith($select);
    }

    /**
     * Get all students for the instructor.
     *
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
        $select->columns(array('id','firstname', 'lastname', 'avatar'))
               ->join('conversation_user', 'conversation_user.user_id = user.id', array())
               ->where(array('conversation_user.conversation_id' => $conversation));
        return $this->selectWith($select);
    }
        
}
