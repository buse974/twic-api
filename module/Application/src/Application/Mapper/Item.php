<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class Item extends AbstractMapper
{
  public function getListId($page_id, $me, $is_admin_page, $parent_id = null)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id', 'title', 'points', 'description', 'type', 'is_available', 'is_published', 'order', 'start_date', 'end_date', 'updated_date', 'created_date', 'parent_id', 'page_id', 'user_id'])
      ->join('page_user', 'page_user.page_id=item.page_id', [])
      ->where(['page_user.user_id' => $me])
      ->where(['item.page_id' => $page_id]);

    if(null !== $parent_id) {
      $select->where(['item.parent_id' => $parent_id]);
    } else {
      $select->where(['item.parent_id IS NULL']);
    }

    if($is_admin_page !== true) {
      $select->where(['item.is_published IS TRUE']);
    }

    return $this->selectWith($select);
  }

  public function get($id, $me)
  {
    $select = $this->tableGateway->getSql()->select();
    $select->columns(['id', 'title', 'points','description', 'type', 'is_available', 'is_published', 'order', 'start_date', 'end_date', 'updated_date', 'created_date', 'parent_id', 'page_id', 'user_id'])
      ->join('page_user', 'page_user.page_id=item.page_id', [])
      ->where(['page_user.user_id' => $me])
      ->where(['id' => $id]);

    return $this->selectWith($select);
  }
}
