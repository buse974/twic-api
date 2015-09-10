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
}