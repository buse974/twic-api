<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Item extends AbstractService
{
  public function add(
    $page_id,
    $title,
    $description = null,
    $type = null,
    $is_available = null,
    $is_published = null,
    $order = null,
    $start_date = null,
    $end_date = null,
    $parent_id = null)
  {
    $m_item = $this->getModel()
      ->setTitle($title)
      ->setDescription($description)
      ->setType($type)
      ->setIsAvailable($is_available)
      ->setIsPublished($is_published)
      ->setOrder($order)
      ->setStartDate($start_date)
      ->setEndDate($end_date)
      ->setUpdatedDate($updated_date)
      ->setCreatedDate($created_date)
      ->setParentId($parent_id)
      ->setPageId($page_id)
      ->setUserId($user_id);
      
  }

  public function getList()
  {

  }

  public function getList()
  {

  }
}
