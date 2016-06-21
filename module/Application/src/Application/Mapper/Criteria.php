<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Criteria extends AbstractMapper
{
    public function getListByItem($item)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'name', 'description', 'points'))
                ->join('grading_policy', 'grading_policy.id=criteria.grading_policy_id', [])
                ->join('item', 'grading_policy.id=item.grading_policy_id', [])
                ->where(['item.id' => $item]);

        return $this->selectWith($select);
    }

    public function deleteNotIn($ids, $grading_policy)
    {
        $delete = $this->tableGateway->getSql()->delete();

        $delete->where(['grading_policy_id' => $grading_policy]);
        if (count($ids) > 0) {
            $delete->where->notIn('id', $ids);
        }

        return $this->deleteWith($delete);
    }
}
