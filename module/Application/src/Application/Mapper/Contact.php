<?php
namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Contact extends AbstractMapper
{

    /**
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($user)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('accepted_date'))
            ->join('user', 'user.id=contact.contact_id', array('id','firstname','lastname','avatar'), $select::JOIN_INNER)
            ->where(array('contact.user_id' => $user))
            ->where(array('contact.accepted_date IS NOT NULL'))
            ->where(array('contact.deleted_date IS NULL'));
        
        return $this->selectWith($select);
    }
    
    /**
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getListRequest($user)
    {
        $select = $this->tableGateway->getSql()->select();
    
        $select->columns(array('request_date'))
            ->join('user', 'user.id=contact.contact_id', array('id','firstname','lastname','avatar'), $select::JOIN_INNER)
            ->where(array('contact.contact_id' => $user))
            ->where(array('contact.accepted_date IS NULL'))
            ->where(array('contact.request_date IS NOT NULL'));
    
        return $this->selectWith($select);
    }
}
