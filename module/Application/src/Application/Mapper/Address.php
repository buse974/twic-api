<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Address extends AbstractMapper
{
    /**
     * Get school list.
     *
     * @param string $filter
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'next_name', 'short_name', 'logo', 'describe', 'website', 'programme', 'backroung', 'phone'))
               ->join(array('school_address' => 'address'), 'school.address_id = school_address.id', array('id', 'street_no', 'street_type', 'street_name', 'floor', 'door', 'apartment', 'building', 'longitude', 'latitude', 'timezone'),  $select::JOIN_LEFT)
               ->join(array('school_address_division' => 'division'), 'school_address_division.id=school_address.division_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('school_address_city' => 'city'), 'school_address_city.id=school_address.city_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('school_address_country' => 'country'), 'school_address_country.id=school_address.country_id', array('id', 'short_name', 'name'),  $select::JOIN_LEFT);
                
        $select->where('school.deleted_date IS NULL');
        return $this->selectWith($select);
    }
}
