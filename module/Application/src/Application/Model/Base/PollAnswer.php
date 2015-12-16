<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PollAnswer extends AbstractModel
{
 	protected $id;
	protected $poll_id;
	protected $poll_question_id;
	protected $user_id;

	protected $prefix = 'poll_answer';

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

	public function getPollQuestionId()
	{
		return $this->poll_question_id;
	}

	public function setPollQuestionId($poll_question_id)
	{
		$this->poll_question_id = $poll_question_id;

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

}