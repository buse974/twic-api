<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class WorkshopSessionUser extends AbstractModel
{
    protected $user_id;
    protected $workshop_session_id;

    protected $prefix = 'workshop_session_user';

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getWorkshopSessionId()
    {
        return $this->workshop_session_id;
    }

    public function setWorkshopSessionId($workshop_session_id)
    {
        $this->workshop_session_id = $workshop_session_id;

        return $this;
    }
}
