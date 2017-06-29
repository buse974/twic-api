<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ItemUser extends AbstractMapper
{
  public function getList($item_id)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id', 'user_id', 'item_id', 'submission_id'])
      ->join('group', 'item_user.group_id=group.id', ['group!id' => 'id', 'name'], $select::JOIN_LEFT)
      ->where(['item_user.deleted_date IS NULL']);

    return $this->selectWith($select);
  }
}
