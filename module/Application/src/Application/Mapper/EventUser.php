<?php

namespace Application\Mapper;

use Dal\Mapper\AbstractMapper;

class EventUser extends AbstractMapper
{
    public function inserUpdate($date, $me)
    {
        $sql = "INSERT INTO event_user (event_id, user_id, read_date)
                SELECT event.id as event_id, :user as user_id, :read_date as read_date FROM event_user
                RIGHT JOIN event ON event.id=event_user.event_id
                WHERE event.target <> 'user' AND
                event_user.read_date IS NULL";
        
        return $this->requestPdo($sql, ['user' => $me, 'read_date' => $date]);
    }
}