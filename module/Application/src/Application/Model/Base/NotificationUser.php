<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class NotificationUser extends AbstractModel
{
    protected $notification_id;
    protected $user_id;

    protected $prefix = 'notification_user';

    public function getNotificationId()
    {
        return $this->notification_id;
    }

    public function setNotificationId($notification_id)
    {
        $this->notification_id = $notification_id;

        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
}
