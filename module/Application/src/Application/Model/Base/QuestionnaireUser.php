<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class QuestionnaireUser extends AbstractModel
{
 	protected $id;
	protected $user_id;
	protected $questionnaire_id;
	protected $submission_id;
	protected $state;
	protected $created_date;
	protected $end_date;

	protected $prefix = 'questionnaire_user';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getQuestionnaireId()
	{
		return $this->questionnaire_id;
	}

	public function setQuestionnaireId($questionnaire_id)
	{
		$this->questionnaire_id = $questionnaire_id;

		return $this;
	}

	public function getSubmissionId()
	{
		return $this->submission_id;
	}

	public function setSubmissionId($submission_id)
	{
		$this->submission_id = $submission_id;

		return $this;
	}

	public function getState()
	{
		return $this->state;
	}

	public function setState($state)
	{
		$this->state = $state;

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

	public function getEndDate()
	{
		return $this->end_date;
	}

	public function setEndDate($end_date)
	{
		$this->end_date = $end_date;

		return $this;
	}

}