<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GradingPolicyGrade extends AbstractService
{
	/**
	 * @invokable
	 * 
	 * @param array $avg
	 * @param array $filter
	 */
	public function getList($avg = array(), $filter = array())
	{
		return $this->getMapper()->getList($avg, $filter);
	}
}