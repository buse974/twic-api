<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SubmissionUser extends AbstractModel
{
 	protected $submission_id;
	protected $user_id;
	protected $group_id;
	protected $grade;
	protected $started_date;
	protected $finished_date;
	protected $submit_date;

	protected $prefix = 'submission_user';

	public function getSubmissionId()
	{
		return $this->submission_id;
	}

	public function setSubmissionId($submission_id)
	{
		$this->submission_id = $submission_id;

		return $this;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getGroupId()
	{
		return $this->group_id;
	}

	public function setGroupId($group_id)
	{
		$this->group_id = $group_id;

		return $this;
	}

	public function getGrade()
	{
		return $this->grade;
	}

	public function setGrade($grade)
	{
		$this->grade = $grade;

		return $this;
	}

	public function getStartedDate()
	{
		return $this->started_date;
	}

	public function setStartedDate($started_date)
	{
		$this->started_date = $started_date;

		return $this;
	}

	public function getFinishedDate()
	{
		return $this->finished_date;
	}

	public function setFinishedDate($finished_date)
	{
		$this->finished_date = $finished_date;

		return $this;
	}

	public function getSubmitDate()
	{
		return $this->submit_date;
	}

	public function setSubmitDate($submit_date)
	{
		$this->submit_date = $submit_date;

		return $this;
	}

}