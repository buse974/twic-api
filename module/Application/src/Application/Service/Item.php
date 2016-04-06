<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;
use \Application\Model\Item as ModelItem;
use Zend\Db\Sql\Predicate\Operator;
use Application\Model\Conversation as ModelConversation;

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
     * @param string $opt          
     * @param string $parent_id           
     * @param string $order_id          
     *
     * @throws \Exception
     *
     * @return integer
     */
    public function add($course, $grading_policy = null, $title = null, $describe = null, $duration = null, $type = null, $data = null, $ct = null, $opt = null, $set = null, $start = null, $end = null, $cut_off = null, $parent_id = null, $order_id = null)
    {
        $m_item = $this->getModel()
            ->setCourseId($course)
            ->setGradingPolicyId($grading_policy)
            ->setTitle($title)
            ->setDescribe($describe)
            ->setDuration($duration)
            ->setType($type)
            ->setStart($start)
            ->setEnd($end)
            ->setCutOff($cut_off)
            ->setSetId($set)
            ->setParentId(($parent_id === 0) ? null : $parent_id);
        
        if ($this->getMapper()->insert($m_item) <= 0) {
            throw new \Exception('error insert item');
        }
        
        $item_id = $this->getMapper()->getLastInsertValue();
        
        $this->updateOrderId($item_id,$parent_id, $order_id);
        
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
        
        if(null !== $opt) {
            if(isset($opt['grading'])) {
                $this->getServiceOptGrading()->add($item_id, 
                    (isset($opt['grading']['mode'])) ? $opt['grading']['mode'] : null,
                    (isset($opt['grading']['has_pg'])) ? $opt['grading']['has_pg'] : null,
                    (isset($opt['grading']['pg_nb'])) ? $opt['grading']['pg_nb'] : null,
                    (isset($opt['grading']['pg_auto'])) ? $opt['grading']['pg_auto'] : null,
                    (isset($opt['grading']['pg_due_date'])) ? $opt['grading']['pg_due_date'] : null,
                    (isset($opt['grading']['pg_can_view'])) ? $opt['grading']['pg_can_view'] : null,
                    (isset($opt['grading']['user_can_view'])) ? $opt['grading']['user_can_view'] : null,
                    (isset($opt['grading']['pg_stars'])) ? $opt['grading']['pg_stars'] : null);
            }
        }
        switch ($type) {
            case ModelItem::TYPE_DOCUMENT:
                $link = isset($data['link']) ? $data['link'] : null;
                $token = isset($data['token']) ? $data['token'] : null;
                $name = isset($data['name']) ? $data['name'] : null;
                $type = isset($data['type']) ? $data['type'] : null;
                $this->getServiceDocument()->add($name, $type, $link, $token, $item_id);
                break;
            case ModelItem::TYPE_POLL:
                $ti = isset($data['title']) ? $data['title'] : $title;
                $poll_item  = isset($data['poll_item']) ? $data['poll_item'] : null;
                $expiration = isset($data['expiration']) ? $data['expiration'] : null;
                $time_limit = isset($data['time_limit']) ? $data['time_limit'] : null;
                $this->getServicePoll()->add($ti, $poll_item, $expiration, $time_limit, $item_id);
                break;
            case ModelItem::TYPE_CHAT:
                $this->getServiceConversation()->create(ModelConversation::TYPE_ITEM_CHAT, $item_id);
                break;
            case ModelItem::TYPE_DISCUSSION:
                if($thread_id = isset($data['thread_id']) ? $data['thread_id'] : null) {
                    $this->getServiceThread()->update($thread_id,null,$item_id);
                } else {
                    $this->getServiceThread()->add($title, $course, $describe, $item_id);
                }
                break;
            case ModelItem::TYPE_WORKGROUP:
                $record = isset($data['record']) ? $data['record'] : null;
                $nb_user_autorecord = isset($data['nb_user_autorecord']) ? $data['nb_user_autorecord'] : null;
                $allow_intructor = isset($data['allow_intructor']) ? $data['allow_intructor'] : null;
                $this->getServiceOptVideoconf()->add($item_id, $record, $nb_user_autorecord, $allow_intructor);
                break;
            case ModelItem::TYPE_LIVE_CLASS:
                $record = isset($data['record']) ? $data['record'] : null;
                $nb_user_autorecord = isset($data['nb_user_autorecord']) ? $data['nb_user_autorecord'] : null;
                $this->getServiceOptVideoconf()->add($item_id, $record, $nb_user_autorecord, true);
                break;
        }
        
        if(isset($data['opt_eqcq']) && $data['opt_eqcq']==1) {
            $this->add($course,null,null,null,null,ModelItem::TYPE_EQCQ,null,null,null,null,null,null,null,$item_id);
        }
        if(isset($data['opt_assignment']) && $data['opt_assignment']==1) {
            $this->add($course,null,null,null,null,ModelItem::TYPE_INDIVIDUAL_ASSIGNMENT,null,null,null,null,null,null,null,$item_id);
        }
        
        return $item_id;
    }

    /**
     * @invokable
     * 
     * @param integer $item_id
     */
    public function getListUsers($item_id)
    {
        return $this->getServiceUser()->getListUsersGroupByItemAndUser($item_id);
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
     * @param string $parent_id         
     * @param string $order_id          
     *
     * @return integer
     */
    public function update($id, $grading_policy = null, $title = null, $describe = null, $duration = null, $type = null, $data = null, $start = null, $end = null, $cut_off = null, $parent_id = null, $order_id = null)
    {
        $m_item = $this->getModel()
            ->setId($id)
            ->setGradingPolicyId(($grading_policy === 0) ? new IsNull() : $grading_policy)
            ->setTitle($title)
            ->setDescribe($describe)
            ->setDuration($duration)
            ->setStart($start)
            ->setEnd($end)
            ->setCutOff($cut_off)
            ->setType($type)
            ->setParentId(($parent_id === 0) ? new IsNull():$parent_id);
        
         if ($order_id !== null || $parent_id !== null ) {
         	$this->updateOrderId($id, $parent_id, $order_id);
         }

        return $this->getMapper()->update($m_item);
    }

    /**
     * @invokable
     *
     * @param int $course            
     * @param integer $parent_id            
     *
     * @return array
     */
    public function getList($course, $parent_id = null)
    {
        return $this->getMapper()->select($this->getModel()
            ->setCourseId($course)
            ->setParentId(($parent_id === 0 || null === $parent_id) ? new IsNull() : $parent_id))->toArrayParent('order_id');
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
            ->toArray();
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
    	$this->sort($id);
    	
        return $this->getMapper()->delete($this->getModel()->setId($id));
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
        $res_item = $this->getMapper()->getAllow($id);
        
        if ($res_item->count() <= 0) {
            throw new \Exception('error select item');
        }
        
        $m_item = $res_item->current();
        $m_item->setCtDate($this->getServiceCtDate()->get($m_item->getId()))
               ->setCtDone($this->getServiceCtDone()->get($m_item->getId()))
               ->setCtRate($this->getServiceCtRate()->get($m_item->getId()))
               ->setCtGroup($this->getServiceCtGroup()->get($m_item->getId()));
        
        return $m_item;
    }

    public function updateOrderId($item, $parent_target = null,$order_id = null)
    {
        $me_item = $this->getMapper()
        ->select($this->getModel()
            ->setId($item))
            ->current();
    
            $parent_id = ($me_item->getParentId() == null || $me_item->getParentId() instanceof IsNull)?new IsNull('parent_id'): ['parent_id' => $me_item->getParentId()];
            $sort 	 = ['order_id' => $item,'course_id' => $me_item->getCourseId()];
            $rentre  = [new Operator('id',Operator::OP_NE, $item), 'course_id' => $me_item->getCourseId()];
            $sortp = $rentrep = [];
    
            $parent_target = ($parent_target === null) ? $parent_id: (($parent_target === 0) ? new IsNull('parent_id'):['parent_id' => $parent_target]);
            $order  = ($order_id === null || $order_id === 0)?new IsNull('order_id'): ['order_id' => $order_id];
    
            if(is_array($parent_id)) {
                $sort	= array_merge($sort,$parent_id);
            }else {
                $sortp[]   = $parent_id;
            }
            if(is_array($parent_target)) {
                $rentre = array_merge($rentre,$parent_target);
            }else {
                $rentrep[] = $parent_target;
            }
            if(is_array($order)) {
                $rentre = array_merge($rentre,$order);
            }else {
                $rentrep[] = $order;
            }
    
            $sort = array_merge($sortp, $sort);
            $rentre = array_merge($rentrep, $rentre);
    
            // JE SORT
            $this->getMapper()->update($this->getModel()->setOrderId($me_item->getOrderId() === null ? new IsNull() : $me_item->getOrderId()), $sort);
    
            // JE RENTRE
            $this->getMapper()->update($this->getModel()->setOrderId($item), $rentre);
            $this->getMapper()->update($this->getModel()
                ->setId($item)
                ->setOrderId(($order_id === null || $order_id === 0) ? new IsNull():$order_id));
    
    }
    
    public function sort($item)
    {
        $me_item = $this->getMapper()
        ->select($this->getModel()
            ->setId($item))
            ->current();
             
            return $this->getMapper()->update($this->getModel()->setOrderId($me_item->getOrderId() === null ? new IsNull() : $me_item->getOrderId()), [
                'order_id' => $me_item->getId(),
                'course_id' => $me_item->getCourseId()
            ]);
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
    
    /**
     *
     * @return \Application\Service\OptGrading
     */
    public function getServiceOptGrading()
    {
        return $this->getServiceLocator()->get('app_service_opt_grading');
    }
    
    /**
     *
     * @return \Application\Service\Conversation
     */
    public function getServiceConversation()
    {
        return $this->getServiceLocator()->get('app_service_conversation');
    }
    
    /**
     *
     * @return \Application\Service\Thread
     */
    public function getServiceThread()
    {
        return $this->getServiceLocator()->get('app_service_thread');
    }
    
    /**
     *
     * @return \Application\Service\OptVideoconf
     */
    public function getServiceOptVideoconf()
    {
        return $this->getServiceLocator()->get('app_service_opt_videoconf');
    }
    
}
