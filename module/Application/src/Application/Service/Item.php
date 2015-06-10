<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Item extends AbstractService
{
    /**
     * @invokable
     *
     * @param int    $course
     * @param int    $grading_policy
     * @param string $title
     * @param string $describe
     * @param int    $duration
     * @param string $type
     * @param int    $weight
     * @param int    $parent
     * @param int    $module
     *
     * @throws \Exception
     *
     * @return int
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

        if ($this->getMapper()->insert($m_item) <= 0) {
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
        $me_item = $res_item->current();

        // JE SORT
        $this->getMapper()->update($this->getModel()->setParentId($me_item->getParentId() === null ? new IsNull() : $me_item->getParentId()), array('parent_id' => $item, 'course_id' => $me_item->getCourseId()));
        // JE RENTRE
        $this->getMapper()->update($this->getModel()->setParentId($item), array('parent_id' => $parent_id, 'course_id' => $me_item->getCourseId()));
        $this->getMapper()->update($this->getModel()->setId($item)->setParentId($parent_id));
    }

    /**
     * @invokable
     *
     * @param int    $id
     * @param int    $grading_policy
     * @param int    $duration
     * @param string $title
     * @param string $describe
     * @param int    $weight
     * @param string $parent
     * @param int    $module
     *
     * @return int
     */
    public function update($id, $grading_policy = null, $duration = null, $title = null, $describe = null, $weight = null, $parent = null, $module = null)
    {
        $m_item = $this->getModel()
                       ->setId($id)
                       ->setDuration($duration)
                       ->setTitle($title)
                       ->setDescribe($describe)
                       ->setWeight($weight)
                       ->setGradingPolicyId($grading_policy)
                       ->setModuleId($module);

        if ($parent !== null) {
            $this->updateParentId($id, $parent);
        }

        return $this->getMapper()->update($m_item);
    }

    /**
     * @invokable
     * 
     * @param integer $course
     * @return array
     */
    public function getList($course)
    {
    	$m_item = $this->getModel()->setCourseId($course);
		
    	$res_item = $this->getMapper()->select($m_item);
    	
    	return $res_item->toArrayParent();
    }
    
    /**
     * Get Item by Type.
     *
     * @invokable
     *
     * @param int $course
     * @param int $type
     *
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getItemByType($course, $type)
    {
        $m_item = $this->getModel()->setType($type)->setCourse($course);

        return $this->getMapper()->select($m_item);
    }
}
