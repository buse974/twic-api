<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Hashtag extends AbstractMapper
{
    public function getList($search)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('name', 'type', 'nbr' => new Expression('count(true)')))
            ->where(array('hashtag.name LIKE ? ' => '%'.$search.'%'))
            ->group(array('name', 'type'))
            ->order(array('nbr' => 'DESC'));
    
        return $this->selectWith($select);
    }
}