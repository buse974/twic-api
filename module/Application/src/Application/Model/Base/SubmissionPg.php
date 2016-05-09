<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SubmissionPg extends AbstractModel
{
 	protected $user_id;
	protected $submission_id;
	protected $date;
	protected $is_graded;

	protected $prefix = 'submission_pg';

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getSubmissionId()
	{
		return $this->submission_id;
	}

	public function setSubmissionId($submission_id)
	{
		$this->submission_id = $submission_id;

		return $this;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setDate($date)
	{
		$this->date = $date;

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

}