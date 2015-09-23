<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Activity extends AbstractModel
{
 	protected $id;
	protected $source_id;
	protected $source_name;
	protected $source_data;
	protected $event;
	protected $object_id;
	protected $object_name;
	protected $object_data;
	protected $target_id;
	protected $target_name;
	protected $target_data;
	protected $date;

	protected $prefix = 'activity';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getSourceId()
	{
		return $this->source_id;
	}

	public function setSourceId($source_id)
	{
		$this->source_id = $source_id;

		return $this;
	}

	public function getSourceName()
	{
		return $this->source_name;
	}

	public function setSourceName($source_name)
	{
		$this->source_name = $source_name;

		return $this;
	}

	public function getSourceData()
	{
		return $this->source_data;
	}

	public function setSourceData($source_data)
	{
		$this->source_data = $source_data;

		return $this;
	}

	public function getEvent()
	{
		return $this->event;
	}

	public function setEvent($event)
	{
		$this->event = $event;

		return $this;
	}

	public function getObjectId()
	{
		return $this->object_id;
	}

	public function setObjectId($object_id)
	{
		$this->object_id = $object_id;

		return $this;
	}

	public function getObjectName()
	{
		return $this->object_name;
	}

	public function setObjectName($object_name)
	{
		$this->object_name = $object_name;

		return $this;
	}

	public function getObjectData()
	{
		return $this->object_data;
	}

	public function setObjectData($object_data)
	{
		$this->object_data = $object_data;

		return $this;
	}

	public function getTargetId()
	{
		return $this->target_id;
	}

	public function setTargetId($target_id)
	{
		$this->target_id = $target_id;

		return $this;
	}

	public function getTargetName()
	{
		return $this->target_name;
	}

	public function setTargetName($target_name)
	{
		$this->target_name = $target_name;

		return $this;
	}

	public function getTargetData()
	{
		return $this->target_data;
	}

	public function setTargetData($target_data)
	{
		$this->target_data = $target_data;

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

}