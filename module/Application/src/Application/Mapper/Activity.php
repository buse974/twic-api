<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Application\Model\Role as ModelRole;
use Zend\Db\Sql\Predicate\Predicate;

class Activity extends AbstractMapper
{
    public function aggregate($event, $user, $object_id = null, $object_name = null, $target_id = null, $target_name = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('event', 'activity$value_user' => new Expression('SUM( IF(activity.user_id='.$user.', object_value, 0))'), 'activity$value_total' => new Expression('SUM(object_value)')))->where(array('event' => $event));
        if (null !== $object_name && null !== $object_id) {
            $select->where(array('object_id' => $object_id, 'object_name' => $object_name));
        }
        if (null !== $target_name && null !== $target_id) {
            $select->where(array('target_id' => $target_id, 'target_name' => $target_name));
        }

        $select->join('user_role', 'user_role.user_id=activity.user_id')
            ->where(array('user_role.role_id='.ModelRole::ROLE_STUDENT_ID.''));

        return $this->selectWith($select);
    }
    
    
    public function getListWithFilters($event = null, $object_id = null, $object_name = null, $school_id = null, $program_id = null, $course_id = null, $item_id = null, $user_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'event', 'object_name', 'object_data', 'object_value', 'target_name', 'target_data', 'user_id', 'activity$date' => new Expression('DATE_FORMAT(activity.date, "%Y-%m-%dT%TZ")')])
            ->join('sub_conversation', 'sub_conversation.conversation_id = activity.object_id', array())
            ->join('submission', 'sub_conversation.submission_id = submission.id', array())
            ->join('item', 'submission.item_id = item.id', array('activity$item_id' => 'id'))
            ->join('course', 'item.course_id = course.id', array('activity$course_id' => 'id'))
            ->join('program', 'course.program_id = program.id', array('activity$program_id' => 'id','activity$school_id' => 'school_id'));
        if(null !== $event){
            $select->where(array('event' => $event));
        }
        if(null !== $object_id){
            $select->where(array('object_id' => $object_id));
        }
        if(null !== $object_name){
            $select->where(array('object_name' => $object_name));
        }
        if(null !== $school_id){
            $select->where(array('program.school_id' => $school_id));
        }
        if(null !== $program_id){
            $select->where(array('program.id' => $program_id));
        }
        if(null !== $course_id){
            $select->where(array('course.id' => $course_id));
        }
        if(null !== $item_id){
            $select->where(array('item.id' => $item_id));
        }
        if(null !== $user_id && !empty($user_id)){
            $select->where(array('activity.user_id' => $user_id));
        }
        
        return $this->selectWith($select);
    }

    public function getListWithUser($search)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'event', 'object_name', 'object_data', 'target_name', 'target_data', 'activity$date' => new Expression('DATE_FORMAT(activity.date, "%Y-%m-%dT%TZ")')])->join('user', 'user.id = activity.user_id', array('firstname', 'lastname', 'avatar'));

        if (null !== $search) {
            $select->where(array('CONCAT_WS(" ", user.firstname, user.lastname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)->where(array('CONCAT_WS(" ", user.lastname, user.firstname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR);
        }
        $select->order(['activity.id' => 'DESC']);

        return $this->selectWith($select);
    }
}
