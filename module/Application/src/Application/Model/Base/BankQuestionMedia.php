<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class BankQuestionMedia extends AbstractModel
{
 	protected $id;
	protected $bank_question_id;
	protected $library_id;

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

	public function getLibraryId()
	{
		return $this->library_id;
	}

	public function setLibraryId($library_id)
	{
		$this->library_id = $library_id;

		return $this;
	}

}