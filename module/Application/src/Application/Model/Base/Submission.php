<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Submission extends AbstractModel
{
 	protected $id;
	protected $item_id;
	protected $group_name;
	protected $submit_date;

	protected $prefix = 'submission';

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

	public function getGroupName()
	{
		return $this->group_name;
	}

	public function setGroupName($group_name)
	{
		$this->group_name = $group_name;

		return $this;
	}

	public function getSubmitDate()
	{
		return $this->submit_date;
	}

	public function setSubmitDate($submit_date)
	{
		$this->submit_date = $submit_date;

		return $this;
	}

}