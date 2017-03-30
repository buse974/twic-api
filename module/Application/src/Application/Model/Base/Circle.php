<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Circle extends AbstractModel
{
 	protected $id;
	protected $name;

	protected $prefix = 'circle';

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

}