<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Whiteboard extends AbstractModel
{
 	protected $id;

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

}