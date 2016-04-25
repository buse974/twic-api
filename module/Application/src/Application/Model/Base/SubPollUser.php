<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class SubPollUser extends AbstractModel
{
 	protected $id;
	protected $poll_id;
	protected $poll_item_id;
	protected $bank_question_id;
	protected $group_question_id;
	protected $user_id;
	protected $submission_id;
	protected $point;
	protected $attempt;

	protected $prefix = 'sub_poll_user';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getPollId()
	{
		return $this->poll_id;
	}

	public function setPollId($poll_id)
	{
		$this->poll_id = $poll_id;

		return $this;
	}

	public function getPollItemId()
	{
		return $this->poll_item_id;
	}

	public function setPollItemId($poll_item_id)
	{
		$this->poll_item_id = $poll_item_id;

		return $this;
	}

	public function getBankQuestionId()
	{
		return $this->bank_question_id;
	}

	public function setBankQuestionId($bank_question_id)
	{
		$this->bank_question_id = $bank_question_id;

		return $this;
	}

	public function getGroupQuestionId()
	{
		return $this->group_question_id;
	}

	public function setGroupQuestionId($group_question_id)
	{
		$this->group_question_id = $group_question_id;

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

	public function getSubmissionId()
	{
		return $this->submission_id;
	}

	public function setSubmissionId($submission_id)
	{
		$this->submission_id = $submission_id;

		return $this;
	}

	public function getPoint()
	{
		return $this->point;
	}

	public function setPoint($point)
	{
		$this->point = $point;

		return $this;
	}

	public function getAttempt()
	{
		return $this->attempt;
	}

	public function setAttempt($attempt)
	{
		$this->attempt = $attempt;

		return $this;
	}

}