<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Item extends AbstractMapper
{
	/**
	 * Get Last parent id.
	 *
	 * @param integer $course
	 *
	 * @return integer
	 */
	public function selectLastParentId($course = null,$id = null)
	{
		if($course ===null && $id = null) {
			throw new \Exception('Course and id are null');
		}
		if($course === null) {
			$course = $this->tableGateway->getSql()->select();
			$course->columns(array('course_id'))
			       ->where(array('id' => $id));
		}
		
		$select = $this->tableGateway->getSql()->select();
		$subselect = $this->tableGateway->getSql()->select();
		
		$subselect->columns(array('parent_id'))
		          ->where(array('parent_id IS NOT NULL'))
		          ->where(array('course_id' => $course));
		
		$select->columns(array('id' => $subselect))
		       ->where(array('course_id' => $course));
		
		$res = $this->selectWith($select);
	
		return (($res->count() > 0) ? $res->current()->getId() : null);
	}
}
