<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ItemGrading extends AbstractMapper
{
	public function getList()
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('grade', 'created_date'))
			   ->join('grading_policy', 'item_grading.grading_policy_id=grading_policy.id');
			   
	}
}
