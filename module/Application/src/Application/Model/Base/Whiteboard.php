<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Whiteboard extends AbstractModel
{
 	protected $id;
	protected $name;
	protected $owner_id;

	protected $prefix = 'whiteboard';

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

	public function getOwnerId()
	{
		return $this->owner_id;
	}

	public function setOwnerId($owner_id)
	{
		$this->owner_id = $owner_id;

		return $this;
	}

}