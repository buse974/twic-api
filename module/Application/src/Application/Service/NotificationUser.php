<?php

namespace Application\Service;

use Dal\Service\AbstractService;

class NotificationUser extends AbstractService
{
    public function add($user, $notification)
    {
        if(!is_array($user)) {
            $user = [$user];
        }
        $m_notification_user = $this->getModel()->setNotificationId($notification);
        
        foreach ($user as $u) {
            $m_notification_user->setUserId($u);
            $this->getMapper()->insert($m_notification_user);
        }
        
        return true;
    }
}