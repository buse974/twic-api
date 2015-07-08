<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;

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

        $item_id = $this->getMapper()->getLastInsertValue();
        if ($parent !== null) {
            $this->updateParentId($item_id, $parent);
        }
        if ($materials !== null) {
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
        if ($materials !== null) {
            $this->getServiceItemMaterialDocumentRelation()->replaceByItem($id, $materials);
        }

        return $this->getMapper()->update($m_item);
    }

    /**
     * @invokable
     *
     * @param int $course
     *
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
    
    public function getListRecord($course, $user, $is_student)
    {
    	$res_item = $this->getMapper()->getListRecord($course, $user, $is_student);
    	
    	foreach ($res_item as $m_item) {
    		$m_item->setItemProg($this->getServiceItemProg()->getListRecord($m_item->getId(), $user, $is_student));
    	}
    	
    	return $res_item;
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

        if ($res_item->count() > 0) {
            foreach ($res_item as $m_item) {
                $nbr += $this->delete($m_item->getId());
            }
        }

        return $nbr;
    }

    /**
     * @invokable
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        $this->getServiceItemMaterialDocumentRelation()->deleteByItem($id);
        $this->getServiceItemProg()->deleteByItem($id);

        return $this->getMapper()->delete($this->getModel()->setId($id));
    }

    /**
     * @invokable
     *
     * @param array  $program
     * @param array  $course
     * @param string $type
     * @param bool   $not_graded
     * @param bool   $new_message
     * @param array  $filter
     */
    public function getListGrade($program, $course = null, $type = null, $not_graded = null, $new_message = null, $filter = null)
    {
        $mapper = $this->getMapper();
        $user = $this->getServiceUser()->getIdentity();

        $res_item = $mapper->usePaginator($filter)->getListGrade($user, $program, $course, $type, $not_graded, $new_message, $filter);

        foreach ($res_item as $m_item) {
        	$item_prog_id = $m_item->getItemProg()->getId();
            $m_item->setUsers($this->getServiceUser()->getListByItemProg($item_prog_id));
        }

        return array('count' => $mapper->count(), 'list' => $res_item);
    }

    /**
     * @invokable
     *
     * @param int $course
     * @param int $user
     */
    public function getListGradeDetail($course, $user = null)
    {
        $identity = $this->getServiceUser()->getIdentity();
        if($user === null || in_array(\Application\Model\Role::ROLE_STUDENT_STR, $identity['roles'])){
            $user = $identity['id'];
        }
        $res_grading_policy = $this->getServiceGradingPolicy()->getListByCourse($course, $user);

        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setItems($this->getMapper()->getListGradeItem($m_grading_policy->getId(), $course, $user));
        }

        return $res_grading_policy;
    }

    /**
     * @invokable
     *
     * @param int $module
     *
     * @return array
     */
    public function getListByModule($module)
    {
        $res_items = $this->getMapper()->select($this->getModel()->setModuleId($module));

        foreach ($res_items as $m_item) {
            $m_item->setItemProg($this->getServiceItemProg()->getList($m_item->getId()));
        }

        return $res_items;
    }

    /**
     * @param int $item_prog
     *
     * @throws \Exception
     *
     * @return \Application\Model\Item
     */
    public function getByItemProg($item_prog)
    {
        $res_item = $this->getMapper()->getByItemProg($item_prog);

        if ($res_item->count() <= 0) {
            throw new \Exception('error select item by itemprog');
        }

        return $res_item->current();
    }
    
    /**
     * @param int $item
     *
     * @throws \Exception
     *
     * @return \Application\Model\Item
     */
    public function get($item)
    {
    	$res_item = $this->getMapper()->select($this->getModel()->setId($item));
    
    	if ($res_item->count() <= 0) {
    		throw new \Exception('error select item');
    	}
    
    	return $res_item->current();
    }

    /**
     * @return \Application\Service\ItemMaterialDocumentRelation
     */
    public function getServiceItemMaterialDocumentRelation()
    {
        return $this->getServiceLocator()->get('app_service_item_material_document_relation');
    }

    /**
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_item_prog');
    }

    /**
     * @return \Application\Service\GradingPolicy
     */
    public function getServiceGradingPolicy()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy');
    }

    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}
