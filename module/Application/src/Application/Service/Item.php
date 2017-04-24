<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Item extends AbstractService
{
  /**
  * Add item
  *
  * @invokable
  *
  * @param int page_id
  * @param string title
  * @param int $points
  * @param string description
  * @param string type
  * @param bool is_available
  * @param bool is_published
  * @param bool order
  * @param string start_date
  * @param string end_date
  * @param int parent_id
  *
  **/
  public function add($page_id, $title, $points = null, $description = null, $type = null, $is_available = null, $is_published = null, $order = null, $start_date = null, $end_date = null, $parent_id = null)
  {
    $identity = $this->getServiceUser()->getIdentity();

    $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'admin');
    if(!in_array($identity['id'], $ar_pu[$page_id])) {
      throw new \Exception("not admin of the page");
    }

    $user_id = $identity['id'];
    $m_item = $this->getModel()
      ->setPageId($page_id)
      ->setTitle($title)
      ->setPoints($points)
      ->setDescription($description)
      ->setType($type)
      ->setIsAvailable($is_available)
      ->setIsPublished($is_published)
      ->setOrder($order)
      ->setStartDate($start_date)
      ->setEndDate($end_date)
      ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
      ->setParentId($parent_id)
      ->setUserId($user_id);

      $this->getMapper()->insert($m_item);

      $id = (int)$this->getMapper()->getLastInsertValue();

      return $id;
  }

  /**
  * GetList Id Item
  *
  * @invokable
  *
  * @param int $page_id
  * @param int $parent_id
  */
  public function getListId($page_id = null, $parent_id = null)
  {
    $identity = $this->getServiceUser()->getIdentity();

    $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'admin');
    $is_admin_page = (in_array($identity['id'], $ar_pu[$page_id]));

    $res_item = $this->getMapper()->getListId($page_id, $identity['id'], $is_admin_page, $parent_id);

    $index = ($parent_id === null) ? $page_id : $parent_id;
    $ar_item = [];
    foreach ($res_item as $m_item) {
      $ar_item[$index] = $m_item->getId();
    }

    return $ar_item;
  }

  /**
  * Get Item
  *
  * @invokable
  *
  * @param int|array $id
  */
  public function get($id)
  {
    $identity = $this->getServiceUser()->getIdentity();
    $res_item = $this->getMapper()->get($id, $identity['id']);

    return (is_array($id)) ?
      $res_item->toArray(['id']) :
      $res_item->current();
  }

  /**
  * Add item
  *
  * @invokable
  *
  * @param int id
  * @param string title
  * @param int $points
  * @param string description
  * @param bool is_available
  * @param bool is_published
  * @param bool order
  * @param string start_date
  * @param string end_date
  * @param int parent_id
  *
  **/
  public function update($id, $title = null, $points = null, $description = null, $is_available = null, $is_published = null, $order = null, $start_date = null, $end_date = null, $parent_id = null)
  {
    $identity = $this->getServiceUser()->getIdentity();

    $m_item = $this->get($id);
    $ar_pu = $this->getServicePageUser()->getListByPage($m_item->getPageId(), 'admin');
    if(!in_array($identity['id'], $ar_pu[$m_item->getPageId()])) {
      throw new \Exception("not admin of the page");
    }

    $m_item = $this->getModel()
      ->setId($id)
      ->setTitle($title)
      ->setDescription($description)
      ->setIsAvailable($is_available)
      ->setPoints($points)
      ->setIsPublished($is_published)
      ->setOrder($order)
      ->setStartDate($start_date)
      ->setEndDate($end_date)
      ->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
      ->setParentId($parent_id);

      return $this->getMapper()->update($m_item);
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

  /**
   * Get Service Page User
   *
   * @return \Application\Service\PageUser
   */
  private function getServicePageUser()
  {
      return $this->container->get('app_service_page_user');
  }
}
