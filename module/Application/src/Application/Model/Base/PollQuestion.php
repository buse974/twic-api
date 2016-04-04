<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PollQuestion extends AbstractModel
{
 	protected $id;
	protected $poll_id;
	protected $bank_question_id;
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

	public function getPollId()
	{
		return $this->poll_id;
	}

	public function setPollId($poll_id)
	{
		$this->poll_id = $poll_id;

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