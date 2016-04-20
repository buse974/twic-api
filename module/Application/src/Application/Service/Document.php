<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Document extends AbstractService
{

    /**
     * @invokable
     * 
     * @param string $name
     * @param string $type
     * @param string $link
     * @param string $token
     * @param string $item_id
     * @param integer $submission_id
     * @param integer $folder_id
     * 
     * @throws \Exception
     * 
     * @return integer
     */
    public function add($name = null, $type = null, $link = null, $token = null, $item_id = null, $submission_id = null, $folder_id = null)
    {
        if(null === $link && null === $token && null === $name) {
            return 0;
        }
        if($submission_id !== null) {
            $item_id = null;
        }
        
        if(null !== $item_id) {
            $this->getMapper()->delete($this->getModel()->setItemId($item_id));
        }
        
        
        $library_id = $this->getServiceLibrary()->add($name, $link, $token, $type, $folder_id)->getId();
        $m_document = $this->getModel()
            ->setItemId($item_id)
            ->setSubmissionId($submission_id)
            ->setLibraryId($library_id)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_document) <= 0) {
            throw new \Exception();
        }
        
        return $this->getMapper()->getLastInsertValue();
    }
    
    public function getListBySubmission($submission_id)
    {
        $m_document = $this->getModel()
            ->setSubmissionId($submission_id);

        return $this->getMapper()->select($m_document);
    }
    
    public function getListByItem($item_id)
    {
        $m_document = $this->getModel()
            ->setItemId($item_id);

        return $this->getMapper()->select($m_document);
    }
        
    /**
     * @invokable
     * 
     * @param integer $id
     * @param integer $submission_id
     * @param integer $library_id
     * @return boolean
     */
    public function delete($id = null, $submission_id = null, $library_id = null) 
    {
        if($id === null && ($submission_id === null || $library_id === null)) {
            return false;
        }
        
        return $this->getMapper()->delete($this->getModel()
            ->setId($id)
            ->setSubmissionId($submission_id)
            ->setLibraryId($library_id));
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