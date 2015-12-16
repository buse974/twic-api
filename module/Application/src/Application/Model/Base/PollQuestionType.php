<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class PollQuestionType extends AbstractModel
{
 	protected $id;
	protected $name;

	protected $prefix = 'poll_question_type';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

}