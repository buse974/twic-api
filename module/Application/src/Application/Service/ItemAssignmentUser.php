<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class ItemAssignmentUser extends AbstractService
{
	public function add($user, $item_assignment) 
	{
		if(!is_array($user)) {
			$user = array($user);
		}
		if(!is_array($item_assignment)) {
			$item_assignment = array($item_assignment);
		}
		foreach ($user as $u) {
			foreach ($item_assignment as $ia) {
				$this->getMapper()->insert($this->getModel()->setUserId($u)->setItemAssignmentId($ia));
			}
		}
	}
	
	public function deleteByItemAssignment($item_assignment)
	{
		return $this->getMapper()->delete($this->getModel()->setItemAssignmentId($item_assignment));
	}
	
	public function getByItemAssignment($item_assignment)
	{
		return $this->getMapper()->select($this->getModel()->setItemAssignmentId($item_assignment));
	}
}