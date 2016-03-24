<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Document extends AbstractService
{

    /**
     * @param string $name
     * @param string $type
     * @param string $link
     * @param string $token
     * @param string $item_id
     * 
     * @throws \Exception
     * 
     * @return integer
     */
    public function add($name = null, $type = null, $link = null, $token = null, $item_id = null)
    {
        $library_id = $this->getServiceLibrary()->add($name, $link, $token, $type)->getId();
        $m_document = $this->getModel()
            ->setItemId($item_id)
            ->setLibraryId($library_id)
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
     * @return integer
     */
    public function delete($id) 
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));   
    }
    
    /**
     *
     * @return \Application\Service\Library
     */
    public function getServiceLibrary()
    {
        return $this->getServiceLocator()->get('app_service_library');
    }
}