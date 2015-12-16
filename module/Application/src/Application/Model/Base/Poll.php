<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Poll extends AbstractModel
{
 	protected $id;
	protected $title;
	protected $expiration_date;

	protected $prefix = 'poll';

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

	public function getExpirationDate()
	{
		return $this->expiration_date;
	}

	public function setExpirationDate($expiration_date)
	{
		$this->expiration_date = $expiration_date;

		return $this;
	}

}