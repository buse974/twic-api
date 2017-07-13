<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class ItemUser extends AbstractMapper
{
  public function getList($item_id, $user_id = null, $submission_id = null)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id', 'user_id', 'item_id', 'submission_id', 'rate'])
      ->join('group', 'item_user.group_id=group.id', ['group!id' => 'id', 'name'], $select::JOIN_LEFT)
      ->where(['item_user.deleted_date IS NULL'])
      ->where(['item_user.item_id' => $item_id]);
    if(null !== $user_id){
        $select->where(['item_user.user_id' => $user_id]);
    } 
    if(null !== $submission_id){
        $select->where(['item_user.submission_id' => $submission_id]);
    }

    return $this->selectWith($select);
  }
}
