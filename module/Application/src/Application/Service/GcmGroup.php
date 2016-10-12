<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class GcmGroup extends AbstractService
{
    /**
     *
     * @param string $notification_key_name
     * @param string|array $registration_ids
     *
     * @throws \Exception
     * @return boolean
     */
    public function create($notification_key_name, $registration_ids)
    {
        if (! is_array($registration_ids)) {
            $registration_ids = [$registration_ids];
        }
        
        $m_gcm_group = $this->get($notification_key_name);
        $gcm_group_id = $m_gcm_group->getId();
        
        $fregistration_ids = [];
        foreach ($registration_ids as $registration_id) {
            if(!$this->getServiceGcmRegistration()->has($gcm_group_id, $registration_id)) {
                $fregistration_ids[] = $registration_id;
            }
        }
        if(!empty($fregistration_ids)) {
            //on suprime toutes les registration
            $this->getServiceGcmRegistration()->delete($fregistration_ids);
            
            $notification_key = ( false !== $m_gcm_group ) ? $m_gcm_group->getNotificationKey() : null;
            $rep = $this->getServiceGcmRegistration()->addFcm($notification_key_name, $fregistration_ids, $notification_key);
            $gcm_group_id = ( false !== $m_gcm_group ) ? $m_gcm_group->getId() : $this->add($notification_key_name, $rep['notification_key']);
        
            // on ajoute a la bdd les registration;
            return $this->getServiceGcmRegistration()->add($gcm_group_id, $fregistration_ids);
        }
        
        return false;
    }
    
    /**
     * Add GcmGroup In Bdd
     *
     * @param string $notification_key_name
     * @param string $notification_key
     * @throws \Exception
     * @return int
     */
    public function add($notification_key_name, $notification_key)
    {
        $m_gcm_group = $this->getModel()
        ->setNotificationKeyName($notification_key_name)
        ->setNotificationKey($notification_key);
    
        if ($this->getMapper()->insert($m_gcm_group) <= 0) {
            throw new \Exception('error insert gcm group');
        }
    
        return $this->getMapper()->getLastInsertValue();
    }
    
    public function getNotificationKey($notification_key_name)
    {
        $m_gcm_group = $this->get($notification_key_name);
    
        return ($m_gcm_group !== false) ? $m_gcm_group->getNotificationKey() : false;
    }
    
    /**
     *
     * @param string $notification_key_name
     *
     * @return \Application\Model\GcmGroup
     */
    public function get($notification_key_name)
    {
        $res_gcm_group = $this->getMapper()->select($this->getModel()
            ->setNotificationKeyName($notification_key_name));
    
        return ($res_gcm_group->count() > 0) ? $res_gcm_group->current() : false;
    }
    
    /**
     *
     * @return \Application\Service\GcmRegistration
     */
    private function getServiceGcmRegistration()
    {
        return $this->container->get('app_service_gcm_registration');
    }

}