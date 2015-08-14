<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Country extends AbstractMapper
{
    /**
     * Get Country list.
     *
     * @param string $filter
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($filter = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','short_name'));
        syslog(1,$select);
        return $this->selectWith($select);
    }
}
