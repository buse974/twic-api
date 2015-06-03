<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Module extends AbstractService
{
	/**
	 * Add Module
	 * 
	 * @invokable
	 * 
	 * @param integer $course
	 * @throws \Exception
	 * @return integer
	 */
	public function add($course)
	{
		$res_course = $this->getMapper()->insert($this->getModel()->setCourseId($course));
		
		if($res_course <= 0) {
			throw new \Exception('error insert module');
		}
		
		return $this->getMapper()->getLastInsertValue();
	}
}
