<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Answer extends AbstractModel
{
 	protected $id;
	protected $questionnaire_user_id;
	protected $questionnaire_question_id;
	protected $question_id;
	protected $scale_id;
	protected $created_date;

	protected $prefix = 'answer';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getQuestionnaireUserId()
	{
		return $this->questionnaire_user_id;
	}

	public function setQuestionnaireUserId($questionnaire_user_id)
	{
		$this->questionnaire_user_id = $questionnaire_user_id;

		return $this;
	}

	public function getQuestionnaireQuestionId()
	{
		return $this->questionnaire_question_id;
	}

	public function setQuestionnaireQuestionId($questionnaire_question_id)
	{
		$this->questionnaire_question_id = $questionnaire_question_id;

		return $this;
	}

	public function getQuestionId()
	{
		return $this->question_id;
	}

	public function setQuestionId($question_id)
	{
		$this->question_id = $question_id;

		return $this;
	}

	public function getScaleId()
	{
		return $this->scale_id;
	}

	public function setScaleId($scale_id)
	{
		$this->scale_id = $scale_id;

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