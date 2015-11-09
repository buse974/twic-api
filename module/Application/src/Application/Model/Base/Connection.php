<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class Connection extends AbstractModel
{
 	protected $id;
	protected $user_id;
	protected $token;
	protected $diff;
	protected $start;
	protected $total;
	protected $parent_id;

	protected $prefix = 'connection';

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;

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

	public function getToken()
	{
		return $this->token;
	}

	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	public function getDiff()
	{
		return $this->diff;
	}

	public function setDiff($diff)
	{
		$this->diff = $diff;

		return $this;
	}

	public function getStart()
	{
		return $this->start;
	}

	public function setStart($start)
	{
		$this->start = $start;

		return $this;
	}

	public function getTotal()
	{
		return $this->total;
	}

	public function setTotal($total)
	{
		$this->total = $total;

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

}