<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class City extends AbstractMapper
{
    /**
     * Get city list.
     *
     * @param string $filter
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'libelle', 'code', 'division_id', 'country_id', 'longitude', 'latitude'))
       
               ->join(array('city_division' => 'division'), 'city_division.id=city.division_id', array('id', 'name'),  $select::JOIN_LEFT)
               ->join(array('city_country' => 'country'), 'city_country.id=city.country_id', array('id', 'short_name', 'name'),  $select::JOIN_LEFT);
              
        return $this->selectWith($select);
    }
}
