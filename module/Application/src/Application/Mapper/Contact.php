<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Dal\Db\Sql\Select;

class Contact extends AbstractMapper
{
    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($user_id, $exclude = null, $search = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['accepted_date', 'contact_id', 'user_id'])
          ->join('user', 'user.id=contact.contact_id', [])
          ->where(array('contact.user_id' => $user_id))
          ->where(array('contact.accepted_date IS NOT NULL'))
          ->where(array('contact.deleted_date IS NULL'))
          ->where(array('user.deleted_date IS NULL'));

        if (!empty($exclude)) {
            $select->where->notIn('contact.contact_id', $exclude);
        }

        if (null !== $search) {
            $select->where(array('( CONCAT_WS(" ", user.firstname, user.lastname) LIKE ? ' => ''.$search.'%'))
            ->where(array('CONCAT_WS(" ", user.lastname, user.firstname) LIKE ? ' => ''.$search.'%'), Predicate::OP_OR)
            ->where(array('user.nickname LIKE ? )' => ''.$search.'%'), Predicate::OP_OR);
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
    
    public function getAcceptedCount($me, $interval, $start_date = null, $end_date = null, $organization_id = null){
        $select = $this->tableGateway->getSql()->select();
        $select->columns([ 'contact$accepted_date' => new Expression(' SUBSTRING(contact.accepted_date,1,'.$interval.')'), 
                           'contact$accepted' => new Expression('SUM(contact.accepted)')])
            ->where(['contact.accepted_date BETWEEN ? AND ? ' => [$start_date, $end_date ]])
            ->where('contact.deleted_date IS NULL')
            ->group(new Expression(' SUBSTRING(contact.accepted_date,1,'.$interval.')'));
        
        if (null != $organization_id)
        {
            $select->join('user', 'contact.contact_id = user.id', [])
                ->join('page_user', 'user.id = page_user.user_id', [])
                ->where(['page_user.page_id' => $organization_id])
                ->group('contact.id');
        }
        return $this->selectWith($select);
        
      
    }
    
     public function getRequestsCount($me, $interval, $start_date = null, $end_date = null, $organization_id = null){
         $select = $this->tableGateway->getSql()->select();
        $select->columns([ 'contact$request_date' => new Expression(' SUBSTRING(contact.request_date,1,'.$interval.')'), 
                           'contact$requested' => new Expression('SUM(contact.requested)')])
            ->where(['contact.request_date BETWEEN ? AND ? ' => [$start_date, $end_date ]])
            ->where('contact.deleted_date IS NULL')
            ->group(new Expression(' SUBSTRING(contact.request_date,1,'.$interval.')'));
        
        if (null != $organization_id)
        {
            $select->join('user', 'contact.user_id = user.id', [])
                ->join('page_user', 'user.id = page_user.user_id', [])
                ->where(['page_user.page_id' => $organization_id]);
        }
        return $this->selectWith($select);
        
      
    }
}
