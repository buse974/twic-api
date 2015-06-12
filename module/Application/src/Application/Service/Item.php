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
     * @param array  $materials
     *
     * @throws \Exception
     *
     * @return int
     */
    public function add($course, $grading_policy, $title = null, $describe = null, $duration = null, $type = null, $weight = null, $parent = null, $module = null, $materials = null)
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
        if($materials !== null) {
        	$this->getServiceItemMaterialDocumentRelation()->addByItem($item_id, $materials);
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
     * @param array  $materials
     *
     * @return int
     */
    public function update($id, $grading_policy = null, $duration = null, $title = null, $describe = null, $weight = null, $parent = null, $module = null, $materials = null)
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
        if($materials !== null) {
        	$this->getServiceItemMaterialDocumentRelation()->replaceByItem($id, $materials);
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
    	$res_item = $this->getMapper()->select($this->getModel()->setCourseId($course));
    	foreach ($res_item as $m_item) {
    		$res_imdr = $this->getServiceItemMaterialDocumentRelation()->getListByItemId($m_item->getId());
    		$ar_imdr = array();
    		foreach ($res_imdr as $m_imdr) {
    			$ar_imdr[] = $m_imdr->getMaterialDocumentId();
    		}
    		$m_item->setMaterials($ar_imdr);
    	}
    	
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
    
    public function deleteByModuleId($module)
    {
    	$nbr = 0;
    	$res_item = $this->getMapper()->select($this->getModel()->setModuleId($module));
    	
    	if($res_item->count() > 0) {
	    	foreach ($res_item as $m_item) {
	    		$nbr += $this->delete($m_item->getId());
	    	}
    	}
    	
     	return $nbr;
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * @return integer
     */
    public function delete($id)
    {
    	$this->getServiceItemMaterialDocumentRelation()->deleteByItem($id);
    	
    	return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    /**
     * @invokable
     * 
     * @param array $programs
     * @param array $courses
     * @param boolean $individualWork
     * @param boolean $groupWork
     * @param boolean $notGraded
     * @param boolean $newMessage
     * @param array $filter
     */
    public function getListGrade($programs, $courses = null, $individualWork = null, $groupWork = null, $notGraded = null, $newMessage = null, $filter = null)
    {
    	$mapper = $this->getMapper();
    	
    	$res_item = $mapper->usePaginator($filter)->getListGrade($programs, $courses, $individualWork, $groupWork, $notGraded, $newMessage, $filter);
    	
    	return array('count' => $mapper->count(), 'list' => $res_item);
    }
    
    /**
     * @return \Application\Service\ItemMaterialDocumentRelation
     */
    public function getServiceItemMaterialDocumentRelation()
    {
    	return $this->getServiceLocator()->get('app_service_item_material_document_relation');
    }
}
