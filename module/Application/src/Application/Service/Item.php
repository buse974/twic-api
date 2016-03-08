<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use \Application\Model\Item as ModelItem;

class Item extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $course            
     * @param string $grading_policy            
     * @param string $title            
     * @param string $describe            
     * @param string $duration            
     * @param string $type            
     * @param string $data            
     * @param string $ct            
     * @param string $parent            
     * @param string $order            
     *
     * @throws \Exception
     *
     * @return integer
     */
    public function add($course, $grading_policy = null, $title = null, $describe = null, $duration = null, $type = null, $data = null, $ct = null, $set = null, $parent = null, $order = null)
    {
        $m_item = $this->getModel()
            ->setCourseId($course)
            ->setGradingPolicyId($grading_policy)
            ->setTitle($title)
            ->setDescribe($describe)
            ->setDuration($duration)
            ->setType($type)
            ->setSetId($set)
            ->setOrderId($order);
        
        if (null === $parent) {
            $m_item->setParentId($this->getMapper()->selectLastParentId($course));
        }
        
        if ($this->getMapper()->insert($m_item) <= 0) {
            throw new \Exception('error insert item');
        }
        
        $item_id = $this->getMapper()->getLastInsertValue();
        
        if ($parent !== null) {
             $this->updateParentId($item_id, $parent);
        }
        
        if(null !== $ct) {
            if(isset($ct['date'])) {
                foreach ($ct['date'] as $date) {
                    $this->getServiceCtDate()->add($item_id, $date['date'], (isset($date['after'])) ? $date['after'] : null);
                }
            }
            if(isset($ct['done'])) {
                foreach ($ct['done'] as $done) {
                    $this->getServiceCtDone()->add($item_id, $done['target'], (isset($done['all'])) ? $done['all'] : null);
                }
            
            }
            if(isset($ct['group'])) {
                foreach ($ct['group'] as $group) {
                    $this->getServiceCtGroup()->add($item_id, $group['group'], (isset($group['belongs'])) ? $group['belongs'] : null);
                }
            
            }
            if(isset($ct['rate'])) {
                foreach ($ct['rate'] as $rate) {
                    $this->getServiceCtRate()->add($item_id, $rate['target'], (isset($rate['inf'])) ? $rate['inf'] : null, (isset($rate['sup'])) ? $rate['sup'] : null);
                }
            
            }
        }
        
        switch ($type) {
            case ModelItem::TYPE_DOCUMENT:
                $link = isset($data['link']) ? $data['link'] : null;
                $token = isset($data['token']) ? $data['token'] : null;
                $ti = isset($data['title']) ? $data['title'] : null;
                $this->getServiceDocument()->add($ti, $link, $token, $item_id);
                break;
            case ModelItem::TYPE_POLL:
                $ti = isset($d['title']) ? $d['title'] : $title;
                $poll_questions = isset($d['questions']) ? $d['questions'] : null;
                $this->getServicePoll()->add($ti, $poll_questions);
                break;
        }
        
        return $item_id;
    }

    public function updateParentId($item, $parent_id)
    {
        $me_item = $this->getMapper()
            ->select($this->getModel()
            ->setId($item))
            ->current();

        if($item == $parent_id || $parent_id == $me_item->getParentId()) {
            return;
        }
       
        // JE SORT
        $this->getMapper()->update($this->getModel()->setParentId($me_item->getParentId() === null ? new IsNull() : $me_item->getParentId()), array('parent_id' => $item,'course_id' => $me_item->getCourseId()));
        // JE RENTRE
        $this->getMapper()->update($this->getModel()
            ->setParentId($item), array('parent_id' => $parent_id,'course_id' => $me_item->getCourseId()));
        $this->getMapper()->update($this->getModel()
            ->setId($item)
            ->setParentId($parent_id));
    }

    /**
     * @invokable
     *
     * @param integer $id            
     * @param string $grading_policy            
     * @param string $title            
     * @param string $describe            
     * @param string $duration            
     * @param string $type            
     * @param string $data            
     * @param string $ct            
     * @param string $parent            
     * @param string $order            
     *
     * @return integer
     */
    public function update($id, $grading_policy = null, $title = null, $describe = null, $duration = null, $type = null, $data = null, $ct = null, $parent = null, $order = null)
    {
        $m_item = $this->getModel()
            ->setId($id)
            ->setGradingPolicyId(($grading_policy === 0) ? new IsNull() : $grading_policy)
            ->setTitle($title)
            ->setDescribe($describe)
            ->setDuration($duration)
            ->setType($type)
            ->setOrderId($order);
        
         if ($parent !== null) {
            $this->updateParentId($id, $parent);
         }
        
        
        return $this->getMapper()->update($m_item);
    }

    /**
     * @invokable
     *
     * @param int $course            
     * @param integer $parent            
     *
     * @return array
     */
    public function getList($course, $parent = null)
    {
        return $this->getMapper()->select($this->getModel()
            ->setCourseId($course)
            ->setParentId(($parent === 0) ? new IsNull() : $parent))->toArrayParent();
    }

    /**
     * @invokable
     *
     * @param int $user            
     *
     * @return array
     */
    public function getListByUser($user)
    {
        return $this->getMapper()
            ->select($this->getModel()
            ->setCourseId($course))
            ->toArrayParent();
    }

    public function getListRecord($course, $user, $is_student)
    {
        $res_item = $this->getMapper()->getListRecord($course, $user, $is_student);
        
        foreach ($res_item as $m_item) {
            $m_item->setItemProg($this->getServiceItemProg()
                ->getListRecord($m_item->getId(), $user, $is_student));
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
        $m_item = $this->getModel()
            ->setType($type)
            ->setCourse($course);
        
        return $this->getMapper()->select($m_item);
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
        
        return $this->getMapper()->delete($this->getModel()
            ->setId($id));
    }

    /**
     * @invokable
     *
     * @param array $program            
     * @param array $course            
     * @param string $type            
     * @param bool $not_graded            
     * @param bool $new_message            
     * @param array $filter            
     */
    public function getListGrade($program = null, $course = null, $type = null, $not_graded = null, $new_message = null, $filter = null, $item_prog = null, $user = null)
    {
        $mapper = $this->getMapper();
        $me = $this->getServiceUser()->getIdentity();
        
        $res_item = $mapper->usePaginator($filter)->getListGrade($me, $program, $course, $type, $not_graded, $new_message, $filter, $item_prog, $user);
        
        foreach ($res_item as $m_item) {
            $item_assigment_id = $m_item->getItemProg()
                ->getItemAssignment()
                ->getId();
            if ($item_assigment_id !== null && ! $item_assigment_id instanceof IsNull) {
                $m_item->setUsers($this->getServiceUser()
                    ->getListByItemAssignment($item_assigment_id));
            }
        }
        
        return array('count' => $mapper->count(),'list' => $res_item);
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
        if ($user === null || in_array(\Application\Model\Role::ROLE_STUDENT_STR, $identity['roles'])) {
            $user = $identity['id'];
        }
        $res_grading_policy = $this->getServiceGradingPolicy()->getListByCourse($course, $user);
        
        foreach ($res_grading_policy as $m_grading_policy) {
            $m_grading_policy->setItems($this->getMapper()
                ->getListGradeItem($m_grading_policy->getId(), $course, $user));
        }
        
        return $res_grading_policy;
    }

    /**
     * @invokable
     *
     * @param int $grading_policy            
     * @param int $course            
     * @param int $user            
     * @param int $item_prog            
     */
    public function getListGradeItem($grading_policy = null, $course = null, $user = null, $item_prog = null)
    {
        return $this->getMapper()->getListGradeItem($grading_policy, $course, $user, $item_prog);
    }

    /**
     * @invokable
     *
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
     * @invokable
     *
     * @param int $id            
     *
     * @throws \Exception
     *
     * @return \Application\Model\Item
     */
    public function get($id)
    {
        $res_item = $this->getMapper()->get($id);
        
        if ($res_item->count() <= 0) {
            throw new \Exception('error select item');
        }
        
        return $res_item->current();
    }

    /**
     *
     * @return \Application\Service\ItemMaterialDocumentRelation
     */
    public function getServiceItemMaterialDocumentRelation()
    {
        return $this->getServiceLocator()->get('app_service_item_material_document_relation');
    }

    /**
     *
     * @return \Application\Service\ItemProg
     */
    public function getServiceItemProg()
    {
        return $this->getServiceLocator()->get('app_service_item_prog');
    }

    /**
     *
     * @return \Application\Service\GradingPolicy
     */
    public function getServiceGradingPolicy()
    {
        return $this->getServiceLocator()->get('app_service_grading_policy');
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }

    /**
     *
     * @return \Application\Service\Document
     */
    public function getServiceDocument()
    {
        return $this->getServiceLocator()->get('app_service_document');
    }

    /**
     *
     * @return \Application\Service\Poll
     */
    public function getServicePoll()
    {
        return $this->getServiceLocator()->get('app_service_poll');
    }
    
    
    /**
     *
     * @return \Application\Service\CtDate
     */
    public function getServiceCtDate()
    {
        return $this->getServiceLocator()->get('app_service_ct_date');
    }
    
    /**
     *
     * @return \Application\Service\CtDone
     */
    public function getServiceCtDone()
    {
        return $this->getServiceLocator()->get('app_service_ct_done');
    }
    
    /**
     *
     * @return \Application\Service\CtGroup
     */
    public function getServiceCtGroup()
    {
        return $this->getServiceLocator()->get('app_service_ct_group');
    }
    
    /**
     *
     * @return \Application\Service\CtRate
     */
    public function getServiceCtRate()
    {
        return $this->getServiceLocator()->get('app_service_ct_rate');
    }
    
    
}
