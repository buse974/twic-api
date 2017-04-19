<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Item extends AbstractModel
{
 	protected $id;
	protected $title;
	protected $description;
	protected $type;
	protected $is_available;
	protected $is_published;
	protected $order;
	protected $start_date;
	protected $end_date;
	protected $updated_date;
	protected $created_date;
	protected $parent_id;
	protected $page_id;
	protected $user_id;

	protected $prefix = 'item';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	public function getIsAvailable()
	{
		return $this->is_available;
	}

	public function setIsAvailable($is_available)
	{
		$this->is_available = $is_available;

		return $this;
	}

	public function getIsPublished()
	{
		return $this->is_published;
	}

	public function setIsPublished($is_published)
	{
		$this->is_published = $is_published;

		return $this;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function setOrder($order)
	{
		$this->order = $order;

		return $this;
	}

	public function getStartDate()
	{
		return $this->start_date;
	}

	public function setStartDate($start_date)
	{
		$this->start_date = $start_date;

		return $this;
	}

	public function getEndDate()
	{
		return $this->end_date;
	}

	public function setEndDate($end_date)
	{
		$this->end_date = $end_date;

		return $this;
	}

	public function getUpdatedDate()
	{
		return $this->updated_date;
	}

	public function setUpdatedDate($updated_date)
	{
		$this->updated_date = $updated_date;

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

	public function getParentId()
	{
		return $this->parent_id;
	}

	public function setParentId($parent_id)
	{
		$this->parent_id = $parent_id;

		return $this;
	}

	public function getPageId()
	{
		return $this->page_id;
	}

	public function setPageId($page_id)
	{
		$this->page_id = $page_id;

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

}