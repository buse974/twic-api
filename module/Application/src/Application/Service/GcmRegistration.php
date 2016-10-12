<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use ZendService\Google\Gcm\DeviceGroup;

class GcmRegistration extends AbstractService
{
    public function addFcm($notification_key_name, $registration_ids, $notification_key = null)
    {
        if (!is_array($registration_ids)) {
            $registration_ids = [$registration_ids];
        }
        $dg = new DeviceGroup();
        $dg->setOperation((null !== $notification_key)?'add':'create')
            ->setNotificationKeyName($notification_key_name)
            ->setNotificationKey($notification_key)
            ->setRegistrationIds($registration_ids);
    
        return $this->getServiceGcmClient()->sendDeviceGroup($dg);
    }
    
    
    /**
     * @param int $gcm_group_id
     * @param int|array $registration_ids
     * @return boolean
     */
    public function add($gcm_group_id, $registration_ids)
    {
        if (!is_array($registration_ids)) {
            $registration_ids = [$registration_ids];
        }
    
        $m_gcm_registration = $this->getModel()->setGcmGroupId($gcm_group_id);
        foreach ($registration_ids as $registration_id) {
            $this->getMapper()->insert($m_gcm_registration->setRegistrationId($registration_id));
        }
    
        return true;
    }
    
    /**
     * Get If relation exist
     *
     * @param int   $gcm_group_id
     * @param array $registration_id
     *
     * @return bool
     */
    public function has($gcm_group_id, $registration_id)
    {
        $m_gcm_registration = $this->getModel()->setGcmGroupId($gcm_group_id)->setRegistrationId($registration_id);
    
        return $this->getMapper()->select($m_gcm_registration)->count() > 0;
    }
    
    /**
     * @param int $registration_id
     */
    public function delete($registration_id)
    {
        $res_gcm_registration = $this->getMapper()->getListByRegistrationId($registration_id);
        foreach ($res_gcm_registration as $m_gcm_registration) {
            $dg = new DeviceGroup();
            $dg->setOperation('remove')
                ->setNotificationKey($m_gcm_registration->getGcmGroup()->getNotificationKey())
                ->setNotificationKeyName($m_gcm_registration->getGcmGroup()->getNotificationKeyName())
                ->setRegistrationIds([$m_gcm_registration->getRegistrationId()]);
    
            $this->getServiceGcmClient()->sendDeviceGroup($dg);
            $this->getMapper()->delete($m_gcm_registration);
        }
    
        return true;
    }
    
    /**
     * @return \ZendService\Google\Gcm\Client
     */
    private function getServiceGcmClient()
    {
        return $this->container->get('gcm-client');
    }
}