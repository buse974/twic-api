<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;
use Zend\Json\Expr;

class Course extends AbstractMapper
{
    public function get($id)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture', 'objectives', 'teaching', 'attendance', 'duration', 'video_link', 'video_token', 'learning_outcomes', 'notes'))
        ->join(array('course_user' => 'user'), 'course_user.id=course.creator_id', array('id', 'firstname', 'lastname', 'email'))
        ->join(array('course_user_school' => 'school'), 'course_user_school.id=course_user.school_id', array('id', 'name', 'logo'), $select::JOIN_LEFT)
        ->where(array('course.id' => $id));

        return $this->selectWith($select);
    }

    public function getList($program = null, $search = null, $filter = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'title', 'abstract', 'description', 'picture', 'objectives', 'teaching', 'attendance', 'duration', 'video_link', 'video_token', 'learning_outcomes', 'notes', 'program_id',
            'course$start_date' => new Expression('DATE_FORMAT(MIN(start_date), "%Y-%m-%dT%TZ")'),
            'course$end_date' => new Expression('DATE_FORMAT(MAX(start_date), "%Y-%m-%dT%TZ")')))
            ->where(array('course.deleted_date IS NULL'))
            ->join('item', 'item.course_id=course.id', array())
            ->join('item_prog', 'item_prog.item_id=item.id', array(), $select::JOIN_LEFT)
            ->group('course.id');

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
    
    /**
     *
     * @param integer $user
     */
    public function getListRecord($user, $is_student = false)
    {
    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('id', 'title', 'abstract', 'description', 'picture'))
    	       ->join('program', 'course.program_id=program.id', array(), $select::JOIN_INNER)
    	       ->join(array('course_school' => 'school'), 'program.school_id=course_school.id', array('id', 'logo'), $select::JOIN_INNER)
    		   ->join('course_user_relation', 'course_user_relation.course_id=course.id', array(), $select::JOIN_INNER)
    		   ->join('item', 'item.course_id=course.id', array(), $select::JOIN_INNER)
    		   ->join('item_prog', 'item_prog.item_id=item.id', array(), $select::JOIN_INNER)
    		   ->join('videoconf', 'item_prog.id=videoconf.item_prog_id', array(), $select::JOIN_INNER)
    		   ->where(array('course_user_relation.user_id' => $user))
    		   ->where(array('videoconf.archive_link IS NOT NULL'))
    		   ->group('course.id');
    	
    	if($is_student!==false) {
    		$select->join('item_prog_user', 'item_prog.id=item_prog_user.item_prog_id', array(), $select::JOIN_INNER)
    			->where(array('item_prog_user.user_id' => $user));
    	}
    		   
    	return  $this->selectWith($select);
    }
}
