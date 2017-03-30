<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class BankQuestionTag extends AbstractModel
{
 	protected $bank_question_id;
	protected $name;

	protected $prefix = 'bank_question_tag';

	public function getBankQuestionId()
	{
		return $this->bank_question_id;
	}

	public function setBankQuestionId($bank_question_id)
	{
		$this->bank_question_id = $bank_question_id;

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