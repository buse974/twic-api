<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Document extends AbstractService
{

    /**
     * @param string $title
     * @param string $link
     * @param string $token
     * @param string $item
     * @throws \Exception
     * 
     * @return integer
     */
    public function add($title = null, $link = null, $token = null, $item = null)
    {
        $m_document = $this->getModel()
            ->setTitle($title)
            ->setLink($link)
            ->setToken($token)
            ->setItemId($item)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_document) <= 0) {
            throw new \Exception();
        }
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     * 
     * @param integer $id
     * @param integer $item
     * @param string $title
     * @param string $link
     * @param string $token
     * @throws \Exception
     * 
     * @return integer
     */
    public function update($id = null, $item = null, $title = null, $link = null, $token = null)
    {
        if($id===null && $item===null) {
            throw new \Exception('id and item are null');
        }
        
        $m_document = $this->getModel()
            ->setTitle($title)
            ->setLink($link)
            ->setToken($token);
        
        $w = ($id!==null) ? ['id' => $id]:['item_id' => $item];
        
        return $this->getMapper()->update($m_document, $w);
    }
    
    /**
     * @invokable
     * 
     * @param integer $id
     * @return integer
     */
    public function delete($id) 
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));   
    }
}