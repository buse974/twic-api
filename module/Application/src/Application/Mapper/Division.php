<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Division extends AbstractMapper
{
    /**
     * Get division list.
     *
     * @param string $filter
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'short_name', 'libelle', 'code', 'country_id', 'longitude', 'latitude'))

               ->join(array('division_country' => 'country'), 'division_country.id=division.country_id', array('id', 'short_name', 'name'),  $select::JOIN_LEFT);

        return $this->selectWith($select);
    }
}
