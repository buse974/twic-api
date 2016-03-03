<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Submission extends AbstractModel
{
 	protected $id;
	protected $item_id;
	protected $published_date;

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

	public function getPublishedDate()
	{
		return $this->published_date;
	}

	public function setPublishedDate($published_date)
	{
		$this->published_date = $published_date;

		return $this;
	}

}