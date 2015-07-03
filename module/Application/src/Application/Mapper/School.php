<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class School extends AbstractMapper
{
    /**
     * Get school by id.
     *
     * @param int $school
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function get($school)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'name', 'next_name', 'short_name', 'logo', 'describe', 'website', 'programme', 'backroung', 'phone', 'contact', 'address_id'))
               ->join(array('school_user' => 'user'), 'school_user.id=school.contact_id', array('id', 'firstname', 'lastname', 'status', 'email', 'birth_date', 'position', 'interest', 'avatar'), $select::JOIN_LEFT)
               ->join(array('school_address' => 'address'), 'school.address_id = school_address.id', array('id', 'street_no', 'street_type', 'street_name', 'floor', 'door', 'apartment', 'building', 'longitude', 'latitude', 'timezone'),  $select::JOIN_LEFT)
               ->join(array('school_address_division' => 'division'), 'school_address_division.id=school_address.division_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('school_address_city' => 'city'), 'school_address_city.id=school_address.city_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('school_address_country' => 'country'), 'school_address_country.id=school_address.country_id', array('id', 'short_name', 'name'),  $select::JOIN_LEFT)
               ->where(array('school.id' => $school));

        return $this->selectWith($select);
    }
    
    /**
     * Get school list
     * 
     * @param string $school
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'short_name', 'logo', 'describe', 'website', 'backroung', 'phone'))
                ->where('school.deleted_date IS NULL');
        
        return $this->selectWith($select);
    }
}
