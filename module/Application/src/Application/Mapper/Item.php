<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\NotIn;

class Item extends AbstractMapper
{
	
	public function getListGrade($programs, $courses, $individualWork, $groupWork, $notGraded, $newMessage, $filter)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('title'))
		       ->join('module', 'module.id=item.module_id', array('id', 'title'), $select::JOIN_LEFT)
		       ->join('course', 'course.id=item.course_id', array('id', 'title'))
		       ->join('program', 'program.id=course.program_id', array('id', 'name'))
		       ->join('item_prog', 'item_prog.item_id=item.id', array('start_date'));
		
		
	}
	
    /**
     * Get Last parent id.
     *
     * @param int $course
     *
     * @return int
     */
    public function selectLastParentId($course = null, $id = null)
    {
        if ($course === null && $id = null) {
            throw new \Exception('Course and id are null');
        }
        if ($course === null) {
            $course = $this->tableGateway->getSql()->select();
            $course->columns(array('course_id'))
                   ->where(array('id' => $id));
        }

        $select = $this->tableGateway->getSql()->select();
        $subselect = $this->tableGateway->getSql()->select();

        $subselect->columns(array('parent_id'))
                  ->where(array('parent_id IS NOT NULL'))
                  ->where(array('course_id' => $course));

        $select->columns(array('id'))
                ->where(array(new NotIn('id', $subselect)))
               ->where(array('course_id' => $course));

        $res = $this->selectWith($select);

        return (($res->count() > 0) ? $res->current()->getId() : null);
    }
}
