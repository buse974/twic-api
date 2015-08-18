<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Country extends AbstractMapper
{
    /**
     * Get Country list.
     *
     * @param string $filter
     * @param string $string
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getList($string)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','short_name'))
                ->where(array('short_name LIKE ?' => '%' .$string . '%'));

        return $this->selectWith($select);
    }
}
