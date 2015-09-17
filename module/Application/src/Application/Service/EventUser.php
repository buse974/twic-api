<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class EventUser extends AbstractService
{
    public function add($user, $notification)
    {
        if(!is_array($user)) {
            $user = [$user];
        }
        $m_event_user = $this->getModel()->setEventId($notification);
    
        foreach ($user as $u) {
            $m_event_user->setUserId($u);
            $this->getMapper()->insert($m_event_user);
        }
    
        return true;
    }
    
     /**
     * @invokable
     */
    public function read($ids = null)
    {
        $date = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $me = $this->getServiceUser()->getIdentity()['id'];  
        $m_event = $this->getModel()->setUserId($me)->setReadDate($date);
        if(null !== $ids){
            $nb = 0;
            foreach($ids as $id){
               $nb += $this->getMapper()->update($m_event->setEventId($id));
            }
            return $nb;
        }
        else{
            
            return $this->getMapper()->update($m_event, array('user_id' => $me));
        }
        
    }
    
      /**
     *
     * @return \Application\Service\User
     */
    public function getServiceUser()
    {
        return $this->getServiceLocator()->get('app_service_user');
    }
}