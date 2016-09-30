<?php
/**
 * 
 * TheStudnet (http://thestudnet.com)
 *
 * Subscription User
 *
 */
namespace Application\Service;

use Dal\Service\AbstractService;

/**
 * Class SubscriptionUser
 */
class SubscriptionUser extends AbstractService
{
    /**
     * Add subscription
     * 
     * @param string $libelle
     * @return int
     */
    public function add($libelle)
    {
        $m_subscription_user = $this->getModel()
            ->setLibelle($libelle)
            ->setUserId($this->getServiceUser()->getIdentity()['id'])
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        return $this->getMapper()->insert($m_subscription_user);
    }
    
    /**
     * Delete Subscription
     * 
     * @param string $libelle
     * @return int
     */
    public function delete($libelle)
    {
        $m_subscription_user = $this->getModel()
            ->setLibelle($libelle)
            ->setUserId($this->getServiceUser()->getIdentity()['id']);
    
        return $this->getMapper()->delete($m_subscription_user);
    }
    
    /**
     * Get Service User
     *
     * @return \Application\Service\User
     */
    private function getServiceUser()
    {
        return $this->container->get('app_service_user');
    }
}