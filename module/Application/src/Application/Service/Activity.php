<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Activity extends AbstractService
{
    /**
     * @invokable
     * 
     * @param array $source
     * @param string $date
     * @param string $event
     * @param array $object
     * @param array $target
     * @throws \Exception
     * 
     * @return integer
     * 
     */
    public function add($source = null, $date = null, $event = null, $object = null, $target = null)
    {
        $m_activity = $this->getModel();
        $m_activity->setEvent($event);
        $m_activity->setDate($date);
        
        if(null !== $source) {
            if(isset($source['id'])) {
                $m_activity->setSourceId($source['id']);
            }
            if(isset($source['name'])) {
                $m_activity->setSourceName($source['name']);
            }
            if(isset($source['data'])) {
                $m_activity->setSourceData($source['data']);
            }
        }
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
        
        if($this->getMapper()->insert($m_activity) <=0 ){
            throw new \Exception('error insert ativity');
        }
        
        return $this->getMapper()->getLastInsertValue();
    }
}