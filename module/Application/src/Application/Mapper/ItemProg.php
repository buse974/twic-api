<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class ItemProg extends AbstractMapper
{
    /**
     * @param int $item
     *
     * @return array
     */
    public function getList($item)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('id', 'item_prog$start_date' => new Expression("DATE_FORMAT(start_date, '%Y-%m-%dT%TZ') ")))
        ->where(array('item_prog.item_id ' => $item));

        return $this->selectWith($select);
    }

    public function getByItemAssignment($item_assignement)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('start_date', 'id'))
               ->join('item_prog', 'item_prog.item_id=item.id', array())
               ->where(array('item_prog.id' => $item_prog));

        return $this->selectWith($select);
    }
}
