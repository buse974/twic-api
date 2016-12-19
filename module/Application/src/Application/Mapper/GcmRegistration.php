<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Predicate;

class GcmRegistration extends AbstractMapper
{
    /**
     * @param string $uuid
     * @param string $registration_id
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($uuid = null, $registration_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(['registration_id', 'gcm_group_id', 'uuid'])
            ->join('gcm_group', 'gcm_group.id=gcm_registration.gcm_group_id', array('id', 'notification_key_name', 'notification_key'));
        
        if (null !== $uuid && null !== $registration_id) {
            $select->where(['gcm_registration.registration_id' => $registration_id])
                ->where(['gcm_registration.uuid' => $uuid], Predicate::OP_OR);
        } elseif (null !== $uuid) {
            $select->where(['gcm_registration.uuid' => $uuid]);
        } elseif (null !== $registration_id) {
            $select->where(['gcm_registration.registration_id' => $registration_id]);
        }
        
        return $this->selectWith($select);
    }
}
