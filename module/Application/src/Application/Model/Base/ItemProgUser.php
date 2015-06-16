<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemProgUser extends AbstractModel
{
 	protected $id;
	protected $user_id;
	protected $item_prog_id;

	protected $prefix = 'item_prog_user';

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

	public function getItemProgId()
	{
		return $this->item_prog_id;
	}

	public function setItemProgId($item_prog_id)
	{
		$this->item_prog_id = $item_prog_id;

		return $this;
	}

}