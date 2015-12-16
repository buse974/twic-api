<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PollQuestion extends AbstractModel
{
 	protected $id;
	protected $question;
	protected $poll_id;
	protected $poll_question_type_id;
	protected $is_mandatory;
	protected $parent_id;

	protected $prefix = 'poll_question';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getQuestion()
	{
		return $this->question;
	}

	public function setQuestion($question)
	{
		$this->question = $question;

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

	public function getPollQuestionTypeId()
	{
		return $this->poll_question_type_id;
	}

	public function setPollQuestionTypeId($poll_question_type_id)
	{
		$this->poll_question_type_id = $poll_question_type_id;

		return $this;
	}

	public function getIsMandatory()
	{
		return $this->is_mandatory;
	}

	public function setIsMandatory($is_mandatory)
	{
		$this->is_mandatory = $is_mandatory;

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

}