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
  * @param int $order_id
  * @param string start_date
  * @param string end_date
  * @param int parent_id
  *
  **/
  public function add(
    $page_id,
    $title,
    $points = null,
    $description = null,
    $type = null,
    $is_available = null,
    $is_published = null,
    $order_id = null,
    $start_date = null,
    $end_date = null,
    $parent_id = null,
    $library_id = null,
    $post_id = null,
    $text = null,
    $participants = null
    )
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
      ->setStartDate($start_date)
      ->setEndDate($end_date)
      ->setLibraryId($library_id)
      ->setText($text)
      ->setParticipants($participants)
      ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
      ->setUserId($user_id);

      $this->getMapper()->insert($m_item);

      $id = (int)$this->getMapper()->getLastInsertValue();

      if(null !== $post_id) {
        $this->getServicePost()->update($post_id,null,null,null,null,null,null,null,null,null,null,null,null,null, $id);
      }

      $this->move($id, -1, $parent_id);

      return $id;
  }

  /**
  * Move Item
  *
  * @invokable
  *
  * @param int $id
  * @param int $order_id
  * @param int $parent_id
  */
  public function move($id, $order_id = null, $parent_id = null)
  {
    if(null !== $parent_id) {
      $this->getMapper()->update($this->getModel()->setParentId($parent_id)->setId($id));
    }

    $m_base_order = $this->getMapper()->select($this->getModel()->setId($id))->current();

    if(-1 === $order_id || (null === $order_id && null !== $parent_id) ) {
      $order = 1;
      //on récuper l'ordre le plus grand +1
      $res_order_last = $this->getMapper()->getLastOrder($id, $m_base_order->getPageId(), $m_base_order->getParentId());
      if($res_order_last->count() > 0) {
        $order = $res_order_last->current()->getOrder()+1;
      }

      //on atribut l'ordre
      $this->getMapper()->update($this->getModel()->setId($id)->setOrder($order));
    } elseif(is_numeric($order_id)) {
      //on verirfie si il existe une ordre superieur

      if($order_id !== 0) {
        $m_order = $this->getMapper()->select($this->getModel()->setId($order_id))->current();
        $order = ($m_order->getOrder()+1);
      } else {
        $order = 1;
      }

      $res_order_sup = $this->getMapper()->select($this->getModel()->setOrder($order));
      if($res_order_sup->count() > 0) {
        //si oui on decaler
        $this->getMapper()->uptOrder($m_base_order->getPageId(), $order, $m_base_order->getParentId());
      }

      //on atribut l'ordre
      $this->getMapper()->update($this->getModel()->setId($id)->setOrder($order));
    }

  }

  /**
  * GetList User of Item
  *
  * @invokable
  *
  * @param int $id
  */
  public function getListItemUser($id)
  {
    if(!is_array($id)) {
      $id = [$id];
    }

    $arr_item_user = [];
    foreach ($id as $i) {
      $arr_item_user[$i]=[];
    }
    $res_item_user = $this->getServiceItemUser()->getList($id);
    foreach ($res_item_user as $m_item_user) {
      $arr_item_user[$m_item_user->getItemId()][] = $m_item_user->toArray();
    }

    return $arr_item_user;
  }

  /**
  * Add User In Item
  *
  * @invokable
  *
  * @param int $id
  * @param int|array $user_ids
  * @param int $group_id
  * @param string $group_name
  */
  public function addUsers($id, $user_ids, $group_id = null, $group_name = null)
  {
    return $this->getServiceItemUser()->addUsers($id, $user_ids);
  }

  /**
  * Delete User In Item
  *
  * @invokable
  *
  * @param int $id
  * @param int|array $user_ids
  */
  public function deleteUsers($id, $user_ids)
  {
    return $this->getServiceItemUser()->deleteUsers($id, $user_ids);
  }

  /**
  * GetList Id Item
  *
  * @invokable
  *
  * @param int $page_id
  * @param int $parent_id
  * @param bool $is_publish
  */
  public function getListId($page_id = null, $parent_id = null, $is_publish = null)
  {
    $identity = $this->getServiceUser()->getIdentity();

    if(is_array($page_id)) {
      $page_id = reset($page_id);
    }
    if(null === $page_id) {
      $page_id = $this->getServicePage()->getIdByItem($parent_id);
    }

    $ar_pu = $this->getServicePageUser()->getListByPage($page_id, 'admin');
    $is_admin_page = (in_array($identity['id'], $ar_pu[$page_id]));

    if($is_admin_page === true && $is_publish === true) {
      $is_admin_page = false;
    }

    $res_item = $this->getMapper()->getListId($page_id, $identity['id'], $is_admin_page, $parent_id);

    $index = ($parent_id === null) ? $page_id : $parent_id;

    if(is_array($index)) {
      foreach ($index as $i) {
        $ar_item[$i] = [];
      }
    } else {
      $ar_item[$index] = [];
    }

    foreach ($res_item as $m_item) {
      $ii = (!is_numeric($m_item->getParentId())) ? $m_item->getPageId() : $m_item->getParentId();
      $ar_item[$ii][] = $m_item->getId();
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
  public function update(
    $id,
    $title = null,
    $points = null,
    $description = null,
    $is_available = null,
    $is_published = null,
    $order = null,
    $start_date = null,
    $end_date = null,
    $parent_id = null,
    $library_id = null,
    $post_id = null,
    $text = null,
    $participants = null)
  {
    $identity = $this->getServiceUser()->getIdentity();

    $m_item = $this->get($id);
    $ar_pu = $this->getServicePageUser()->getListByPage($m_item->getPageId(), 'admin');
    if(!in_array($identity['id'], $ar_pu[$m_item->getPageId()])) {
      throw new \Exception("not admin of the page");
    }

    if(null !== $post_id) {
      $this->getServicePost()->update($post_id,null,null,null,null,null,null,null,null,null,null,null,null,null, $id);
    }

    $m_item = $this->getModel()
      ->setId($id)
      ->setTitle($title)
      ->setDescription($description)
      ->setIsAvailable($is_available)
      ->setPoints($points)
      ->setIsPublished($is_published)
      ->setOrder($order)
      ->setLibraryId($library_id)
      ->setText($text)
      ->setParticipants($participants)
      ->setStartDate($start_date)
      ->setEndDate($end_date)
      ->setUpdatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
      ->setParentId($parent_id);

      return $this->getMapper()->update($m_item);
  }

  /**
  * Delete Item
  *
  * @invokable
  *
  * @param int $id
  **/
  public function delete($id)
  {
    $identity = $this->getServiceUser()->getIdentity();

    $m_item = $this->get($id);
    $ar_pu = $this->getServicePageUser()->getListByPage($m_item->getPageId(), 'admin');
    if(!in_array($identity['id'], $ar_pu[$m_item->getPageId()])) {
      throw new \Exception("not admin of the page");
    }

    return $this->getMapper()->delete($this->getModel()->setId($id));
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
   * Get Service Page
   *
   * @return \Application\Service\Page
   */
  private function getServicePage()
  {
      return $this->container->get('app_service_page');
  }

  /**
   * Get Service Post
   *
   * @return \Application\Service\Post
   */
  private function getServicePost()
  {
      return $this->container->get('app_service_post');
  }

  /**
   * Get Service Item User
   *
   * @return \Application\Service\ItemUser
   */
  private function getServiceItemUser()
  {
      return $this->container->get('app_service_item_user');
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
