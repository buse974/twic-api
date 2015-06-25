<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicyGradeComment extends AbstractService
{
	/**
	 * 
	 * @invokable
	 * 
	 * @param integer $grading_policy
	 * @param integer $user
	 */
	public function getGetList($grading_policy, $user)
	{
		return $this->getMapper()->getGetList($grading_policy, $user);
	}
}
