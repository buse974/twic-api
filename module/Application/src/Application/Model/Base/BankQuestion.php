<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class BankQuestion extends AbstractModel
{
 	protected $id;
	protected $name;
	protected $question;
	protected $bank_question_type_id;
	protected $course_id;
	protected $point;
	protected $older;
	protected $created_date;

	protected $prefix = 'bank_question';

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

	public function getQuestion()
	{
		return $this->question;
	}

	public function setQuestion($question)
	{
		$this->question = $question;

		return $this;
	}

	public function getBankQuestionTypeId()
	{
		return $this->bank_question_type_id;
	}

	public function setBankQuestionTypeId($bank_question_type_id)
	{
		$this->bank_question_type_id = $bank_question_type_id;

		return $this;
	}

	public function getCourseId()
	{
		return $this->course_id;
	}

	public function setCourseId($course_id)
	{
		$this->course_id = $course_id;

		return $this;
	}

	public function getPoint()
	{
		return $this->point;
	}

	public function setPoint($point)
	{
		$this->point = $point;

		return $this;
	}

	public function getOlder()
	{
		return $this->older;
	}

	public function setOlder($older)
	{
		$this->older = $older;

		return $this;
	}

	public function getCreatedDate()
	{
		return $this->created_date;
	}

	public function setCreatedDate($created_date)
	{
		$this->created_date = $created_date;

		return $this;
	}

}