<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PollAnswerItems extends AbstractModel
{
 	protected $id;
	protected $poll_answer_id;
	protected $poll_question_item_id;
	protected $answer;
	protected $date;
	protected $time;

	protected $prefix = 'poll_answer_items';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getPollAnswerId()
	{
		return $this->poll_answer_id;
	}

	public function setPollAnswerId($poll_answer_id)
	{
		$this->poll_answer_id = $poll_answer_id;

		return $this;
	}

	public function getPollQuestionItemId()
	{
		return $this->poll_question_item_id;
	}

	public function setPollQuestionItemId($poll_question_item_id)
	{
		$this->poll_question_item_id = $poll_question_item_id;

		return $this;
	}

	public function getAnswer()
	{
		return $this->answer;
	}

	public function setAnswer($answer)
	{
		$this->answer = $answer;

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

	public function getTime()
	{
		return $this->time;
	}

	public function setTime($time)
	{
		$this->time = $time;

		return $this;
	}

}