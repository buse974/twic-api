<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Item extends AbstractModel
{
 	protected $id;
	protected $course_id;
	protected $grading_policy_id;
	protected $title;
	protected $describe;
	protected $duration;
	protected $type;
	protected $set_id;
	protected $parent_id;
	protected $order_id;
	protected $has_submission;
	protected $start;
	protected $end;
	protected $cut_off;
	protected $is_graded;
	protected $updated_date;
	protected $is_grouped;
	protected $has_all_student;
	protected $is_complete;
	protected $coefficient;

	protected $prefix = 'item';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getCourseId()
	{
		return $this->course_id;
	}

	public function setCourseId($course_id)
	{
		$this->course_id = $course_id;

		return $this;
	}

	public function getGradingPolicyId()
	{
		return $this->grading_policy_id;
	}

	public function setGradingPolicyId($grading_policy_id)
	{
		$this->grading_policy_id = $grading_policy_id;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getDescribe()
	{
		return $this->describe;
	}

	public function setDescribe($describe)
	{
		$this->describe = $describe;

		return $this;
	}

	public function getDuration()
	{
		return $this->duration;
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;

		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	public function getSetId()
	{
		return $this->set_id;
	}

	public function setSetId($set_id)
	{
		$this->set_id = $set_id;

		return $this;
	}

	public function getParentId()
	{
		return $this->parent_id;
	}

	public function setParentId($parent_id)
	{
		$this->parent_id = $parent_id;

		return $this;
	}

	public function getOrderId()
	{
		return $this->order_id;
	}

	public function setOrderId($order_id)
	{
		$this->order_id = $order_id;

		return $this;
	}

	public function getHasSubmission()
	{
		return $this->has_submission;
	}

	public function setHasSubmission($has_submission)
	{
		$this->has_submission = $has_submission;

		return $this;
	}

	public function getStart()
	{
		return $this->start;
	}

	public function setStart($start)
	{
		$this->start = $start;

		return $this;
	}

	public function getEnd()
	{
		return $this->end;
	}

	public function setEnd($end)
	{
		$this->end = $end;

		return $this;
	}

	public function getCutOff()
	{
		return $this->cut_off;
	}

	public function setCutOff($cut_off)
	{
		$this->cut_off = $cut_off;

		return $this;
	}

	public function getIsGraded()
	{
		return $this->is_graded;
	}

	public function setIsGraded($is_graded)
	{
		$this->is_graded = $is_graded;

		return $this;
	}

	public function getUpdatedDate()
	{
		return $this->updated_date;
	}

	public function setUpdatedDate($updated_date)
	{
		$this->updated_date = $updated_date;

		return $this;
	}

	public function getIsGrouped()
	{
		return $this->is_grouped;
	}

	public function setIsGrouped($is_grouped)
	{
		$this->is_grouped = $is_grouped;

		return $this;
	}

	public function getHasAllStudent()
	{
		return $this->has_all_student;
	}

	public function setHasAllStudent($has_all_student)
	{
		$this->has_all_student = $has_all_student;

		return $this;
	}

	public function getIsComplete()
	{
		return $this->is_complete;
	}

	public function setIsComplete($is_complete)
	{
		$this->is_complete = $is_complete;

		return $this;
	}

	public function getCoefficient()
	{
		return $this->coefficient;
	}

	public function setCoefficient($coefficient)
	{
		$this->coefficient = $coefficient;

		return $this;
	}

}