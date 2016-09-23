<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Page extends AbstractMapper
{

    public function getList($id = null, $parent_id = null, $user_id = null, $organization_id = null, $type = null, $start_date = null, $end_date = null, $member_id = null)
    {
        $where = $this->getWhereParams([
            'id' => $id,
            'parent_id' => $parent_id,
            'user_id' => $user_id,
            'organization_id' => $organization_id,
            'type' => $type
        ]);

        $select = $this->tableGateway->getSql()->select()->where($where);

        if (null !== $member_id) {
            $this->selectByMember($select, $member_id);
        }

        return $this->selectWithDates($select, $start_date, $end_date);
    }

    protected function getWhereParams($originalParams = [])
    {
        return array_filter($originalParams, function($value) {
            return null !== $value;
        });
    }

    protected function selectWithDates($select, $start_date, $end_date)
    {
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

    protected function selectByMember($select, $member_id)
    {
        return $select->join('page_user', 'page_user.page_id = page.id')
                      ->where(['page_user.user_id' => $member_id]);
    }
}
