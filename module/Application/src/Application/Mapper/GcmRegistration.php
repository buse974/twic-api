<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class GcmRegistration extends AbstractMapper
{
    /**
     * @param string $registration_id
     */
    public function getListByRegistrationId($registration_id)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(['registration_id', 'gcm_group_id'])
        ->join('gcm_group', 'gcm_group.id=gcm_registration.gcm_group_id', array('id', 'notification_key_name', 'notification_key'))
        ->where(array('gcm_registration.registration_id' => $registration_id));
    
        return $this->selectWith($select);
    }
}