<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ComponentScale extends AbstractModel
{
 	protected $id;
	protected $component_id;
	protected $min;
	protected $max;
	protected $describe;

	protected $prefix = 'component_scale';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getComponentId()
	{
		return $this->component_id;
	}

	public function setComponentId($component_id)
	{
		$this->component_id = $component_id;

		return $this;
	}

	public function getMin()
	{
		return $this->min;
	}

	public function setMin($min)
	{
		$this->min = $min;

		return $this;
	}

	public function getMax()
	{
		return $this->max;
	}

	public function setMax($max)
	{
		$this->max = $max;

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