<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Questionnaire extends AbstractModel
{
 	protected $id;
	protected $item_prog_id;
	protected $created_date;
	protected $max_duration;
	protected $max_time;

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

	public function getItemProgId()
	{
		return $this->item_prog_id;
	}

	public function setItemProgId($item_prog_id)
	{
		$this->item_prog_id = $item_prog_id;

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

}