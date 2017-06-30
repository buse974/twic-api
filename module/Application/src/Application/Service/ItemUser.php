<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use Zend\Db\Sql\Predicate\IsNull;

class ItemUser extends AbstractService
{
  /**
  * GetList User of Item
  *
  * @param int $item_id
  */
  public function getList($item_id)
  {
    return $this->getMapper()->getList($item_id);
  }

  /**
  * Add User In Item
  *
  * @param int $item_id
  * @param int|array $user_id
  */
  public function addUsers($item_id, $user_id)
  {
    if(!is_array($user_id)) {
      $user_id = [$user_id];
    }

    $res_item_user = $this->getMapper()->select($this->getModel()->setUserId($user_id)->setItemId($item_id));
    foreach ($res_item_user as $m_item_user) {
      $this->getMapper()->update($this->getModel()->setDeletedDate(new IsNull('deleted_date')), ['id' => $m_item_user->getId()]);
      unset($user_id[array_search($m_item_user->getUserId(), $user_id)]);
    }

    foreach ($user_id as $user) {
      $this->getMapper()->insert($this->getModel()->setUserId($user)->setItemId($item_id));
    }

    return true;
  }

  /**
  * Delete User In Item
  *
  * @param int $item_id
  * @param int|array $user_id
  */
  public function deleteUsers($item_id, $user_id)
  {
    if(!is_array($user_id)) {
      $user_id = [$user_id];
    }

    $m_item_user = $this->getModel()->setDeletedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
    foreach ($user_id as $user) {
      $this->getMapper()->update($m_item_user, ['user_id' => $user, 'item_id' => $item_id]);
    }

    return true;
  }

}
