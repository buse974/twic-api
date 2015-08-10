<?php

namespace Application\Service;

use Dal\Service\AbstractService;
use DateTimeZone;
use DateTime;

class ItemAssigmentDocument extends AbstractService
{
    public function add($item_assigment_id, $type = null, $title = null, $author = null, $link = null, $source = null, $token = null, $date = null)
    {
        $m_item_assigment_document = $this->getModel()->setItemAssigmentId($item_assigment_id)
                                                      ->setType($type)
                                                       ->setTitle($title)
                                                       ->setAuthor($author)
                                                       ->setLink($link)
                                                       ->setSource($source)
                                                       ->setToken($token)
                                                       ->setDate($date)
                                                       ->setCreatedDate((new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s'));

        return $this->getMapper()->insert($m_item_assigment_document);
    }

    public function deleteByItemAssignment($item_assigment)
    {
        return $this->getMapper()->delete($this->getModel()->setItemAssigmentId($item_assigment));
    }

    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    public function getListByItemAssignment($item_assignment)
    {
        return $this->getMapper()->getListByItemAssignment($item_assignment);
    }
}
