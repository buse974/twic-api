<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Activity extends AbstractService
{
    /**
     * @invokable
     * 
     * @param array $activities
     * 
     * @return array
     * 
     */
    public function add($activities)
    {
        $ret = [];
        $user = $this->getServiceUser()->getIdentity()['id'];        
        foreach ($activities as $activity) {
            $date   = (isset($activity['date']))   ? $activity['date']  :null;
            $event  = (isset($activity['event']))  ? $activity['event'] :null;
            $object = (isset($activity['object'])) ? $activity['object']:null;
            $target = (isset($activity['target'])) ? $activity['target']:null;
            
            $ret[] = $this->_add($date, $event, $object, $target, $user);
        }
        
        return $ret;
    }
    
    /**
     * @param string $date
     * @param string $event
     * @param array $object
     * @param array $target
     * @throws \Exception
     *
     * @return integer
     *
     */
    public function _add($date = null, $event = null, $object = null, $target = null, $user = null)
    {
        $m_activity = $this->getModel();
        $m_activity->setEvent($event);
        $m_activity->setDate($date);
        $m_activity->setUserId($user);
        
        if(null !== $object) {
            if(isset($object['id'])) {
                $m_activity->setObjectId($object['id']);
            }
            if(isset($object['value'])) {
                $m_activity->setObjectValue($object['value']);
            }
            if(isset($object['name'])) {
                $m_activity->setObjectName($object['name']);
            }
            if(isset($object['data'])) {
                $m_activity->setObjectData(json_encode($object['data']));
            }
        }
        if(null !== $target) {
            if(isset($target['id'])) {
                $m_activity->setTargetId($target['id']);
            }
            if(isset($target['name'])) {
                $m_activity->setTargetName($target['name']);
            }
            if(isset($target['data'])) {
                $m_activity->setTargetData(json_encode($target['data']));
            }
        }
        
        if($this->getMapper()->insert($m_activity) <=0 ){
            throw new \Exception('error insert ativity');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }
    
    /**
     * @invokable
     * 
     * @param string $date
     * @param string $event
     * @param array $object
     * @param array $target
     * @param array $user
     * 
     * @throws \Exception
     * 
     * @return integer
     * 
     */
    public function getList($date = null, $event = null, $object = null, $target = null, $user = null)
    {
        $m_activity = $this->getModel();
        $m_activity->setEvent($event)
                   ->setDate($date)
                   ->setUserId($user);
        
        if(null !== $object) {
            if(isset($object['id'])) {
                $m_activity->setObjectId($object['id']);
            }
            if(isset($object['name'])) {
                $m_activity->setObjectName($object['name']);
            }
            if(isset($object['data'])) {
                $m_activity->setObjectData($object['data']);
            }
        }
        if(null !== $target) {
            if(isset($target['id'])) {
                $m_activity->setTargetId($target['id']);
            }
            if(isset($target['name'])) {
                $m_activity->setTargetName($target['name']);
            }
            if(isset($target['data'])) {
                $m_activity->setTargetData($target['data']);
            }
        }
        
        return $this->getMapper()->select($m_activity);
    }
    

    
    /**
     * @invokable
     * 
     * @param array|string $event
     * @param integer $user
     * @param integer $object_id
     * @param string $object_name
     */
    public function aggregate($event, $user, $object_id, $object_name)
    {
        $ret = [];
        if(!is_array($event)) {
            $event = array($event);
        }
        
        foreach ($event as $e) {
            $ret[] = $this->getMapper()->aggregate($e, $user, $object_id, $object_name)->current();
        }
        
        return $ret;
    }
    
    /**
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}