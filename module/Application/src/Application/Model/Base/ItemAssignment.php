<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssignment extends AbstractModel
{
 	protected $id;
	protected $question;
	protected $item_prog_id;
	protected $submit_date;

	protected $prefix = 'item_assignment';

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

	public function getItemProgId()
	{
		return $this->item_prog_id;
	}

	public function setItemProgId($item_prog_id)
	{
		$this->item_prog_id = $item_prog_id;

		return $this;
	}

	public function getSubmitDate()
	{
		return $this->submit_date;
	}

	public function setSubmitDate($submit_date)
	{
		$this->submit_date = $submit_date;

		return $this;
	}

}