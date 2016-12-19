<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class GcmGroup extends AbstractService
{

    /**
     *
     * @param string $notification_key_name
     * @param array $registration_id
     * @param string $uuid
     * @return bool
     */
    public function create($notification_key_name, $registration_id, $uuid)
    {
        $m_gcm_group = $this->get($notification_key_name);
        if (false !== $m_gcm_group) {
            if ($this->getServiceGcmRegistration()->has($m_gcm_group->getId(), $uuid, $registration_id)) {
                return false;
            }
        }
        
        // on suprime toutes les registrations
        $this->getServiceGcmRegistration()->delete($uuid, $registration_id);
        
        $notification_key = (false !== $m_gcm_group) ? $m_gcm_group->getNotificationKey() : null;
        $rep = $this->getServiceGcmRegistration()->addFcm($notification_key_name, $registration_id, $notification_key);
        $gcm_group_id = (false !== $m_gcm_group) ? $m_gcm_group->getId() : $this->add($notification_key_name, $rep['notification_key']);
        
        // on ajoute a la bdd les registration;
        return $this->getServiceGcmRegistration()->add($gcm_group_id, $uuid, $registration_id);
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
