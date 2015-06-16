<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssignmentComment extends AbstractModel
{
 	protected $id;
	protected $text;
	protected $user_id;
	protected $item_assignment_id;
	protected $created_date;
	protected $read_date;

	protected $prefix = 'item_assignment_comment';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getText()
	{
		return $this->text;
	}

	public function setText($text)
	{
		$this->text = $text;

		return $this;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getItemAssignmentId()
	{
		return $this->item_assignment_id;
	}

	public function setItemAssignmentId($item_assignment_id)
	{
		$this->item_assignment_id = $item_assignment_id;

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

	public function getReadDate()
	{
		return $this->read_date;
	}

	public function setReadDate($read_date)
	{
		$this->read_date = $read_date;

		return $this;
	}

}