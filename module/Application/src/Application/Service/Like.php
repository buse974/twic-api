<?php
namespace Application\Service;

use Dal\Service\AbstractService;

class Like extends AbstractService
{

    /**
     * @invokable
     *
     * @param integer $event            
     */
    public function add($event)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        
        $m_like = $this->getModel()
            ->setEventId($event)
            ->setUserId($me)
            ->setIsLike(true)
            ->setCreatedDate((new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'));
        
        if ($this->getMapper()->insert($m_like) <= 0) {
            throw new \Exception('error add like');
        }
        
        $this->getServiceEvent()->userLike($event);
        
        return $this->getMapper()->getLastInsertValue();
    }

    /**
     * @invokable
     *
     * @param integer $event            
     */
    public function delete($event)
    {
        $me = $this->getServiceUser()->getIdentity()['id'];
        
        $m_like = $this->getModel()
            ->setEventId($event)
            ->setUserId($me);
                
        return $this->getMapper()->delete($m_like);
    }

    /**
     * @invokable
     *
     * @param integer $feed            
     */
    public function getList($feed)
    {
        return $this->getServiceUser()->getList(null, null, null, null, null, null, null, null, null, null, null, $feed);
    }

    /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->serviceLocator->get('app_service_user');
    }

    /**
     *
     * @return \Application\Service\Feed
     */
    public function getServiceFeed()
    {
        return $this->serviceLocator->get('app_service_feed');
    }

    /**
     *
     * @return \Application\Service\Contact
     */
    public function getServiceContact()
    {
        return $this->serviceLocator->get('app_service_contact');
    }

    /**
     *
     * @return \Application\Service\Event
     */
    public function getServiceEvent()
    {
        return $this->serviceLocator->get('app_service_event');
    }
}