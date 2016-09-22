<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class PageUser extends AbstractService
{
    
    /**
     * Add Page User Relation 
     * 
     * @invokable
     * 
     * @param int $page_id
     * @param int $user_id
     * @param string $role
     * @param strung $state
     * @return int
     */
    public function add($page_id, $user_id, $role, $state)
    {
        $m_page_user = $this->getModel()
            ->setPageId($page_id)
            ->setUserId($user_id)
            ->setRole($role)
            ->setState($state);
        
        return $this->getMapper()->insert($m_page_user);
    }
    
    
    /**
     * Update Page User Relation 
     * 
     * @invokable
     * 
     * @param int $page_id
     * @param int $user_id
     * @param string $role
     * @param strung $state
     * @return int
     */
    public function update($page_id, $user_id, $role, $state)
    {
        $m_page_user = $this->getModel()
            ->setRole($role)
            ->setState($state);
        
        return $this->getMapper()->update($m_page_user, ['page_id' => $page_id, 'user_id' => $user_id]);
    }
    
    
    /**
     * Delete Page User Relation 
     * 
     * @invokable
     * 
     * @param int $page_id
     * @param int $user_id
     * @return int
     */
    public function delete($page_id, $user_id)
    {
        $m_page_user = $this->getModel()
            ->setPageId($page_id)
            ->setUserId($user_id);
        
        return $this->getMapper()->delete($m_page_user);
    }
    
    /**
     * Get List Page User Relation
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($page_id)
    {
        $m_page_user = $this->getModel()->setPageId($page_id);
        
        return $this->getMapper()->select($m_page_user);
    }
    /**
     * Add Array
     * 
     * @param int $page_id
     * @param array $data
     * @return array
     */
    public function _add($page_id, $data)
    {
        $ret = [];
        foreach ($data as $ar_u) {
            $user_id = (isset($ar_u['user_id'])) ? $ar_u['user_id']:null;
            $role = (isset($ar_u['role'])) ? $ar_u['role']:null;
            $state = (isset($ar_u['state'])) ? $ar_u['state']:null;
            
            $ret[$user_id] = $this->add($page_id, $user_id, $role, $state);
        }
        
        return $ret;
    }
    
    /**
     * Add Array
     *
     * @param int $page_id
     * @param array $data
     * @return array
     */
    public function replace($page_id, $data)
    {
        $this->getMapper()->delete($this->getModel()->setPageId($page_id));
        
        return $this->_add($page_id, $data);
    }

}