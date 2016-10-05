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
     * @param int|array $user_id
     * @param string $role
     * @param strung $state
     * @return int
     */
    public function add($page_id, $user_id, $role, $state)
    {
        if(!is_array($user_id)){
            $user_id = [$user_id];
        }
        
        $m_page_user = $this->getModel()
            ->setPageId($page_id)
            ->setRole($role)
            ->setState($state);
        $ret = 0;
        foreach($user_id as $uid){
            $this->getServiceSubscription()->add('UP'.$page_id, $uid);
            $this->getServiceSubscription()->add('EP'.$page_id, $uid);
            
            $ret +=  $this->getMapper()->insert($m_page_user->setUserId($uid));
        }
        
        return $ret;
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
     * @invokable
     * 
     * @return \Dal\Db\ResultSet\ResultSet
     */
    public function getList($page_id, $filter = null, $state = null)
    {
        $mapper = $this->getMapper();
        $res = $mapper->usePaginator($filter)->getList($page_id, $state);
        
        return null !== $filter ? ['list' => $res,'count' => $mapper->count()] : $res;
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

    /**
     * Get Service Subscription
     *
     * @return \Application\Service\Subscription
     */
    private function getServiceSubscription()
    {
        return $this->container->get('app_service_subscription');
    }

}