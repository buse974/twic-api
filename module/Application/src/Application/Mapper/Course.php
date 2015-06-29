<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Course extends AbstractMapper
{
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture', 'objectives', 'teaching', 'attendance', 'duration', 'video_link', 'video_token', 'learning_outcomes', 'notes'))
        ->join('user', 'user.id=course.creator_id', array('id', 'firstname', 'lastname', 'email'))
        ->join('school', 'school.id=user.school_id', array('id', 'name', 'logo'), $select::JOIN_LEFT)
        ->where(array('course.id' => $id));

        return $this->selectWith($select);
    }

    public function getList($program = null, $search = null, $filter = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture', 'objectives', 'teaching', 'attendance', 'duration', 'video_link', 'video_token', 'learning_outcomes', 'notes'))
        ->where(array('course.deleted_date IS NULL'));

        if ($program) {
            $select->where(array('course.program_id' => $program));
        }

        if (null !== $filter && array_key_exists('user', $filter)) {
            $select->join('course_user_relation', 'course_user_relation.course_id=course.id', [])
                ->where(['course_user_relation.user_id' => $filter['user']]);
        }

        if (null == !$search) {
            $select->where(array('course.title LIKE ? ' => '%'.$search.'%'));
        }

        return $this->selectWith($select);
    }
    
    
   
}
