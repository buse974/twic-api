<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Document extends AbstractService
{

    public function add($title, $link, $token, $item)
    {
        $m_document = $this->getModel()
            ->setTitle($title)
            ->setLink($link)
            ->setToken($token)
            ->setItemId($item)
            ->setCreatedDate();
        
        if($this->getMapper()->insert($m_document)) {
            throw new \Exception();
        }
        
        return $this->getMapper()->getLastInsertValue();
    }
}