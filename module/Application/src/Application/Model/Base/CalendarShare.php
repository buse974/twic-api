<?php

namespace Application\Model\Base;

use Dal\Model\AbstractModel;

class CalendarShare extends AbstractModel
{
    protected $calendar_id;
    protected $user_id;

    protected $prefix = 'calendar_share';

    public function getCalendarId()
    {
        return $this->calendar_id;
    }

    public function setCalendarId($calendar_id)
    {
        $this->calendar_id = $calendar_id;

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
