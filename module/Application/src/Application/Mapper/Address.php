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
        $select->columns(array('id', 'street_no', 'street_type', 'street_name', 'floor', 'door', 'apartment', 'building', 'city_id', 'division_id', 'country_id', 'longitude', 'latitude', 'timezone'))
       
               ->join(array('address_division' => 'division'), 'address_division.id=address.division_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('address_city' => 'city'), 'address_city.id=address.city_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('address_country' => 'country'), 'address_country.id=address.country_id', array('id', 'short_name', 'name'),  $select::JOIN_LEFT);
                
        $select->where('school.deleted_date IS NULL');
        return $this->selectWith($select);
    }
}
