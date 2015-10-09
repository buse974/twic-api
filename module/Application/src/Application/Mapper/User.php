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
        $columns = array('id','firstname','gender','lastname','email','has_email_notifier',
            'user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),
            'position','interest','avatar','school_id',
            'user$contact_state' => new Expression('(contact.accepted_date IS NOT NULL OR other_contact.request_date IS NOT NULL) << 1' . ' | (contact.accepted_date IS NOT NULL OR contact.request_date IS NOT NULL)'),
            'user$contacts_count' => new Expression('SUM(IF(connections.accepted_date IS NOT NULL, 1, 0))'));
        
        /*if($user===$me) {
            $columns[] = 'has_email_notifier';
        }*/
        
        $select = $this->tableGateway->getSql()->select();
        $select->columns($columns)
            ->join('school', 'school.id=user.school_id', array('id','name','short_name','logo'), $select::JOIN_LEFT)
            ->join(array('nationality' => 'country'), 'nationality.id=user.nationality', array('id','short_name'), $select::JOIN_LEFT)
            ->join(array('origin' => 'country'), 'origin.id=user.origin', array('id','short_name'), $select::JOIN_LEFT)
            ->join(array('uu' => 'user'), 'uu.id=uu.id', array(), $select::JOIN_CROSS)
            ->join('contact', 'contact.contact_id = user.id AND contact.user_id=uu.id', array(), $select::JOIN_LEFT)
            ->join(array('other_contact' => 'contact'), 'other_contact.user_id = user.id AND other_contact.contact_id=uu.id', array(), $select::JOIN_LEFT)
            ->join(array('connections' => 'contact'), 'connections.user_id = user.id')
            ->where(array('uu.id' => $me))
            ->where('connections.accepted_date IS NOT NULL')
            ->where(array('user.id' => $user));
        
        return $this->selectWith($select);
    }

    public function getList($filter = null, $school = null, $event = null, $user_school = null, $type = null, $level = null, $course = null, $program = null, $search = null, $noprogram = null, $nocourse = null, $schools = null, $order = null, array $exclude = null, $message = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','firstname','lastname','email','password','user$birth_date' => new Expression('DATE_FORMAT(user.birth_date, "%Y-%m-%dT%TZ")'),'position','interest','avatar','user$contact_state' => new Expression('(contact.accepted_date IS NOT NULL OR other_contact.request_date IS NOT NULL) << 1' . ' | (contact.accepted_date IS NOT NULL OR contact.request_date IS NOT NULL)'),'user$contacts_count' => new Expression('SUM(IF(connections.accepted_date IS NULL, 0, 1))')))
            ->join('school', 'school.id=user.school_id', array('id','name','short_name','logo'), $select::JOIN_LEFT)
            ->join(array('uu' => 'user'), 'uu.id=uu.id', array(), $select::JOIN_CROSS)
            ->join('contact', 'contact.contact_id = user.id AND contact.user_id=uu.id', array(), $select::JOIN_LEFT)
            ->join(array('other_contact' => 'contact'), 'other_contact.user_id = user.id AND other_contact.contact_id=uu.id', array(), $select::JOIN_LEFT)
            ->join(array('connections' => 'contact'), 'connections.user_id = user.id', array(), $select::JOIN_LEFT)
            ->group('user.id')
            ->quantifier('DISTINCT');
        
        switch ($order) {
            case 'firstname':
                $select->order('user.firstname ASC');
                break;
            case 'random':
                $select->order(new Expression('RAND()'));
                break;
            case 'contact_state':
                $select->order('user$contact_state DESC');
                break;
        }
        
        if ($exclude) {
            $select->where->notIn('user.id', $exclude);
        }
        
        if ($school !== null) {
            $select->where(array('school.id' => $school));
        }
        
        if ($user_school) {
            $select->where(array('uu.id' => $user_school));
        }
        
        if (null !== $event) {
            $select->join('like', 'like.user_id=user.id', array())
                ->where(array('like.event_id' => $event))
                ->where(array('like.is_like IS TRUE'));
        }
        
        if (null !== $schools) {
            $select->where(array('school.id' => $schools));   
        } elseif (null !== $user_school) {
            $sub_select = $this->tableGateway->getSql()->select();
            $sub_select->columns(array('school_id'))->where(array('user.id' => $user_school));
            $select->where(array('school.id' => $sub_select));
        }
        
        if ($type !== null) {
            if (! is_array($type)) {
                $type = array($type => true);
            }
            $ts = array();
            foreach ($type as $key => $t) {
                if ($t) {
                    $ts[] = $key;
                }
            }
            
            if (! empty($ts)) {
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
        
        if (null !== $message) {
            $select->join('message_user', 'message_user.user_id=user.id', array(), $select::JOIN_LEFT)
                ->join('message', 'message_user.message_id=message.id', array(), $select::JOIN_LEFT)
                ->where(array('message.id' => $message[1]))
                ->where(array(' ( message_user.type = ? ' => $message[0]))
                ->where(array(' message_user.type = ? ) ' => 'RS'), Predicate::OP_OR);
        }
        
        if (! empty($search)) {
            $select->where(array('(program.deleted_date IS NULL && program.name LIKE ? ' => '' . $search . '%'));
            $select->where(array('CONCAT_WS(" ", user.firstname, user.lastname) LIKE ? ' => '' . $search . '%'), Predicate::OP_OR);
            $select->where(array('CONCAT_WS(" ", user.lastname, user.firstname) LIKE ? )' => '' . $search . '%'), Predicate::OP_OR);
        }
        
        $select->where('user.deleted_date IS NULL')
            ->where('school.deleted_date IS NULL')
            ->order(array('user.id' => 'DESC'));
        
        return $this->selectWith($select);
    }

    public function getListByItemAssignment($item_assignment)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','firstname','lastname','avatar'))
            ->join('school', 'school.id=user.school_id', array('id','name','short_name','logo'), $select::JOIN_LEFT)
            ->join('item_prog_user', 'item_prog_user.user_id=user.id', array())
            ->join('item_assignment_relation', 'item_assignment_relation.item_prog_user_id=item_prog_user.id', array())
            ->where(array('item_assignment_relation.item_assignment_id' => $item_assignment));
        
        return $this->selectWith($select);
    }

    public function getListByItemProg($item_prog)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','firstname','lastname','avatar'))
            ->join('item_prog_user', 'item_prog_user.user_id=user.id', array('started_date','finished_date'))
            ->where(array('item_prog_user.item_prog_id' => $item_prog));
        
        return $this->selectWith($select);
    }

    public function getListByItemProgWithInstrutor($item_prog)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id'))
            ->join('user_role', 'user_role.user_id=user.id', array())
            ->join('course_user_relation', 'course_user_relation.user_id=user.id', array())
            ->join('item', 'item.course_id=course_user_relation.course_id', array())
            ->join('item_prog', 'item_prog.item_id=item.id', array())
            ->join('item_prog_user', 'item_prog_user.user_id=user.id AND item_prog_id = item_prog.id', array(), $select::JOIN_LEFT)
            ->where(array('item_prog.id' => $item_prog))
            ->where(array(' (( user_role.role_id  = ? ' => \Application\Model\Role::ROLE_STUDENT_ID))
            ->where(array('item_prog_user.id IS NOT NULL ) '))
            ->where(array('  user_role.role_id  = ? ) ' => \Application\Model\Role::ROLE_INSTRUCTOR_ID), Predicate::OP_OR)
            ->group(array('user.id'));
        
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
        $select->columns(array('id','firstname','lastname','avatar','user$available' => new Expression('MIN(IF(item_prog_user.item_prog_id = ' . $item_prog . ' OR item_prog_user.item_prog_id IS NULL, 1, 0))'),'user$selected' => new Expression('MAX(IF(item_prog_user.item_prog_id = ' . $item_prog . ', 1, 0))')))
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
        $select->columns(array('id','firstname','lastname','avatar'))
            ->join('course_user_relation', 'user.id = course_user_relation.user_id', array())
            ->join('user_role', 'user.id = user_role.user_id', array())
            ->where(array('course_user_relation.course_id IN ?' => $sub_select))
            ->where(array('user_role.role_id' => \Application\Model\Role::ROLE_STUDENT_ID))
            ->quantifier('DISTINCT');
        
        return $this->selectWith($select);
    }

    /**
     *
     * @param int $conversation            
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','firstname','lastname','avatar'))
            ->join('conversation_user', 'conversation_user.user_id = user.id', array())
            ->where(array('conversation_user.conversation_id' => $conversation));
        
        return $this->selectWith($select);
    }
}
