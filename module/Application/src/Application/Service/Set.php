<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Set extends AbstractService
{
    /**
     * @invokable
     * 
     * @param integer $course
     * @param string $uid
     * @param string $name
     * @param string $groups
     * 
     * @throws \Exception
     * 
     * @return integer
     */
    public function add($course, $name, $uid = null, $groups = null)
    {
        $m_set = $this->getModel()->setUid($uid)->setName($name)->setCourseId($course);
        
        if($this->getMapper()->insert($m_set) <= 0) {
            throw new \Exception('error insert set group');
        }
        
        $set_id = $this->getMapper()->getLastInsertValue();
        
        if(null != $groups) {
            foreach ($groups as $group) {
                $name = (isset($group['name'])) ? $group['name'] : null;
                $uid = (isset($group['uid'])) ? $group['uid'] : null;
                $users = (isset($group['users'])) ? $group['users'] : null;
                
                $this->getServiceGroup()->add($set_id, $name, $uid, $users);
            }
        }
        
        return $this->get($set_id);
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * 
     * @return integer
     */
    public function delete($id) 
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * @param string $uid
     * @param string $name
     * 
     * @return integer
     */
    public function update($id, $uid = null, $name = null)
    {
        return $this->getMapper()->update($this->getModel()->setId($id)->setUid($uid)->setName($name));
    }
    
    /**
     * 
     * @param integer $id
     * 
     * @return \Application\Model\Set
     */
    public function get($id) 
    {
        $res_set = $this->getMapper()->select($this->getModel()->setId($id));
        
        if($res_set->count() <= 0) {
            throw new \Exception('error select set group');
        }
        
        $m_set =  $res_set->current();
        $m_set->setGroups($this->getServiceGroup()->getList($m_set->getId()));
        
        return $m_set;
    }
    
    /**
     * @return \Application\Service\Group
     */
    public function getServiceGroup()
    {
        return $this->getServiceLocator()->get('app_service_group');
    }
}