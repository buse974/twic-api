<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Questionnaire extends AbstractModel
{
 	protected $id;
	protected $item_id;
	protected $created_date;
	protected $max_duration;
	protected $max_time;
	protected $set_id;

	protected $prefix = 'questionnaire';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getItemId()
	{
		return $this->item_id;
	}

	public function setItemId($item_id)
	{
		$this->item_id = $item_id;

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

	public function getMaxDuration()
	{
		return $this->max_duration;
	}

	public function setMaxDuration($max_duration)
	{
		$this->max_duration = $max_duration;

		return $this;
	}

	public function getMaxTime()
	{
		return $this->max_time;
	}

	public function setMaxTime($max_time)
	{
		$this->max_time = $max_time;

		return $this;
	}

	public function getSetId()
	{
		return $this->set_id;
	}

	public function setSetId($set_id)
	{
		$this->set_id = $set_id;

		return $this;
	}

}