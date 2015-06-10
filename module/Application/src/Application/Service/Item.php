<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Item extends AbstractService
{
	/**
	 * @invokable
	 * 
	 * @param integer $course
	 * @param integer $grading_policy
	 * @param string $title
	 * @param string $describe
	 * @param integer $duration
	 * @param string $type
	 * @param integer $weight
	 * @param integer $parent
	 * @param integer $module
	 * @throws \Exception
	 * @return integer
	 */
	public function add($course, $grading_policy, $title = null, $describe = null, $duration = null, $type = null, $weight = null, $parent = null, $module = null)
	{
		$m_item = $this->getModel()->setTitle($title)
		                 ->setDescribe($describe)
		                 ->setType($type)
		                 ->setParentId($this->getMapper()->selectLastParentId($course))
		                 ->setDuration($duration)
		                 ->setWeight($weight)
		                 ->setCourseId($course)
		                 ->setGradingPolicyId($grading_policy)
		                 ->setModuleId($module);
		
		if($this->getMapper()->insert($m_item) <= 0) {
			throw new \Exception('error insert item');
		}
		
		$item_id =  $this->getMapper()->getLastInsertValue();
		if ($parent !== null) {
			$this->updateParentId($item_id, $parent);
		}
		
		return $item_id;
	}
	
	public function updateParentId($item, $parent_id)
	{
		$res_item = $this->getMapper()->select($this->getModel()->setId($item));
		$course = $res_item->current()->getCourseId();
		$this->getMapper()->update($this->getModel()->setParentId($item), array('parent_id' => $parent_id, 'course_id' => $course));
		$this->getMapper()->update($this->getModel()->setId($item)->setParentId($parent_id));
	}
	
	public function update($id, $grading_policy = null, $title = null, $describe = null, $weight = null, $parent = null, $module = null)
	{
		$m_item = $this->getModel()
		               ->setId($id) 
		               ->setTitle($title)
		               ->setDescribe($describe)
					   ->setWeight($weight)
					   ->setGradingPolicy($grading_policy)
					   ->setModule($module);
	
		if($this->getMapper()->update($m_item) <= 0) {
			throw new \Exception('error insert item');
		}

		if ($parent !== null) {
			$this->updateParentId($item_id, $parent);
		}		
	
		return $item_id;
	}
	
	/**
	 * 
	 * Get Item by Type
	 * 
	 * @invokable
	 * 
	 * @param integer $course
	 * @param integer $type
	 * @return \Dal\Db\ResultSet\ResultSet
	 */
	public function getItemByType($course, $type)
	{
		$m_item = $this->getModel()->setType($type)->setCourse($course);
		
		return $this->getMapper()->select($m_item);
	}
}
