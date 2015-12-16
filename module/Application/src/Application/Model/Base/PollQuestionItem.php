<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PollQuestionItem extends AbstractModel
{
 	protected $id;
	protected $libelle;
	protected $poll_question_id;
	protected $parent_id;

	protected $prefix = 'poll_question_item';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getLibelle()
	{
		return $this->libelle;
	}

	public function setLibelle($libelle)
	{
		$this->libelle = $libelle;

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