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
     * @return int
     */
    public function add($name = "")
    {
        $m_whiteboard = $this->getModel()
            ->setName($name)
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

        return $this->add($name);
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