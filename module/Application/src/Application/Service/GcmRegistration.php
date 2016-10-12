<?php
namespace Application\Service;

use Dal\Service\AbstractService;
use ZendService\Google\Gcm\DeviceGroup;

class GcmRegistration extends AbstractService
{

    /**
     * 
     * @param string $notification_key_name
     * @param string $registration_id
     * @param string $notification_key
     */
    public function addFcm($notification_key_name, $registration_id, $notification_key = null)
    {
        $dg = new DeviceGroup();
        $dg->setOperation((null !== $notification_key) ? 'add' : 'create')
            ->setNotificationKeyName($notification_key_name)
            ->setNotificationKey($notification_key)
            ->setRegistrationIds([$registration_id]);
        
        return $this->getServiceGcmClient()->sendDeviceGroup($dg);
    }

    /**
     *
     * @param int $gcm_group_id
     * @param string $uuid
     * @param string $registration_id
     * @return number
     */
    public function add($gcm_group_id, $uuid, $registration_id)
    {
        $m_gcm_registration = $this->getModel()
            ->setGcmGroupId($gcm_group_id)
            ->setRegistrationId($registration_id)
            ->setUuid($uuid);
        
        return $this->getMapper()->insert($m_gcm_registration);
    }

    /**
     * Get If relation exist
     *
     * @param int $gcm_group_id            
     * @param string $uuid            
     * @param string $registration_id            
     * @return bool
     */
    public function has($gcm_group_id, $uuid, $registration_id)
    {
        $m_gcm_registration = $this->getModel()
            ->setGcmGroupId($gcm_group_id)
            ->setRegistrationId($registration_id)
            ->setUuid($uuid);
        
        return $this->getMapper()
            ->select($m_gcm_registration)
            ->count() > 0;
    }

    /*
     * @param string $uuid
     * @param string $registration_id
     * @return bool
     */
    public function delete($uuid, $registration_id)
    {
        $res_gcm_registration = $this->getMapper()->getList($uuid, $registration_id);
        foreach ($res_gcm_registration as $m_gcm_registration) {
            $dg = new DeviceGroup();
            $dg->setOperation('remove')
                ->setNotificationKey($m_gcm_registration->getGcmGroup()->getNotificationKey())
                ->setNotificationKeyName($m_gcm_registration->getGcmGroup()->getNotificationKeyName())
                ->setRegistrationIds([$m_gcm_registration->getRegistrationId()]);
            
            try {
                $this->getServiceGcmClient()->sendDeviceGroup($dg);
            } catch (\Exception $e) {
                syslog(1, "Error fcm remove : ". $e->getMessage());
            }
            $this->getMapper()->delete($m_gcm_registration);
        }
        
        return true;
    }

    /**
     *
     * @return \ZendService\Google\Gcm\Client
     */
    private function getServiceGcmClient()
    {
        return $this->container->get('gcm-client');
    }
}