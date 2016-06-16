<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;
use Zend\Db\Sql\Expression;

class CtDate extends AbstractMapper
{
    /**
     * @param int $item_id
     */
    public function get($item_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['id', 'item_id', 'ct_date$date' => new Expression('DATE_FORMAT(ct_date.date, "%Y-%m-%dT%TZ")'), 'after'])
            ->where(['item_id' => $item_id]);

        return $this->selectWith($select);
    }
}
