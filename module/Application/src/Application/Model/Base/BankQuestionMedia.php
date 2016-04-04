<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class BankQuestionMedia extends AbstractModel
{
 	protected $id;
	protected $bank_question_id;
	protected $token;
	protected $link;

	protected $prefix = 'bank_question_media';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

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

	public function getToken()
	{
		return $this->token;
	}

	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	public function getLink()
	{
		return $this->link;
	}

	public function setLink($link)
	{
		$this->link = $link;

		return $this;
	}

}