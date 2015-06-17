<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class ItemAssignmentUser extends AbstractModel
{
 	protected $item_assigment_id;
	protected $user_id;

	protected $prefix = 'item_assignment_user';

	public function getItemAssigmentId()
	{
		return $this->item_assigment_id;
	}

	public function setItemAssigmentId($item_assigment_id)
	{
		$this->item_assigment_id = $item_assigment_id;

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