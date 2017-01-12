<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Dal\Db\Sql\Select;

class Contact extends AbstractMapper
{
    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($user_id, $exclude = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('accepted_date', 'contact_id', 'user_id'))
            ->join('user', 'user.id=contact.contact_id', [])
            ->where(array('contact.user_id' => $user_id))
            ->where(array('contact.accepted_date IS NOT NULL'))
            ->where(array('contact.deleted_date IS NULL'))
            ->where(array('user.deleted_date IS NULL'));

        if ($exclude) {
            $select->where->notIn('contact.contact_id', $exclude);
        }

        return $this->selectWith($select);
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getListRequest($user = null, $contact = null)
    {
        if (null === $user && null === $contact) {
            throw new \Exception('Invalid params');
        }
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array('request_date', 'user_id', 'contact_id'))
            ->where(
                array('
                contact.request_date IS NOT NULL AND 
                contact.accepted_date IS NULL AND 
                contact.deleted_date IS NULL AND 
                requested IS false AND 
                accepted IS false AND 
                deleted IS false')
            );
        if (null !== $user) {
            $select->where(array('contact.user_id' => $user));
        }
        if (null !== $contact) {
            $select->where(array('contact.contact_id' => $contact));
        }
        return $this->selectWith($select);
    }

    /**
     * @param int $school
     *
     * @return int
     */
    public function addBySchool($school)
    {
        $insert = $this->tableGateway->getSql()->insert();
        $select = new Select('user');
        $select->columns(array('contact_id' => 'id', 'accepted_date' => new Expression('UTC_TIMESTAMP()')));
        $select->join(array('uu' => 'user'), 'uu.school_id = user.school_id AND uu.id <> user.id', array('user_id' => 'id'))
            ->join('contact', 'contact.user_id = uu.id AND contact.contact_id = user.id', array(), $select::JOIN_LEFT)
            ->where(array('contact.id IS NULL'))
            ->where(array('user.school_id' => $school));

        $insert->columns(['accepted_date', 'contact_id', 'user_id'])
            ->select($select);

        return $this->insertWith($insert);
    }
}
