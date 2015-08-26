<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Dimension extends AbstractModel
{
 	protected $id;
	protected $name;
	protected $describe;

	protected $prefix = 'dimension';

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

	public function getDescribe()
	{
		return $this->describe;
	}

	public function setDescribe($describe)
	{
		$this->describe = $describe;

		return $this;
	}

}