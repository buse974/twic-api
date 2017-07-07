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

  public function grade($item_id, $rate,$user_id = null, $group_id = null)
  {


    $page_id = $this->getServiceItem()->getLite($item_id)->current()->getPageId();
    $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'admin');
    $identity = $this->getServiceUser()->getIdentity();
    if(!in_array($identity['id'], $ar_pu[$page_id])) {
      throw new \Exception("No admin", 1);
    }

    if($user_id !== null) {
      if(!is_array($user_id)) {
        $user_id = [$user_id];
      }
      foreach ($user_id as $user) {
        $res_item_user = $this->getMapper()->select($this->getModel()->setUserId($user)->setItemId($item_id));
        if($res_item_user->count() > 0) {
          $this->getMapper()->update($this->getModel()->setId($res_item_user->current()->getId())->setRate($rate));
        } else {
          $this->getMapper()->insert($this->getModel()->setUserId($user)->setItemId($item_id)->setRate($rate));
        }
      }
    }

    if($group_id !== null) {
      if(!is_array($group_id)) {
        $group_id = [$group_id];
      }
      foreach ($group_id as $group) {
        $this->getMapper()->update($this->getModel()->setRate($rate), ['group_id' => $group]);
      }
    }

    return true;
  }

  /**
   * Get Service Page User
   *
   * @return \Application\Service\PageUser
   */
  private function getServicePageUser()
  {
      return $this->container->get('app_service_page_user');
  }

  /**
   * Get Service Item
   *
   * @return \Application\Service\Item
   */
  private function getServiceItem()
  {
      return $this->container->get('app_service_item');
  }

  /**
   * Get Service User
   *
   * @return \Application\Service\User
   */
  private function getServiceUser()
  {
      return $this->container->get('app_service_user');
  }
}
