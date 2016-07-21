<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Whiteboard
 *
 */

namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class Whiteboard
 */
class Whiteboard extends AbstractService
{
    /**
     * Create Whiteboard
     * 
     * @invokable
     * 
     * @param string $name
     * @param int $width
     * @param int $height
     * @throws \Exception
     * @return int
     */
    public function add($name = "", $width = null, $height = null)
    {
        $m_whiteboard = $this->getModel()
            ->setName($name)
            ->setWidth($width)
            ->setHeight($height)
            ->setOwnerId($this->getServiceUser()->getIdentity()['id']);
        
        if ($this->getMapper()->insert($m_whiteboard) <= 0) {
            throw new \Exception('error create Whiteboard');
        }
    
        return $this->getMapper()->getLastInsertValue();
    }
    
    /**
     * Create Whiteboard
     * 
     * @param array $data
     * @return int
     */
    public function _add($data)
    {
        $name = ((isset($data['name']))? $data['name']:null);
        $width = ((isset($data['width']))? $data['width']:null);
        $height = ((isset($data['height']))? $data['height']:null);

        return $this->add($name, $width, $height);
    }
    
    /**
     * Delete Whiteboard
     * 
     * @param integer $id
     * @return integer
     */
    public function delete($id)
    {
        return $this->getMapper()->delete($this->getModel()->setId($id));
    }
    
    /**
     * Get List
     *
     * @param int $submission_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($submission_id)
    {
        return $this->getMapper()->getList($submission_id);
    }
    
    /**
     * Get List By Conversation
     * 
     * @param int $conversation_id
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getListByConversation($conversation_id)
    {
        return $this->getMapper()->getListByConversation($conversation_id);
    }
    
    /**
     * Get Service User
     * 
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
        
    }
}