<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Course extends AbstractModel
{
 	protected $id;
	protected $title;
	protected $creator_id;
	protected $nb_module;
	protected $abstract;
	protected $description;
	protected $objectives;
	protected $teaching;
	protected $attendance;
	protected $duration;
	protected $notes;
	protected $learning_outcomes;
	protected $deleted_date;
	protected $updated_date;
	protected $created_date;
	protected $version;
	protected $video_link;
	protected $video_token;
	protected $program_id;
	protected $sis;

	protected $prefix = 'course';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getCreatorId()
	{
		return $this->creator_id;
	}

	public function setCreatorId($creator_id)
	{
		$this->creator_id = $creator_id;

		return $this;
	}

	public function getNbModule()
	{
		return $this->nb_module;
	}

	public function setNbModule($nb_module)
	{
		$this->nb_module = $nb_module;

		return $this;
	}

	public function getAbstract()
	{
		return $this->abstract;
	}

	public function setAbstract($abstract)
	{
		$this->abstract = $abstract;

		return $this;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	public function getObjectives()
	{
		return $this->objectives;
	}

	public function setObjectives($objectives)
	{
		$this->objectives = $objectives;

		return $this;
	}

	public function getTeaching()
	{
		return $this->teaching;
	}

	public function setTeaching($teaching)
	{
		$this->teaching = $teaching;

		return $this;
	}

	public function getAttendance()
	{
		return $this->attendance;
	}

	public function setAttendance($attendance)
	{
		$this->attendance = $attendance;

		return $this;
	}

	public function getDuration()
	{
		return $this->duration;
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;

		return $this;
	}

	public function getNotes()
	{
		return $this->notes;
	}

	public function setNotes($notes)
	{
		$this->notes = $notes;

		return $this;
	}

	public function getLearningOutcomes()
	{
		return $this->learning_outcomes;
	}

	public function setLearningOutcomes($learning_outcomes)
	{
		$this->learning_outcomes = $learning_outcomes;

		return $this;
	}

	public function getDeletedDate()
	{
		return $this->deleted_date;
	}

	public function setDeletedDate($deleted_date)
	{
		$this->deleted_date = $deleted_date;

		return $this;
	}

	public function getUpdatedDate()
	{
		return $this->updated_date;
	}

	public function setUpdatedDate($updated_date)
	{
		$this->updated_date = $updated_date;

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

	public function getVersion()
	{
		return $this->version;
	}

	public function setVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	public function getVideoLink()
	{
		return $this->video_link;
	}

	public function setVideoLink($video_link)
	{
		$this->video_link = $video_link;

		return $this;
	}

	public function getVideoToken()
	{
		return $this->video_token;
	}

	public function setVideoToken($video_token)
	{
		$this->video_token = $video_token;

		return $this;
	}

	public function getProgramId()
	{
		return $this->program_id;
	}

	public function setProgramId($program_id)
	{
		$this->program_id = $program_id;

		return $this;
	}

	public function getSis()
	{
		return $this->sis;
	}

	public function setSis($sis)
	{
		$this->sis = $sis;

		return $this;
	}

}