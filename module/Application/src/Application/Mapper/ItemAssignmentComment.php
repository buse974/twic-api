<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ItemAssignmentComment extends AbstractMapper
{
	public function getList($item, $user)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('id', 'text', 'item_assignment_id', 'created_date', 'read_date'))
			   ->join('user', 'user.id=user_id', array('id', 'firstname', 'lastname', 'avatar'))
		       ->join('item_assignment', 'item_assignment.id=item_assignment_comment.item_assignment_id', array())
		       ->join('item_assignment_user', 'item_assignment_user.item_assignment_id=item_assignment_comment.item_assignment_id', array())
		       ->join('item_prog', 'item_assignment.item_prog_id=item_prog.id', array())
			   ->where(array('item_prog.item_id' => $item))
			   ->where(array('item_assignment_user.user_id' => $user));
		
		return $this->selectWith($select);
	}
}
