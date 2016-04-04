<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class BankAnswerItem extends AbstractModel
{
 	protected $bank_question_item_id;
	protected $percent;
	protected $answer;
	protected $date;
	protected $time;

	protected $prefix = 'bank_answer_item';

	public function getBankQuestionItemId()
	{
		return $this->bank_question_item_id;
	}

	public function setBankQuestionItemId($bank_question_item_id)
	{
		$this->bank_question_item_id = $bank_question_item_id;

		return $this;
	}

	public function getPercent()
	{
		return $this->percent;
	}

	public function setPercent($percent)
	{
		$this->percent = $percent;

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