<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class GradingPolicyGradeComment extends AbstractMapper
{
	public function getGetList($grading_policy, $user)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('id', 'text', 'user_id','created_date'))
			   ->join('grading_policy_grade', 'grading_policy_grade.id=grading_policy_grade_comment.grading_policy_grade_id')
			   ->where(array('grading_policy_grade.grading_policy_id' => $grading_policy))
			   ->where(array('grading_policy_grade.user_id' => $user));

		return $this->getMapper()->getGetList($grading_policy, $user);
	}
	
}
