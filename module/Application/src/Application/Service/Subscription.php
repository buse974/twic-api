<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class Subscription extends AbstractService
{
    public function add($libelle, $user_id = null)
    {
        if(null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $m_subscription = $this->getModel()
            ->setLibelle($libelle)
            ->setUserId($user_id);
        
        $res_subscription = $this->getMapper()->select($m_subscription);
        
        if ($res_subscription->count() <= 0) {
            $ret = $this->getMapper()->insert($m_subscription->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s')));
        }
        
        return $ret;
    }
    
    public function delete($libelle, $user_id = null)
    {
        if(null === $user_id) {
            $user_id = $this->getServiceUser()->getIdentity()['id'];
        }
        
        $m_subscription = $this->getModel()
            ->setLibelle($libelle)
            ->setUserId($user_id);
        
        return $this->getMapper()->delete($m_subscription);    
    }
    /**
     * Get Service User.
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }
}