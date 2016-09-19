<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Page extends AbstractMapper
{

    public function getList($id = null, $parent_id = null, $user_id = null, $organization_id = null, $start_date = null, $end_date = null)
    {
        $where = $this->getWhereParams([
            'id' => $id,
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'organization_id' => $organization_id
        ]);

        $select = $this->tableGateway->getSql()->select()->where($where);

        if (null !== $end_date) {
            $select->where([
                'page.start_date <= ?' => [$end_date]
            ]);
        }

        if (null !== $start_date) {
            $select->where([
                'page.end_date >= ?' => [$start_date]
            ]);
        }

        return $this->selectWith($select);
    }

    protected function getWhereParams($originalParams = [])
    {
        return array_filter($originalParams, function($value) {
            return null !== $value;
        });
    }

}
